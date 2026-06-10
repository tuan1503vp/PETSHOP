<?php
class OrderController extends Controller {
    private $orderModel;
    private $productModel;

    private $activityLogModel;

    public function __construct() {
        $this->orderModel = $this->model('Order');
        $this->productModel = $this->model('Product');
        $this->activityLogModel = $this->model('ActivityLog');
    }

    // Xử lý form thanh toán
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()) {
            // Lấy dữ liệu từ giỏ hàng
            if (empty($_SESSION['cart'])) {
                header('Location: ' . URLROOT . '/product');
                return;
            }

            $total_amount = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }

            $data = [
                'customer_id'      => $_SESSION['user_id'],
                'total_amount'     => $total_amount,
                'payment_method'   => trim($_POST['payment_method']),
                'shipping_name'    => trim($_POST['fullname']),
                'shipping_phone'   => trim($_POST['phone']),
                'shipping_address' => trim($_POST['address']),
            ];

            // Tạo đơn hàng
            $order_id = $this->orderModel->createOrder($data);

            if ($order_id) {
                // Thêm chi tiết đơn hàng
                foreach ($_SESSION['cart'] as $item) {
                    $this->orderModel->addOrderItem($order_id, $item['id'], $item['quantity'], $item['price']);
                }

                // Xóa giỏ hàng
                unset($_SESSION['cart']);

                // Cập nhật hạng hội viên dựa trên chi tiêu mới
                $userModel = $this->model('User');
                $userModel->updateMembershipTier($_SESSION['user_id']);

                // Gửi email xác nhận đơn hàng
                $mailer = new Mailer();
                $customer_email = $_SESSION['user_email'] ?? 'customer@example.com';
                $mailer->sendOrderConfirmation(
                    $customer_email, 
                    $data['shipping_name'], 
                    $order_id, 
                    $data['total_amount'], 
                    $data['payment_method']
                );

                // Chuyển hướng đến trang thành công
                header('Location: ' . URLROOT . '/order/success/' . $order_id);
            } else {
                die('Có lỗi xảy ra khi tạo đơn hàng.');
            }
        } else {
            header('Location: ' . URLROOT . '/cart');
        }
    }

    public function success($order_id) {
        if (!isLoggedIn()) {
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }

        $data = [
            'order_id' => $order_id
        ];

        $this->view('order/success', $data);
    }

    // API: Kiểm tra trạng thái thanh toán (polling từ client)
    public function check_payment($order_id) {
        header('Content-Type: application/json');
        if (!isLoggedIn()) {
            echo json_encode(['success' => false]);
            return;
        }
        $order = $this->orderModel->getOrderById($order_id);
        if (!$order) {
            echo json_encode(['success' => false, 'status' => 'not_found']);
            return;
        }
        // Chỉ trả về status cho khách hàng sở hữu đơn hàng
        if ($order->customer_id != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'status' => 'forbidden']);
            return;
        }
        echo json_encode([
            'success' => true,
            'status'  => $order->status,
            'paid'    => in_array($order->status, ['shipping', 'completed'])
        ]);
    }

    // API: Tải lên biên lai chuyển khoản
    public function upload_receipt($order_id) {
        header('Content-Type: application/json');
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['receipt'])) {
            $order = $this->orderModel->getOrderById($order_id);
            if (!$order || $order->customer_id != $_SESSION['user_id'] || $order->status != 'pending') {
                echo json_encode(['success' => false, 'message' => 'Đơn hàng không hợp lệ']);
                return;
            }

            $file = $_FILES['receipt'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($file['type'], $allowedTypes)) {
                echo json_encode(['success' => false, 'message' => 'Chỉ hỗ trợ file ảnh JPG/PNG']);
                return;
            }

            $uploadDir = APPROOT . '/../public/uploads/receipts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = 'receipt_' . $order_id . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $destination = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // Update database
                $db = new Database();
                $db->query("UPDATE orders SET receipt_image = :image WHERE id = :id");
                $db->bind(':image', $fileName);
                $db->bind(':id', $order_id);
                $db->execute();

                echo json_encode(['success' => true, 'message' => 'Tải biên lai thành công', 'file' => $fileName]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi lưu file']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No file uploaded']);
        }
    }

    public function history() {
        if (!isLoggedIn()) {
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }

        $userModel = $this->model('User');
        $history = $userModel->getCombinedHistory($_SESSION['user_id']);

        $data = [
            'history' => $history
        ];

        $this->view('order/history', $data);
    }

    public function request_refund($order_id) {
        if (!isLoggedIn()) {
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Loại bỏ FILTER_SANITIZE_STRING do đã bị deprecated từ PHP 8.1

            $order = $this->orderModel->getOrderById($order_id);
            if (!$order || $order->customer_id != $_SESSION['user_id'] || $order->status != 'cancelled' || $order->payment_method != 'transfer') {
                die('Yêu cầu không hợp lệ.');
            }

            $refund_bank = trim($_POST['refund_bank']);
            $refund_account = trim($_POST['refund_account']);
            $refund_name = trim($_POST['refund_name']);

            $db = new Database();
            $db->query("UPDATE orders SET refund_bank = :bank, refund_account = :account, refund_name = :name, refund_status = 'pending' WHERE id = :id");
            $db->bind(':bank', $refund_bank);
            $db->bind(':account', $refund_account);
            $db->bind(':name', $refund_name);
            $db->bind(':id', $order_id);
            
            if($db->execute()) {
                header('Location: ' . URLROOT . '/order/history');
            } else {
                die('Có lỗi xảy ra khi gửi yêu cầu hoàn tiền.');
            }
        }
    }

    // API: Thanh toán tại quầy (POS)
    public function pos_checkout() {
        // Kiểm tra quyền (Admin, Manager, Cashier)
        if (!isLoggedIn() || !in_array($_SESSION['user_role'], ['admin', 'manager', 'cashier'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $json = file_get_contents('php://input');
                $input = json_decode($json, true);

                if (!$input || empty($input['cart'])) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống']);
                    return;
                }

                $cartItems = $input['cart'];
                $customer_name = !empty($input['customer_name']) ? $input['customer_name'] : 'Khách lẻ';
                $customer_phone = !empty($input['customer_phone']) ? $input['customer_phone'] : null;
                $payment_method = !empty($input['payment_method']) ? $input['payment_method'] : 'cash';
                
                $customer_id = null;
                $userModel = $this->model('User');
                $appointmentModel = $this->model('Appointment');
                
                // Tìm kiếm xem có khách hàng thành viên nào khớp thông tin không để tích lũy chi tiêu
                if ($customer_name !== 'Khách lẻ') {
                    $db = new Database();
                    $db->query("SELECT u.id FROM users u 
                               LEFT JOIN members m ON u.id = m.user_id 
                               WHERE u.role = 'customer' AND (u.fullname = :name OR (m.phone = :phone AND m.phone IS NOT NULL)) 
                               LIMIT 1");
                    $db->bind(':name', $customer_name);
                    $db->bind(':phone', $customer_phone);
                    $row = $db->single();
                    if ($row) {
                        $customer_id = $row->id;
                    }
                }

                $product_total = 0;
                $appointment_total = 0;
                foreach ($cartItems as $item) {
                    if (empty($item['is_appointment'])) {
                        $product_total += (float)$item['price'] * (int)$item['quantity'];
                    } else {
                        $appointment_total += (float)$item['price'] * (int)$item['quantity'];
                    }
                }
                
                $grand_total = $product_total + $appointment_total;

                // Tạo đơn hàng chính
                $orderData = [
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'customer_phone' => $customer_phone,
                    'total_amount' => $product_total, // Tổng tiền sản phẩm
                    'payment_method' => $payment_method,
                    'order_type' => 'pos',
                    'status' => 'completed'
                ];

                $order_id = $this->orderModel->createOrder($orderData);

                if ($order_id) {
                    $details_log = [];
                    foreach ($cartItems as $item) {
                        if (!empty($item['is_appointment'])) {
                            // Xử lý lịch hẹn
                            $real_id = isset($item['real_id']) ? $item['real_id'] : str_replace('app_', '', $item['id']);
                            $appointmentModel->updateStatus($real_id, 'completed');
                            $appointmentModel->updateFinalPrice($real_id, (float)$item['price'] * (int)$item['quantity']);
                            $details_log[] = "[Dịch vụ] " . $item['name'] . " (x" . $item['quantity'] . ")";
                        } else {
                            // Xử lý sản phẩm
                            $this->orderModel->addOrderItem($order_id, $item['id'], $item['quantity'], $item['price']);
                            $this->productModel->decreaseStock($item['id'], $item['quantity']);
                            $details_log[] = "[Sản phẩm] " . $item['name'] . " (x" . $item['quantity'] . ")";
                        }
                    }
                    
                    // Cập nhật hạng thành viên nếu là khách hàng đã đăng ký
                    if ($customer_id) {
                        $userModel->updateMembershipTier($customer_id);
                    }

                    // Ghi nhật ký hành vi thanh toán POS thành công
                    $payment_label = ($payment_method == 'cash') ? 'Tiền mặt' : 'Chuyển khoản VietQR';
                    $log_details = "Thanh toán đơn POS #" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " cho " . $customer_name . ".\n";
                    $log_details .= "Hình thức: " . $payment_label . ".\n";
                    $log_details .= "Tổng thanh toán: " . number_format($grand_total) . "đ.\n";
                    $log_details .= "Chi tiết mục mua: " . implode(', ', $details_log);
                    
                    $this->activityLogModel->log(
                        $_SESSION['user_id'], 
                        $_SESSION['user_name'], 
                        $_SESSION['user_role'], 
                        'Thanh toán POS', 
                        $log_details
                    );

                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true, 
                        'order_id' => $order_id,
                        'total_amount' => $grand_total,
                        'payment_method' => $payment_method,
                        'customer_name' => $customer_name,
                        'customer_phone' => $customer_phone,
                        'cashier_name' => $_SESSION['user_name'],
                        'cart' => $cartItems
                    ]);
                    return;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn hàng trong cơ sở dữ liệu']);
                    return;
                }
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Lỗi phát sinh: ' . $e->getMessage()]);
                return;
            } catch (Throwable $t) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống nghiêm trọng: ' . $t->getMessage()]);
                return;
            }
        }
    }

    public function details($id, $type = 'order') {
        header('Content-Type: application/json');
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($type == 'appointment') {
            $appointmentModel = $this->model('Appointment');
            $detail = $appointmentModel->getAppointmentById($id);
            echo json_encode(['success' => true, 'type' => 'appointment', 'data' => $detail]);
        } else {
            $order = $this->orderModel->getOrderById($id);
            $items = $this->orderModel->getOrderItems($id);
            echo json_encode(['success' => true, 'type' => 'order', 'order' => $order, 'items' => $items]);
        }
    }

    public function invoice($id, $type = 'order') {
        if (!isLoggedIn()) {
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }

        if ($type == 'appointment') {
            $appointmentModel = $this->model('Appointment');
            $detail = $appointmentModel->getAppointmentById($id);
            $data = [
                'type' => 'appointment',
                'detail' => $detail
            ];
        } else {
            $order = $this->orderModel->getOrderById($id);
            $items = $this->orderModel->getOrderItems($id);
            $data = [
                'type' => 'order',
                'order' => $order,
                'items' => $items
            ];
        }

        $this->view('order/invoice', $data);
    }
}
