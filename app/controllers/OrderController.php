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

            $userModel = $this->model('User');
            $memDiscountInfo = $userModel->getMembershipDiscount($_SESSION['user_id']);
            $memDiscountAmount = floor(($total_amount * $memDiscountInfo['discount_percent']) / 100);

            $voucher_code = trim($_POST['voucher_code'] ?? '');
            $discount_amount = 0;
            $finalMemDiscount = $memDiscountAmount;

            if ($voucher_code) {
                require_once APPROOT . '/models/Voucher.php';
                $voucherModel = new Voucher();
                $voucher = $voucherModel->getVoucherByCode($voucher_code, $_SESSION['user_id']);
                if ($voucher && $total_amount >= $voucher->min_order_value) {
                    $eligible_amount = 0;
                    if (!empty($voucher->category_id)) {
                        foreach ($_SESSION['cart'] as $item) {
                            $item_cat_id = $item['category_id'] ?? null;
                            if (!$item_cat_id) {
                                $p = $this->productModel->getProductById($item['id']);
                                $item_cat_id = $p->category_id ?? null;
                            }
                            if ($item_cat_id == $voucher->category_id) {
                                $eligible_amount += $item['price'] * $item['quantity'];
                            }
                        }
                    } else {
                        $eligible_amount = $total_amount;
                    }

                    if ($eligible_amount > 0) {
                        if ($voucher->discount_type == 'percent') {
                            $discount_amount = $eligible_amount * ($voucher->discount_amount / 100);
                            if (!empty($voucher->max_discount) && $discount_amount > $voucher->max_discount) {
                                $discount_amount = $voucher->max_discount;
                            }
                        } else {
                            $discount_amount = min($voucher->discount_amount, $eligible_amount);
                        }
                        $discount_amount = floor($discount_amount);
                        
                        // Check combinability
                        if (!$voucher->is_combinable && $memDiscountAmount > 0) {
                            if ($discount_amount <= $memDiscountAmount) {
                                $discount_amount = 0;
                                $voucher_code = ''; // Ignore voucher
                            } else {
                                $finalMemDiscount = 0; // Disable membership discount
                            }
                        }
                    } else {
                        $voucher_code = ''; // Ignore if no eligible items
                    }
                } else {
                    $voucher_code = ''; // Invalid voucher
                }
            }

            $final_amount = max(0, $total_amount - $finalMemDiscount - $discount_amount);

            $data = [
                'customer_id' => $_SESSION['user_id'],
                'customer_name' => $_SESSION['user_name'],
                'customer_phone' => $_POST['phone'] ?? '',
                'total_amount' => $final_amount,
                'payment_method' => trim($_POST['payment_method']),
                'order_type' => 'online',
                'status' => 'pending',
                'shipping_name' => trim($_POST['fullname']),
                'shipping_phone' => trim($_POST['phone']),
                'shipping_address' => trim($_POST['address']),
                'voucher_code' => $voucher_code ?: null,
                'discount_amount' => $discount_amount + $finalMemDiscount
            ];

            // Tạo đơn hàng
            $order_id = $this->orderModel->createOrder($data);

            if ($order_id) {
                if ($voucher_code) {
                    $voucherModel->markVoucherAsUsed($voucher_code);
                }
                
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

    // API: Validate voucher
    public function validate_voucher() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $code = trim($data['code'] ?? '');
            $customer_id = $data['customer_id'] ?? null;
            
            if (!$code || !$customer_id) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }
            
            require_once APPROOT . '/models/Voucher.php';
            $voucherModel = new Voucher();
            $voucher = $voucherModel->getVoucherByCode($code, $customer_id);
            
            if ($voucher) {
                echo json_encode(['success' => true, 'discount' => $voucher->discount_amount, 'title' => $voucher->title]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Mã không hợp lệ hoặc đã sử dụng']);
            }
        }
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

            if (compressImage($file['tmp_name'], $destination, 1200)) {
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
                $voucher_code = !empty($input['voucher_code']) ? trim($input['voucher_code']) : null;
                
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

                $discount_amount = 0;
                if ($voucher_code && $customer_id) {
                    require_once APPROOT . '/models/Voucher.php';
                    $voucherModel = new Voucher();
                    $voucher = $voucherModel->getVoucherByCode($voucher_code, $customer_id);
                    if ($voucher) {
                        $discount_amount = $voucher->discount_amount;
                        $voucherModel->markVoucherAsUsed($voucher_code);
                    }
                }
                
                $grand_total = max(0, $grand_total - $discount_amount);

                // Tạo đơn hàng chính nếu có sản phẩm
                $order_id = null;
                if ($product_total > 0) {
                    $orderData = [
                        'customer_id' => $customer_id,
                        'customer_name' => $customer_name,
                        'customer_phone' => $customer_phone,
                        'total_amount' => $grand_total,
                        'payment_method' => $payment_method,
                        'order_type' => 'pos',
                        'status' => 'completed',
                        'voucher_code' => $voucher_code ?: null,
                        'discount_amount' => $discount_amount
                    ];
                    
                    if ($payment_method == 'vnpay') {
                        $orderData['status'] = 'pending';
                    }

                    $order_id = $this->orderModel->createOrder($orderData);
                }

                if ($order_id || $appointment_total > 0) {
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
                            if ($order_id) {
                                $this->orderModel->addOrderItem($order_id, $item['id'], $item['quantity'], $item['price']);
                            }
                            $this->productModel->decreaseStock($item['id'], $item['quantity']);
                            $details_log[] = "[Sản phẩm] " . $item['name'] . " (x" . $item['quantity'] . ")";
                        }
                    }
                    
                    // Cập nhật hạng thành viên nếu là khách hàng đã đăng ký
                    if ($customer_id) {
                        $userModel->updateMembershipTier($customer_id);

                        require_once APPROOT . '/models/Coin.php';
                        $coinModel = new Coin();
                        $customer = $userModel->getUserById($customer_id);
                        if ($customer) {
                            $level = $customer->membership_level ?? 'Đồng';
                            $multiplier = 1;
                            switch($level) {
                                case 'Đồng': $multiplier = 1; break;
                                case 'Bạc': $multiplier = 1.2; break;
                                case 'Vàng': $multiplier = 1.5; break;
                                case 'Bạch Kim': $multiplier = 2; break;
                                case 'VIP': $multiplier = 3; break;
                            }
                            $coins_earned = floor(($grand_total / 100000) * $multiplier);
                            if ($coins_earned > 0) {
                                $coinModel->addCoins($customer->id, $coins_earned, 'Hoàn xu đơn hàng POS #ORD-' . str_pad($order_id, 5, '0', STR_PAD_LEFT));
                            }
                        }
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
            
            // Load review if exists
            $reviewModel = $this->model('AppointmentReview');
            $review = $reviewModel->getReviewByAppointmentId($id);
            
            echo json_encode(['success' => true, 'type' => 'appointment', 'data' => $detail, 'review' => $review]);
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

    public function review_appointment() {
        if (!isLoggedIn()) {
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $appointment_id = intval($_POST['appointment_id']);
            $rating = intval($_POST['rating'] ?? 5);
            $comment = trim($_POST['comment'] ?? '');

            // Load appointment details to get service_id and doctor_id
            $appointmentModel = $this->model('Appointment');
            $appointment = $appointmentModel->getAppointmentById($appointment_id);

            if ($appointment && $appointment->customer_id == $_SESSION['user_id'] && $appointment->status == 'completed') {
                $reviewModel = $this->model('AppointmentReview');
                
                // Double check if already reviewed
                $existing = $reviewModel->getReviewByAppointmentId($appointment_id);
                if ($existing) {
                    flash('history_msg', 'Lịch hẹn này đã được đánh giá trước đó.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
                } else {
                    $data = [
                        'appointment_id' => $appointment_id,
                        'user_id' => $_SESSION['user_id'],
                        'service_id' => $appointment->service_id,
                        'doctor_id' => $appointment->doctor_id,
                        'rating' => $rating,
                        'comment' => $comment
                    ];
                    
                    if ($reviewModel->addReview($data)) {
                        flash('history_msg', 'Cảm ơn bạn đã gửi đánh giá dịch vụ!');
                    } else {
                        flash('history_msg', 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
                    }
                }
            } else {
                flash('history_msg', 'Không tìm thấy lịch hẹn hoặc lịch hẹn chưa hoàn thành.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            }
        }
        
        header('Location: ' . URLROOT . '/order/history');
        exit;
    }
}
