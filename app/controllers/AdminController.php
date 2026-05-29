<?php
class AdminController extends Controller {
    private $productModel;
    private $orderModel;
    private $activityLogModel;

    public function __construct() {
        if (!isLoggedIn() || ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'staff' && $_SESSION['user_role'] != 'doctor' && $_SESSION['user_role'] != 'cashier' && $_SESSION['user_role'] != 'manager')) {
            flash('admin_error', 'Bạn không có quyền truy cập khu vực này', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        $this->productModel = $this->model('Product');
        $this->orderModel = $this->model('Order');
        $this->activityLogModel = $this->model('ActivityLog');
    }

    private function checkAccess($allowedRoles = ['admin', 'manager']) {
        if (!in_array($_SESSION['user_role'], $allowedRoles)) {
            flash('admin_error', 'Bạn không có quyền truy cập trang này', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            
            if ($_SESSION['user_role'] == 'doctor') {
                header('Location: ' . URLROOT . '/admin/services');
            } elseif ($_SESSION['user_role'] == 'cashier') {
                header('Location: ' . URLROOT . '/admin/pos');
            } elseif ($_SESSION['user_role'] == 'manager') {
                header('Location: ' . URLROOT . '/admin/employees');
            } elseif ($_SESSION['user_role'] == 'staff') {
                header('Location: ' . URLROOT . '/admin/personal_report');
            } else {
                header('Location: ' . URLROOT . '/auth/login');
            }
            exit();
        }
    }

    public function index() {
        if ($_SESSION['user_role'] == 'doctor') {
            header('Location: ' . URLROOT . '/admin/services'); exit();
        }
        if ($_SESSION['user_role'] == 'manager') {
            header('Location: ' . URLROOT . '/admin/services'); exit();
        }
        if ($_SESSION['user_role'] == 'staff') {
            header('Location: ' . URLROOT . '/admin/personal_report'); exit();
        }
        if ($_SESSION['user_role'] == 'cashier') {
            header('Location: ' . URLROOT . '/admin/pos'); exit();
        }

        $orderModel   = $this->model('Order');
        $userModel    = $this->model('User');
        $db           = new Database;

        // --- KPI Cards ---
        // Doanh thu hôm nay
        $db->query("SELECT COALESCE(SUM(total_amount),0) as val FROM orders WHERE DATE(created_at)=CURDATE() AND status='completed'");
        $revenueToday = $db->single()->val;

        // Doanh thu tháng này
        $db->query("SELECT COALESCE(SUM(total_amount),0) as val FROM orders WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW()) AND status='completed'");
        $revenueMonth = $db->single()->val;

        // Đơn hàng hôm nay
        $db->query("SELECT COUNT(*) as val FROM orders WHERE DATE(created_at)=CURDATE()");
        $ordersToday = $db->single()->val;

        // Đơn hàng tháng này
        $db->query("SELECT COUNT(*) as val FROM orders WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())");
        $ordersMonth = $db->single()->val;

        // Khách hàng
        $db->query("SELECT COUNT(*) as val FROM users WHERE role='customer'");
        $totalCustomers = $db->single()->val;

        // Lịch hẹn hôm nay
        $db->query("SELECT COUNT(*) as val FROM appointments WHERE DATE(appointment_date)=CURDATE()");
        $appointmentsToday = $db->single()->val;

        // Tổng lịch hẹn tháng này
        $db->query("SELECT COUNT(*) as val FROM appointments WHERE MONTH(appointment_date)=MONTH(NOW()) AND YEAR(appointment_date)=YEAR(NOW())");
        $appointmentsMonth = $db->single()->val;

        // Sản phẩm sắp hết hàng (< 10)
        $db->query("SELECT COUNT(*) as val FROM products WHERE stock_quantity < 10");
        $lowStockCount = $db->single()->val;

        // --- Biểu đồ doanh thu 30 ngày ---
        $db->query("SELECT DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as cnt
                    FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND status='completed'
                    GROUP BY DATE(created_at) ORDER BY date ASC");
        $dailyRevenue = $db->resultSet();

        // --- Top 5 sản phẩm bán chạy ---
        $db->query("SELECT p.name, SUM(oi.quantity) as total_sold, SUM(oi.quantity * oi.unit_price) as revenue
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.id
                    JOIN orders o ON oi.order_id = o.id
                    WHERE o.status = 'completed'
                    GROUP BY oi.product_id, p.name
                    ORDER BY total_sold DESC LIMIT 5");
        $topProducts = $db->resultSet();

        // --- Doanh thu theo danh mục sản phẩm ---
        $db->query("SELECT c.name as category, SUM(oi.quantity * oi.unit_price) as revenue
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.id
                    JOIN categories c ON p.category_id = c.id
                    JOIN orders o ON oi.order_id = o.id
                    WHERE o.status != 'cancelled'
                    GROUP BY c.id, c.name
                    ORDER BY revenue DESC LIMIT 6");
        $categoryRevenue = $db->resultSet();

        // --- Tỷ lệ trạng thái đơn hàng ---
        $db->query("SELECT status, COUNT(*) as cnt FROM orders GROUP BY status");
        $orderStatus = $db->resultSet();

        // --- 5 đơn hàng mới nhất ---
        $db->query("SELECT o.*, u.fullname as customer_name, m.membership_level 
                    FROM orders o 
                    LEFT JOIN users u ON o.customer_id = u.id 
                    LEFT JOIN members m ON u.id = m.user_id
                    ORDER BY o.created_at DESC LIMIT 5");
        $recentOrders = $db->resultSet();

        // --- Lịch hẹn sắp tới (3 ngày) ---
        $db->query("SELECT a.*, s.name as service_name, u.fullname as customer_name
                    FROM appointments a
                    JOIN services s ON a.service_id = s.id
                    JOIN users u ON a.customer_id = u.id
                    WHERE a.appointment_date >= NOW() AND a.appointment_date <= DATE_ADD(NOW(), INTERVAL 3 DAY)
                    ORDER BY a.appointment_date ASC LIMIT 5");
        $upcomingAppointments = $db->resultSet();

        $this->view('admin/index', [
            'title'               => 'Dashboard',
            'revenue_today'       => $revenueToday,
            'revenue_month'       => $revenueMonth,
            'orders_today'        => $ordersToday,
            'orders_month'        => $ordersMonth,
            'total_customers'     => $totalCustomers,
            'appointments_today'  => $appointmentsToday,
            'appointments_month'  => $appointmentsMonth,
            'low_stock_count'     => $lowStockCount,
            'daily_revenue'       => $dailyRevenue,
            'top_products'        => $topProducts,
            'category_revenue'    => $categoryRevenue,
            'order_status'        => $orderStatus,
            'recent_orders'       => $recentOrders,
            'upcoming_appts'      => $upcomingAppointments,
        ]);
    }

    // Quản lý liên hệ
    public function contacts() {
        $this->checkAccess(['admin', 'manager']);
        $contactModel = $this->model('Contact');
        $contacts = $contactModel->getContacts();
        $this->view('admin/contacts', ['contacts' => $contacts]);
    }

    public function contact_update($id) {
        $this->checkAccess(['admin', 'manager']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'] ?? 'pending';
            $contactModel = $this->model('Contact');
            $contactModel->updateStatus($id, $status);
            
            // Nếu có nội dung reply thì gửi email phản hồi
            if ($status == 'replied' && !empty($_POST['reply_message']) && !empty($_POST['customer_email'])) {
                require_once APPROOT . '/helpers/Mailer.php';
                $mailer = new Mailer();
                $customer_email = $_POST['customer_email'];
                $mailer->sendContactReply($customer_email, $_POST['customer_name'] ?? 'Khách hàng', trim($_POST['reply_message']));
                
                // Kiểm tra xem email này có thuộc tài khoản nào trên hệ thống không
                $userModel = $this->model('User');
                $user = $userModel->getUserByEmail($customer_email);
                if ($user) {
                    $notificationModel = $this->model('Notification');
                    $notificationModel->add([
                        'user_id' => $user->id,
                        'title' => 'Phản hồi từ PETSHOP',
                        'message' => 'Chúng tôi đã trả lời yêu cầu hỗ trợ của bạn qua Email. Vui lòng kiểm tra hộp thư (' . $customer_email . ') nhé!',
                        'type' => 'system'
                    ]);
                }

                flash('contact_message', 'Đã gửi email phản hồi thành công và đánh dấu đã xử lý.', 'success');
            } else {
                flash('contact_message', 'Đã cập nhật trạng thái liên hệ thành công.', 'success');
            }

            $this->activityLogModel->log(
                $_SESSION['user_id'],
                $_SESSION['user_username'],
                $_SESSION['user_role'],
                'Xử lý liên hệ',
                "Đã đánh dấu liên hệ ID $id là: $status"
            );
            header('Location: ' . URLROOT . '/admin/contacts');
            exit;
        }
    }

    // Giao diện POS bán hàng tại quầy
    public function pos() {
        $this->checkAccess(['admin', 'cashier', 'manager']);
        $products = $this->productModel->getProducts();
        
        $appointmentModel = $this->model('Appointment');
        $appointments = $appointmentModel->getAllAppointments(['status' => 'confirmed']);
        
        // Lấy những dịch vụ có giá hoặc là dịch vụ Trông giữ
        $waiting_appointments = array_filter($appointments, function($app) {
            $is_boarding = strpos(mb_strtolower($app->category_name), 'trông giữ') !== false;
            return $is_boarding || (!empty($app->final_price) && $app->final_price > 0);
        });

        // Lấy danh sách khách hàng thành viên để tìm kiếm tại POS
        $customers = $this->model('User')->getUsersByRole('customer');

        // Lấy danh sách ưu đãi hội viên
        $db = new Database();
        $db->query("SELECT * FROM membership_benefits");
        $benefits = $db->resultSet();
        
        // Lấy danh mục dịch vụ để đặt trực tiếp
        $serviceModel = $this->model('Service');
        $services = $serviceModel->getServices();
        
        // Lấy danh sách bác sĩ và nhân viên để gán lịch
        $userModel = $this->model('User');
        $doctors = $userModel->getUsersByRole('doctor');
        $staffs = $userModel->getUsersByRole('staff');
        $assignees = array_merge($doctors, $staffs);
        $staffSchedules = $appointmentModel->getStaffSchedules();
        $productCategories = $this->productModel->getProductCategories();
        
        $data = [
            'products' => $products,
            'appointments' => array_values($waiting_appointments),
            'customers' => $customers,
            'benefits' => $benefits,
            'services' => $services,
            'assignees' => $assignees,
            'staff_schedules' => $staffSchedules,
            'product_categories' => $productCategories
        ];

        $this->view('admin/pos', $data);
    }

    // API lấy danh sách dịch vụ chờ thanh toán mới nhất cho POS
    public function pos_waiting_appointments() {
        if (!isset($_SESSION['user_id'])) exit;
        
        $appointmentModel = $this->model('Appointment');
        $appointments = $appointmentModel->getAllAppointments(['status' => 'confirmed']);
        
        $waiting_appointments = array_filter($appointments, function($app) {
            $is_boarding = strpos(mb_strtolower($app->category_name), 'trông giữ') !== false;
            return $is_boarding || (!empty($app->final_price) && $app->final_price > 0);
        });

        header('Content-Type: application/json');
        echo json_encode(array_values($waiting_appointments));
        exit;
    }

    // API lấy danh sách lịch bận của nhân viên mới nhất cho POS
    public function pos_staff_schedules() {
        if (!isset($_SESSION['user_id'])) exit;
        
        $appointmentModel = $this->model('Appointment');
        $staffSchedules = $appointmentModel->getStaffSchedules();
        
        header('Content-Type: application/json');
        echo json_encode($staffSchedules);
        exit;
    }

    // API Server-Sent Events (SSE) đồng bộ lịch hẹn và lịch nhân sự thời gian thực cho POS
    public function pos_sse() {
        if (!isset($_SESSION['user_id'])) exit;
        
        // Ngăn chặn PHP giữ session lock để không chặn các request khác
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Tắt buffer cho Nginx/Apache
        
        $appointmentModel = $this->model('Appointment');
        $lastHash = '';
        
        while (true) {
            $appointments = $appointmentModel->getAllAppointments(['status' => 'confirmed']);
            $waiting_appointments = array_filter($appointments, function($app) {
                $is_boarding = strpos(mb_strtolower($app->category_name), 'trông giữ') !== false;
                return $is_boarding || (!empty($app->final_price) && $app->final_price > 0);
            });
            $waiting_appointments = array_values($waiting_appointments);
            
            $staffSchedules = $appointmentModel->getStaffSchedules();
            
            $currentData = [
                'appointments' => $waiting_appointments,
                'staff_schedules' => $staffSchedules
            ];
            $currentHash = md5(json_encode($currentData));
            
            if ($currentHash !== $lastHash) {
                echo "data: " . json_encode($currentData) . "\n\n";
                ob_flush();
                flush();
                $lastHash = $currentHash;
            }
            
            if (connection_aborted()) {
                break;
            }
            
            sleep(2);
        }
        exit;
    }

    // API Lấy lịch sử tư vấn sức khỏe AI của khách hàng phục vụ POS tư vấn bán hàng
    public function pos_customer_ai_history() {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $phone = isset($_GET['phone']) ? trim($_GET['phone']) : '';
        $name = isset($_GET['name']) ? trim($_GET['name']) : '';
        
        $aiAnalysisModel = $this->model('AiAnalysis');
        $history = $aiAnalysisModel->getHistoryByPhoneOrName($phone, $name);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'history' => $history]);
        exit;
    }

    // Trang hiển thị Nhật ký hành vi dành cho Admin và Manager
    public function activity_logs() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'manager'])) {
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }
        
        $activityLogModel = $this->model('ActivityLog');
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        if (!empty($search)) {
            $logs = $activityLogModel->searchLogs($search);
        } else {
            $logs = $activityLogModel->getLogs();
        }
        
        $data = [
            'logs' => $logs,
            'search' => $search
        ];
        
        $this->view('admin/activity_logs', $data);
    }

    // API Đặt dịch vụ trực tiếp từ POS
    public function pos_book_service() {
        if (!isset($_SESSION['user_id'])) exit;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            
            $customer_id = !empty($data['customer_id']) ? $data['customer_id'] : null;
            $customer_phone = !empty($data['customer_phone']) ? $data['customer_phone'] : null;

            $db = new Database;

            // Truy tìm xem có phải hội viên không nếu nhập SĐT
            if (empty($customer_id) && !empty($customer_phone)) {
                $db->query("SELECT u.id, u.fullname FROM users u 
                            LEFT JOIN members m ON u.id = m.user_id 
                            WHERE u.role = 'customer' AND m.phone = :phone 
                            LIMIT 1");
                $db->bind(':phone', $customer_phone);
                $member = $db->single();
                if ($member) {
                    $customer_id = $member->id;
                    if (empty($data['customer_name'])) {
                        $data['customer_name'] = $member->fullname;
                    }
                }
            }

            $db->query('INSERT INTO appointments (customer_id, service_id, pet_id, appointment_date, appointment_time, notes, duration_value, duration_unit, status, doctor_id, final_price, customer_name, customer_phone) 
                        VALUES (:customer_id, :service_id, NULL, :appointment_date, :appointment_time, :notes, :duration_value, :duration_unit, :status, :doctor_id, :final_price, :customer_name, :customer_phone)');
            
            $db->bind(':customer_id', $customer_id);
            $db->bind(':service_id', $data['service_id']);
            $db->bind(':appointment_date', $data['appointment_date']);
            $db->bind(':appointment_time', $data['appointment_time']);
            $db->bind(':notes', 'Đặt trực tiếp tại POS');
            $db->bind(':duration_value', $data['duration_value'] ?? 1);
            $db->bind(':duration_unit', $data['duration_unit'] ?? 'none');
            $db->bind(':status', 'confirmed'); // Đã xác nhận luôn
            $db->bind(':doctor_id', $data['doctor_id']);
            $db->bind(':customer_name', $data['customer_name']);
            $db->bind(':customer_phone', $data['customer_phone']);

            // Để final_price là NULL, chờ bác sĩ khám/làm xong mới báo giá (xác nhận hoàn thành)
            $db->bind(':final_price', null);

            if ($db->execute()) {
                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Đặt lịch hẹn POS',
                    "Đặt dịch vụ ID " . $data['service_id'] . " cho khách: " . $data['customer_name'] . " (Bác sĩ ID: " . $data['doctor_id'] . ")"
                );
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
        }
    }

    public function products() {
        $this->checkAccess(['admin', 'manager']);
        $products = $this->productModel->getProducts();
        $this->view('admin/products', ['products' => $products]);
    }

    public function product_add() {
        $this->checkAccess(['admin', 'manager']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], APPROOT . '/../public/images/' . $image);
            }

            $data = [
                'category_id' => trim($_POST['category_id']),
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'stock_quantity' => trim($_POST['stock_quantity']),
                'image' => $image
            ];

            if ($this->productModel->addProduct($data)) {
                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Thêm sản phẩm',
                    "Đã thêm sản phẩm mới: " . $data['name'] . " (Giá: " . $data['price'] . "đ)"
                );
                header('Location: ' . URLROOT . '/admin/products');
            } else {
                die('Có lỗi xảy ra.');
            }
        } else {
            $categories = $this->productModel->getProductCategories();
            $this->view('admin/product_form', ['categories' => $categories]);
        }
    }

    public function product_edit($id) {
        $this->checkAccess(['admin', 'manager']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], APPROOT . '/../public/images/' . $image);
            }

            $data = [
                'id' => $id,
                'category_id' => trim($_POST['category_id']),
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'stock_quantity' => trim($_POST['stock_quantity']),
                'image' => $image
            ];

            if ($this->productModel->updateProduct($data)) {
                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Sửa sản phẩm',
                    "Đã sửa sản phẩm ID " . $id . ": " . $data['name']
                );
                header('Location: ' . URLROOT . '/admin/products');
            } else {
                die('Có lỗi xảy ra.');
            }
        } else {
            $product = $this->productModel->getProductById($id);
            $categories = $this->productModel->getProductCategories();
            $this->view('admin/product_form', ['product' => $product, 'categories' => $categories]);
        }
    }

    public function product_delete($id) {
        $this->checkAccess(['admin', 'manager']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->productModel->deleteProduct($id)) {
                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Xóa sản phẩm',
                    "Đã xóa sản phẩm ID: " . $id
                );
                header('Location: ' . URLROOT . '/admin/products');
            } else {
                die('Lỗi khi xóa.');
            }
        }
    }

    public function orders() {
        $this->checkAccess();
        $orderModel = $this->model('Order');

        // Lazy Cron: Tự động hủy đơn hàng chuyển khoản chờ xử lý quá 24 giờ
        $db = new Database();
        $db->query("SELECT id FROM orders WHERE payment_method = 'transfer' AND status = 'pending' AND created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        $expired_orders = $db->resultSet();
        
        if (count($expired_orders) > 0) {
            $notificationModel = $this->model('Notification');
            $productModel = $this->model('Product');

            foreach ($expired_orders as $o) {
                // Hủy đơn
                $orderModel->updateStatusWithReason($o->id, 'cancelled', 'Đơn hàng tự động hủy do quá hạn thanh toán (24 giờ).');
                
                // Gửi thông báo
                $db->query("SELECT customer_id FROM orders WHERE id = :id");
                $db->bind(':id', $o->id);
                $cust = $db->single();
                if ($cust && $cust->customer_id) {
                    $notificationModel->add([
                        'user_id' => $cust->customer_id,
                        'title' => 'Đơn hàng bị hủy do quá hạn',
                        'message' => "Đơn hàng #ORD-" . str_pad($o->id, 5, '0', STR_PAD_LEFT) . " của bạn đã tự động bị hủy do không nhận được thanh toán trong vòng 24 giờ.",
                        'type' => 'order'
                    ]);
                }
                
                // Hoàn lại kho
                $items = $orderModel->getOrderItems($o->id);
                foreach ($items as $item) {
                    // Tăng lại số lượng
                    $db->query("UPDATE products SET stock_quantity = stock_quantity + :qty WHERE id = :id");
                    $db->bind(':qty', $item->quantity);
                    $db->bind(':id', $item->product_id);
                    $db->execute();
                }
            }
        }

        // Collect filters
        $filters = [
            'type'   => isset($_GET['type'])   ? $_GET['type']   : 'all',
            'status' => isset($_GET['status']) ? $_GET['status'] : 'all',
            'date'   => isset($_GET['date'])   && !empty($_GET['date'])  ? $_GET['date']  : '',
            'month'  => isset($_GET['month'])  && !empty($_GET['month']) ? $_GET['month'] : '',
            'year'   => isset($_GET['year'])   && !empty($_GET['year'])  ? $_GET['year']  : '',
        ];

        $orders = $orderModel->getOrdersFiltered($filters);

        // Lấy chi tiết sản phẩm cho từng đơn hàng
        foreach ($orders as $order) {
            $order->items = $orderModel->getOrderItems($order->id);
        }

        // Thống kê tóm tắt
        $statsFilters = array_filter(['month' => $filters['month'], 'year' => $filters['year']]);
        $revenueStats = $orderModel->getRevenueStats($statsFilters);

        // Summary totals
        $totalRevenue = array_sum(array_column((array)$revenueStats, 'revenue'));
        $totalOrders  = count($orders);

        $this->view('admin/orders', [
            'orders'       => $orders,
            'filters'      => $filters,
            'current_type' => $filters['type'],
            'revenue_stats'=> $revenueStats,
            'total_revenue'=> $totalRevenue,
            'total_orders' => $totalOrders,
        ]);
    }

    public function export_orders() {
        $this->checkAccess();
        $orderModel = $this->model('Order');
        $orders = $orderModel->getAllOrders();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=petshop_orders_' . date('Ymd') . '.csv');
        
        $output = fopen('php://output', 'w');
        // Thêm BOM cho tiếng Việt (Excel)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Mã đơn', 'Khách hàng', 'Tổng tiền', 'Trạng thái', 'Loại', 'Ngày đặt']);
        
        foreach ($orders as $order) {
            fputcsv($output, [
                '#ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                $order->customer_name ?? 'Khách lẻ',
                $order->total_amount,
                $order->status,
                $order->order_type == 'online' ? 'Trực tuyến' : 'Tại quầy (POS)',
                $order->created_at
            ]);
        }
        fclose($output);
        exit();
    }

    public function order_status($id) {
        $this->checkAccess();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];
            $reason = $_POST['cancel_reason'] ?? '';
            $paid_amount = isset($_POST['paid_amount']) ? (float)$_POST['paid_amount'] : null;
            $admin_note = $_POST['admin_note'] ?? '';

            $orderModel = $this->model('Order');
            $notificationModel = $this->model('Notification');
            
            $success = false;
            if ($status == 'cancelled' && !empty($reason)) {
                $success = $orderModel->updateStatusWithReason($id, $status, $reason);
            } elseif ($status == 'shipping' && $paid_amount !== null) {
                $success = $orderModel->approveOrder($id, $paid_amount, $admin_note);
            } else {
                $success = $orderModel->updateStatus($id, $status);
            }

            if ($success) {
                // Gửi thông báo cho khách hàng
                $db_temp = new Database();
                $db_temp->query("SELECT customer_id, payment_method FROM orders WHERE id = :id");
                $db_temp->bind(':id', $id);
                $o = $db_temp->single();

                if ($o && $o->customer_id) {
                    $statusText = [
                        'pending' => 'đang xử lý',
                        'shipping' => 'đang giao hàng',
                        'completed' => 'đã hoàn thành',
                        'cancelled' => 'đã bị hủy'
                    ];

                    // Chỉ thông báo các trạng thái quan trọng: hủy, đang giao, hoàn thành
                    if (in_array($status, ['shipping', 'completed', 'cancelled'])) {
                        if ($status == 'shipping') {
                            $msg = "Đơn hàng #ORD-" . str_pad($id, 5, '0', STR_PAD_LEFT) . " đã được xác nhận. Đặt hàng thành công! Kiện hàng đang trên đường giao đến bạn.";
                        } else {
                            $msg = "Đơn hàng #ORD-" . str_pad($id, 5, '0', STR_PAD_LEFT) . " của bạn hiện " . ($statusText[$status] ?? $status) . ".";
                        }
                        if ($status == 'cancelled' && !empty($reason)) {
                            $msg .= " Lý do: " . $reason;
                            if ($o->payment_method == 'transfer') {
                                $msg .= "\nDo bạn thanh toán qua chuyển khoản, vui lòng liên hệ Zalo/Hotline hoặc phản hồi email cung cấp STK để chúng tôi hoàn tiền lại cho bạn.";
                            }
                        }

                        $notificationModel->add([
                            'user_id' => $o->customer_id,
                            'title' => 'Cập nhật đơn hàng',
                            'message' => $msg,
                            'type' => 'order'
                        ]);
                    }

                    // Nếu hoàn thành đơn hàng -> Trừ kho và Cập nhật hạng hội viên
                    if ($status == 'completed') {
                        // Trừ kho hàng
                        $items = $orderModel->getOrderItems($id);
                        $productModel = $this->model('Product');
                        foreach ($items as $item) {
                            $productModel->decreaseStock($item->product_id, $item->quantity);
                        }

                        $userModel = $this->model('User');
                        $userModel->updateMembershipTier($o->customer_id);
                    }
                }

                header('Location: ' . URLROOT . '/admin/orders');
            } else {
                die('Lỗi khi cập nhật trạng thái.');
            }
        }
    }

    public function complete_refund($order_id) {
        $this->checkAccess();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database();
            $db->query("UPDATE orders SET refund_status = 'completed' WHERE id = :id");
            $db->bind(':id', $order_id);
            if($db->execute()) {
                // Gửi thông báo cho khách
                $db->query("SELECT customer_id FROM orders WHERE id = :id");
                $db->bind(':id', $order_id);
                $order = $db->single();
                if($order && $order->customer_id) {
                    $notificationModel = $this->model('Notification');
                    $notificationModel->add([
                        'user_id' => $order->customer_id,
                        'title' => 'Đã hoàn tiền',
                        'message' => "Yêu cầu hoàn tiền cho đơn hàng #ORD-" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " đã được xử lý. Vui lòng kiểm tra tài khoản ngân hàng của bạn.",
                        'type' => 'order'
                    ]);
                }
                header('Location: ' . URLROOT . '/admin/orders');
            } else {
                die('Lỗi xử lý hoàn tiền.');
            }
        }
    }

    public function services() {
        $this->checkAccess(['admin', 'doctor', 'manager']);
        $appointmentModel = $this->model('Appointment');

        if ($_SESSION['user_role'] == 'doctor') {
            $doctor_user_id = $_SESSION['user_id'];
            $appointments = $appointmentModel->getAppointmentsForDoctor($doctor_user_id);
            $completedAppts = $appointmentModel->getCompletedByDoctor($doctor_user_id);

            // Xây dựng danh sách khung giờ bận của bác sĩ (đã nhận confirmed)
            $busySlots = [];
            $totalWorkingMinutesToday = 0;
            $todayStr = date('Y-m-d');
            
            // Hàm lấy duration_minutes từ service_id
            $db = new Database;

            foreach ($appointments as $app) {
                if (!empty($app->doctor_id) && $app->doctor_id == $doctor_user_id) {
                    $busySlots[] = $app->appointment_date . '_' . $app->appointment_time;
                    if ($app->appointment_date == $todayStr) {
                        $db->query('SELECT duration_minutes FROM services WHERE id = :id');
                        $db->bind(':id', $app->service_id);
                        $svc = $db->single();
                        $totalWorkingMinutesToday += ($svc && $svc->duration_minutes) ? $svc->duration_minutes : 30;
                    }
                }
            }
            foreach ($completedAppts as $app) {
                $busySlots[] = $app->appointment_date . '_' . $app->appointment_time;
                if ($app->appointment_date == $todayStr) {
                    $db->query('SELECT duration_minutes FROM services WHERE id = :id');
                    $db->bind(':id', $app->service_id);
                    $svc = $db->single();
                    $totalWorkingMinutesToday += ($svc && $svc->duration_minutes) ? $svc->duration_minutes : 30;
                }
            }

            // Giả sử một ngày làm việc 8 tiếng = 480 phút
            $maxWorkingMinutes = 480;
            $freeMinutesToday = $maxWorkingMinutes - $totalWorkingMinutesToday;
            if ($freeMinutesToday < 0) $freeMinutesToday = 0;

            $this->view('admin/services', [
                'appointments' => $appointments,
                'completed_appointments' => $completedAppts,
                'is_doctor_view' => true,
                'busy_slots' => $busySlots,
                'work_minutes' => $totalWorkingMinutesToday,
                'free_minutes' => $freeMinutesToday
            ]);
        } else {
            $filters = [];
            if ($_SESSION['user_role'] == 'cashier') {
                $filters['status'] = 'confirmed';
            }
            $appointments = $appointmentModel->getAllAppointments($filters);

            // Lấy danh sách bác sĩ cho quản lý xem trạng thái rảnh/bận
            $doctors = [];
            $staffSchedules = [];
            if ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'manager' || $_SESSION['user_role'] == 'staff') {
                $userModel = $this->model('User');
                $doctors = $userModel->getUsersByRole('doctor');
                $staffSchedules = $appointmentModel->getStaffSchedules();
            }

            $this->view('admin/services', [
                'appointments' => $appointments,
                'completed_appointments' => [],
                'is_doctor_view' => false,
                'doctors' => $doctors,
                'staff_schedules' => $staffSchedules
            ]);
        }
    }

    // ---------- QUẢN LÝ DỊCH VỤ (CRUD) ----------

    public function service_list() {
        $this->checkAccess();
        $services = $this->model('Service')->getServices();
        $this->view('admin/service_list', ['services' => $services]);
    }

    public function service_add() {
        $this->checkAccess();
        $serviceModel = $this->model('Service');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], APPROOT . '/../public/images/' . $image);
            }

            $data = [
                'name' => trim($_POST['name']),
                'category_id' => trim($_POST['category_id']),
                'price' => trim($_POST['price'] ?? 0),
                'duration_minutes' => trim($_POST['duration_minutes'] ?? 30),
                'description' => trim($_POST['description']),
                'image' => $image
            ];
            
            if ($serviceModel->addService($data)) {
                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Thêm dịch vụ',
                    "Đã thêm dịch vụ mới: " . $data['name'] . " (Giá: " . $data['price'] . "đ)"
                );
                header('Location: ' . URLROOT . '/admin/service_list');
            } else {
                die('Có lỗi xảy ra.');
            }
        } else {
            $categories = $serviceModel->getServiceCategories();
            $this->view('admin/service_form', ['categories' => $categories]);
        }
    }

    public function service_edit($id) {
        $this->checkAccess();
        $serviceModel = $this->model('Service');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], APPROOT . '/../public/images/' . $image);
            }

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'category_id' => trim($_POST['category_id']),
                'price' => trim($_POST['price'] ?? 0),
                'duration_minutes' => trim($_POST['duration_minutes'] ?? 30),
                'description' => trim($_POST['description']),
                'image' => $image
            ];
            
            if ($serviceModel->updateService($data)) {
                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Sửa dịch vụ',
                    "Đã sửa dịch vụ ID " . $id . ": " . $data['name']
                );
                header('Location: ' . URLROOT . '/admin/service_list');
            } else {
                die('Có lỗi xảy ra.');
            }
        } else {
            $service = $serviceModel->getServiceById($id);
            $categories = $serviceModel->getServiceCategories();
            $this->view('admin/service_form', ['service' => $service, 'categories' => $categories]);
        }
    }

    public function service_delete($id) {
        $this->checkAccess();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $serviceModel = $this->model('Service');
            if ($serviceModel->deleteService($id)) {
                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Xóa dịch vụ',
                    "Đã xóa dịch vụ ID: " . $id
                );
                header('Location: ' . URLROOT . '/admin/service_list');
            } else {
                die('Lỗi khi xóa.');
            }
        }
    }

    public function customers() {
        $this->checkAccess();
        $customers = $this->model('User')->getCustomersWithTotalSpent();
        $this->view('admin/customers', ['customers' => $customers]);
    }

    public function customer_delete($id) {
        $this->checkAccess();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('User')->deleteUser($id)) {
                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Xóa khách hàng',
                    "Đã xóa tài khoản khách hàng ID: " . $id
                );
                header('Location: ' . URLROOT . '/admin/customers');
            } else {
                die('Lỗi khi xóa khách hàng.');
            }
        }
    }

    // Quản lý nhân viên
    public function employees() {
        $this->checkAccess(['admin', 'manager']);
        $employeeModel = $this->model('Employee');
        $employees = $employeeModel->getEmployees();
        $this->view('admin/employees', ['employees' => $employees]);
    }

    public function employee_add() {
        $this->checkAccess(['admin', 'manager']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $userModel = $this->model('User');
            $employeeModel = $this->model('Employee');

            // 1. Kiểm tra email tồn tại
            if ($userModel->findUserByEmail($_POST['email'])) {
                die('Email đã tồn tại trên hệ thống.');
            }

            // 2. Xử lý ảnh nhân viên
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], APPROOT . '/../public/images/employees/' . $image);
            }

            // 3. Tạo tài khoản User
            $role = trim($_POST['role']);
            if ($role == 'manager' && $_SESSION['user_role'] != 'admin') {
                die('Bạn không có quyền tạo tài khoản Quản lý.');
            }
            if ($role == 'admin' && $_SESSION['user_role'] != 'admin') {
                die('Bạn không có quyền tạo tài khoản Admin.');
            }

            $userData = [
                'fullname' => trim($_POST['fullname']),
                'email' => trim($_POST['email']),
                'password' => password_hash(trim($_POST['password']), PASSWORD_DEFAULT),
                'role' => $role
            ];

            $userId = $userModel->register($userData);

            if ($userId) {
                // 4. Lưu thông tin nhân viên
                $employeeData = [
                    'user_id' => $userId,
                    'employee_code' => trim($_POST['employee_code']),
                    'fullname' => trim($_POST['fullname']),
                    'cccd' => trim($_POST['cccd']),
                    'address' => trim($_POST['address']),
                    'image' => $image
                ];

                if ($employeeModel->addEmployee($employeeData)) {
                    $this->activityLogModel->log(
                        $_SESSION['user_id'],
                        $_SESSION['user_username'],
                        $_SESSION['user_role'],
                        'Thêm nhân viên',
                        "Đã thêm nhân viên mới: " . $employeeData['fullname'] . " (Mã: " . $employeeData['employee_code'] . ", Chức vụ: " . $role . ")"
                    );
                    header('Location: ' . URLROOT . '/admin/employees');
                } else {
                    die('Lỗi khi lưu thông tin nhân viên.');
                }
            } else {
                die('Lỗi khi tạo tài khoản.');
            }
        } else {
            $this->view('admin/employee_form', []);
        }
    }

    public function employee_delete($id) {
        $this->checkAccess(['admin', 'manager']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $employeeModel = $this->model('Employee');
            if ($employeeModel->deleteEmployee($id)) {
                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Xóa nhân viên',
                    "Đã xóa nhân viên ID: " . $id
                );
                header('Location: ' . URLROOT . '/admin/employees');
            } else {
                die('Lỗi khi xóa nhân viên.');
            }
        }
    }

    public function appointment_assign() {
        $this->checkAccess(['admin', 'manager']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $appointment_id = $_POST['appointment_id'];
            $doctor_id = $_POST['doctor_id'];

            $appointmentModel = $this->model('Appointment');
            if ($appointmentModel->assignDoctor($appointment_id, $doctor_id)) {
                
                // Nếu người được phân công là nhân viên chăm sóc (staff) 
                // thì cập nhật giá dịch vụ vào final_price luôn để chuyển sang Chờ thanh toán
                $db = new Database;
                $db->query('SELECT role FROM users WHERE id = :id');
                $db->bind(':id', $doctor_id);
                $user = $db->single();
                
                if ($user && $user->role == 'staff') {
                    $db->query('SELECT s.price FROM appointments a JOIN services s ON a.service_id = s.id WHERE a.id = :id');
                    $db->bind(':id', $appointment_id);
                    $service = $db->single();
                    if ($service && $service->price > 0) {
                        $appointmentModel->updateFinalPrice($appointment_id, $service->price);
                    } elseif ($service && $service->price == 0) {
                        $appointmentModel->updateFinalPrice($appointment_id, 0);
                    }
                }

                // Gửi thông báo cho khách hàng
                $db->query('SELECT customer_id FROM appointments WHERE id = :id');
                $db->bind(':id', $appointment_id);
                $appt = $db->single();
                if ($appt && $appt->customer_id) {
                    $notificationModel = $this->model('Notification');
                    $notificationModel->add([
                        'user_id' => $appt->customer_id,
                        'title' => 'Dịch vụ đã được xác nhận',
                        'message' => 'Lịch hẹn #' . str_pad($appointment_id, 5, '0', STR_PAD_LEFT) . ' của bạn đã được quản lý xác nhận và xếp nhân sự. Đặt dịch vụ thành công!',
                        'type' => 'appointment'
                    ]);
                }

                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Phân công bác sĩ',
                    "Đã xếp lịch hẹn #" . $appointment_id . " cho bác sĩ/nhân viên ID: " . $doctor_id
                );

                header('Location: ' . URLROOT . '/admin/services');
            } else {
                die('Lỗi khi phân công bác sĩ.');
            }
        }
    }

    public function get_available_staff() {
        $this->checkAccess(['admin', 'manager']);
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $date = $_GET['date'];
            $time = $_GET['time'];
            $role = $_GET['role'] ?? 'doctor'; // Mặc định là doctor nếu không có

            $appointmentModel = $this->model('Appointment');
            $staffs = $appointmentModel->getAvailableUsersByRole($date, $time, $role);
            
            echo json_encode($staffs);
            exit;
        }
    }

    // AJAX: Thêm nhanh danh mục
    public function add_category_ajax() {
        $this->checkAccess();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize
            $name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
            $type = isset($_POST['type']) ? trim(strip_tags($_POST['type'])) : 'service';
            $description = isset($_POST['description']) ? trim(strip_tags($_POST['description'])) : '';

            if (empty($name)) {
                echo json_encode(['success' => false, 'message' => 'Tên danh mục không được để trống']);
                exit;
            }

            $categoryModel = $this->model('Category');
            $data = [
                'name' => $name,
                'type' => $type,
                'description' => $description
            ];

            $newId = $categoryModel->addCategory($data);
            if ($newId) {
                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Thêm danh mục',
                    "Đã thêm danh mục mới qua AJAX: " . $name . " (Loại: " . $type . ")"
                );
                echo json_encode([
                    'success' => true, 
                    'id' => $newId, 
                    'name' => $name
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu vào CSDL']);
            }
            exit;
        }
    }

    public function appointment_take($id) {
        if ($_SESSION['user_role'] != 'doctor') {
            die('Chỉ bác sĩ mới có quyền nhận dịch vụ.');
        }

        $appointmentModel = $this->model('Appointment');
        $appointment = $appointmentModel->getAppointmentById($id);

        if (!$appointment || $appointment->status != 'pending' || !empty($appointment->doctor_id)) {
            flash('admin_error', 'Lịch hẹn này đã được bác sĩ khác nhận hoặc đã được quản lý phân công.');
            header('Location: ' . URLROOT . '/admin/services');
            return;
        }

        // Kiểm tra bác sĩ có rảnh vào khung giờ này không
        if (!$appointmentModel->checkDoctorAvailability($_SESSION['user_id'], $appointment->appointment_date, $appointment->appointment_time)) {
            flash('admin_error', 'Bạn đã có lịch hẹn khác vào khung giờ này. Không thể nhận thêm.');
            header('Location: ' . URLROOT . '/admin/services');
            return;
        }

        if ($appointmentModel->assignDoctor($id, $_SESSION['user_id'])) {
            $this->activityLogModel->log(
                $_SESSION['user_id'],
                $_SESSION['user_username'],
                $_SESSION['user_role'],
                'Nhận ca khám',
                "Bác sĩ đã tự nhận ca khám/lịch hẹn #" . $id
            );
            flash('service_success', 'Bạn đã nhận lịch hẹn #' . str_pad($id, 5, '0', STR_PAD_LEFT) . ' thành công! Trạng thái chuyển sang Đã xác nhận.');
            header('Location: ' . URLROOT . '/admin/services');
        } else {
            die('Lỗi khi nhận dịch vụ.');
        }
    }

    public function appointment_set_price() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_SESSION['user_role'] != 'doctor') {
                die('Bạn không có quyền thực hiện thao tác này.');
            }

            $id = $_POST['appointment_id'];
            $price = $_POST['final_price'];

            $appointmentModel = $this->model('Appointment');
            
            if ($appointmentModel->updateFinalPrice($id, $price)) {
                $this->activityLogModel->log(
                    $_SESSION['user_id'],
                    $_SESSION['user_username'],
                    $_SESSION['user_role'],
                    'Báo giá ca khám',
                    "Bác sĩ đã báo giá ca khám #" . $id . ": " . $price . "đ"
                );
                flash('service_success', 'Đã gửi báo giá thành công. Yêu cầu đang chờ thu ngân thanh toán.');
                header('Location: ' . URLROOT . '/admin/services');
            } else {
                die('Lỗi khi cập nhật thành tiền.');
            }
        }
    }

    public function appointment_pay($id) {
        if ($_SESSION['user_role'] != 'cashier' && $_SESSION['user_role'] != 'admin') {
            die('Bạn không có quyền thực hiện thanh toán.');
        }

        $appointmentModel = $this->model('Appointment');
        if ($appointmentModel->updateStatus($id, 'completed')) {
            
            // Gửi thông báo hoàn thành dịch vụ
            $appt = $appointmentModel->getAppointmentById($id);
            if ($appt && $appt->customer_id) {
                $notificationModel = $this->model('Notification');
                $notificationModel->add([
                    'user_id' => $appt->customer_id,
                    'title' => 'Dịch vụ hoàn tất',
                    'message' => "Thú cưng của bạn đã hoàn thành dịch vụ: " . $appt->service_name . ". Bạn có thể đến đón bé rồi nhé!",
                    'type' => 'service'
                ]);
            }

            $this->activityLogModel->log(
                $_SESSION['user_id'],
                $_SESSION['user_username'],
                $_SESSION['user_role'],
                'Thanh toán lịch hẹn',
                "Thu ngân xác nhận thanh toán & hoàn thành dịch vụ #" . $id
            );

            flash('service_success', 'Xác nhận thanh toán và hoàn thành dịch vụ thành công');
            header('Location: ' . URLROOT . '/admin/services');
        } else {
            die('Lỗi khi cập nhật trạng thái thanh toán.');
        }
    }

    // Quản lý trông giữ thú cưng
    public function boarding() {
        $this->checkAccess(['admin', 'cashier', 'manager']);
        $appointmentModel = $this->model('Appointment');
        $allAppts = $appointmentModel->getAllAppointments();
        
        // Lọc các dịch vụ thuộc loại Trông giữ
        $boardingAppts = array_filter($allAppts, function($app) {
            return strpos(mb_strtolower($app->category_name), 'trông giữ') !== false;
        });

        $this->view('admin/boarding', [
            'boarding_appts' => $boardingAppts
        ]);
    }

    public function payment_history() {
        $this->checkAccess(['admin', 'manager', 'cashier']);
        
        $orderModel = $this->model('Order');
        $appointmentModel = $this->model('Appointment');

        $completedOrders = $orderModel->getCompletedOrders();
        
        // Đính kèm danh sách sản phẩm vào từng đơn hàng
        foreach ($completedOrders as $order) {
            $order->items = $orderModel->getOrderItems($order->id);
        }

        $completedAppointments = $appointmentModel->getAllCompletedAppointments();

        $this->view('admin/payment_history', [
            'orders' => $completedOrders,
            'appointments' => $completedAppointments
        ]);
    }

    // ---------- QUẢN LÝ KHO HÀNG ----------

    public function inventory() {
        $this->checkAccess(['admin', 'manager']);
        $products = $this->productModel->getProducts();
        
        // Group products by category
        $groupedProducts = [];
        foreach ($products as $p) {
            $catName = $p->category_name ?? 'Chưa phân loại';
            if (!isset($groupedProducts[$catName])) {
                $groupedProducts[$catName] = [];
            }
            $groupedProducts[$catName][] = $p;
        }

        $this->view('admin/inventory', [
            'groupedProducts' => $groupedProducts
        ]);
    }

    public function inventory_update() {
        $this->checkAccess(['admin', 'manager']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach ($_POST['stock'] as $productId => $quantity) {
                $this->productModel->updateStock($productId, $quantity);
            }
            $this->activityLogModel->log(
                $_SESSION['user_id'],
                $_SESSION['user_username'],
                $_SESSION['user_role'],
                'Cập nhật tồn kho',
                "Đã cập nhật số lượng tồn kho cho các mặt hàng"
            );
            flash('inventory_success', 'Đã cập nhật số lượng tồn kho thành công');
            header('Location: ' . URLROOT . '/admin/inventory');
        }
    }

    // ---------- QUẢN LÝ CHẤM CÔNG ----------

    public function attendance() {
        $this->checkAccess(['admin', 'manager']);
        $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
        
        $attendanceModel = $this->model('Attendance');
        $employees = $attendanceModel->getAttendanceByDate($date);
        
        $this->view('admin/attendance', [
            'employees' => $employees,
            'current_date' => $date
        ]);
    }

    public function attendance_history() {
        $this->checkAccess(['admin', 'manager']);
        
        $employeeModel = $this->model('Employee');
        $attendanceModel = $this->model('Attendance');
        
        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');
        
        // Calculate days in month (compatible version)
        $daysInMonth = date('t', strtotime("$year-$month-01"));
        $dates = [];
        for($i = 1; $i <= $daysInMonth; $i++) {
            $dates[] = sprintf('%04d-%02d-%02d', $year, $month, $i);
        }
        
        $employees = $employeeModel->getEmployees();
        if ($_SESSION['user_role'] == 'staff') {
            // Filter to only show the logged-in staff member
            $employees = array_filter($employees, function($emp) {
                return $emp->user_id == $_SESSION['user_id'];
            });
        }
        $historyData = $attendanceModel->getAttendanceHistory([
            'start_date' => $dates[0],
            'end_date' => $dates[count($dates)-1]
        ]);
        
        // Reorganize data into matrix: [user_id][date] = status
        $matrix = [];
        foreach($historyData as $record) {
            $matrix[$record->user_id][$record->date] = $record->status;
        }
        
        $this->view('admin/attendance_history', [
            'employees' => $employees,
            'dates' => $dates,
            'matrix' => $matrix,
            'current_month' => $month,
            'current_year' => $year
        ]);
    }

    public function attendance_save() {
        $this->checkAccess(['admin', 'manager']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $attendanceModel = $this->model('Attendance');
            $date = $_POST['date'];
            
            foreach ($_POST['status'] as $userId => $status) {
                $data = [
                    'user_id' => $userId,
                    'date' => $date,
                    'status' => $status,
                    'notes' => $_POST['notes'][$userId] ?? ''
                ];
                $attendanceModel->saveAttendance($data);
            }
            
            $this->activityLogModel->log(
                $_SESSION['user_id'],
                $_SESSION['user_username'],
                $_SESSION['user_role'],
                'Chấm công',
                "Đã lưu bảng chấm công ngày: " . $date
            );
            flash('attendance_success', 'Đã lưu thông tin chấm công ngày ' . $date);
            header('Location: ' . URLROOT . '/admin/attendance?date=' . $date);
        }
    }

    // ---------- QUẢN LÝ LƯƠNG ----------

    public function payroll() {
        $this->checkAccess(['admin', 'manager']);
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        
        $payrollModel = $this->model('Payroll');
        $payrolls = $payrollModel->getPayrolls($month, $year);

        if ($_SESSION['user_role'] == 'staff') {
            // Filter to only show the logged-in staff member's payroll
            $payrolls = array_filter($payrolls, function($p) {
                return $p->user_id == $_SESSION['user_id'];
            });
        }
        
        $this->view('admin/payroll', [
            'payrolls' => $payrolls,
            'current_month' => $month,
            'current_year' => $year
        ]);
    }

    // ---------- BÁO CÁO CÁ NHÂN (CHO NHÂN VIÊN) ----------

    public function personal_report() {
        $this->checkAccess(['admin', 'manager', 'staff']);
        
        // Mặc định là tháng trước
        $month = isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('first day of last month'));
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('first day of last month'));
        
        $employeeModel = $this->model('Employee');
        $attendanceModel = $this->model('Attendance');
        $payrollModel = $this->model('Payroll');
        $appointmentModel = $this->model('Appointment');
        
        $user_id = $_SESSION['user_id'];
        
        // 1. Dữ liệu chấm công
        $daysInMonth = date('t', strtotime("$year-$month-01"));
        $dates = [];
        for($i = 1; $i <= $daysInMonth; $i++) {
            $dates[] = sprintf('%04d-%02d-%02d', $year, $month, $i);
        }
        $historyData = $attendanceModel->getAttendanceHistory([
            'start_date' => $dates[0],
            'end_date' => $dates[count($dates)-1]
        ]);
        $matrix = [];
        $attendance_stats = ['present' => 0, 'late' => 0, 'absent' => 0, 'on_leave' => 0];
        foreach($historyData as $record) {
            if ($record->user_id == $user_id) {
                $matrix[$record->date] = $record->status;
                if(isset($attendance_stats[$record->status])) {
                    $attendance_stats[$record->status]++;
                }
            }
        }
        
        // 2. Dữ liệu bảng lương
        $payrolls = $payrollModel->getPayrolls($month, $year);
        $my_payroll = null;
        foreach($payrolls as $p) {
            if ($p->user_id == $user_id) {
                $my_payroll = $p;
                break;
            }
        }
        
        // 3. Dịch vụ đã làm
        $appointments = $appointmentModel->getCompletedAppointmentsByDoctor($user_id, $month, $year);
        
        $this->view('admin/personal_report', [
            'current_month' => $month,
            'current_year' => $year,
            'dates' => $dates,
            'matrix' => $matrix,
            'attendance_stats' => $attendance_stats,
            'payroll' => $my_payroll,
            'appointments' => $appointments
        ]);
    }

    public function payroll_save() {
        $this->checkAccess(['admin', 'manager']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $payrollModel = $this->model('Payroll');
            $month = $_POST['month'];
            $year = $_POST['year'];
            $userId = $_POST['user_id'];
            
            $data = [
                'user_id' => $userId,
                'month' => $month,
                'year' => $year,
                'base_salary' => $_POST['base_salary'],
                'bonus' => $_POST['bonus'],
                'deductions' => $_POST['deductions']
            ];
            
            if ($payrollModel->savePayroll($data)) {
                flash('payroll_success', 'Đã cập nhật bảng lương');
            } else {
                flash('payroll_error', 'Lỗi khi cập nhật bảng lương', 'bg-red-100 text-red-700 p-4 rounded');
            }
            header('Location: ' . URLROOT . '/admin/payroll?month=' . $month . '&year=' . $year);
        }
    }

    public function payroll_export() {
        $this->checkAccess(['admin', 'manager']);
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        
        $payrollModel = $this->model('Payroll');
        $payrolls = $payrollModel->getPayrolls($month, $year);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=petshop_payroll_' . $year . '_' . $month . '.csv');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Mã NV', 'Họ tên', 'Chức vụ', 'Lương cơ bản', 'Thưởng', 'Khấu trừ', 'Thực lĩnh']);
        
        foreach ($payrolls as $p) {
            $total = ($p->base_salary ?? 0) + ($p->bonus ?? 0) - ($p->deductions ?? 0);
            fputcsv($output, [
                $p->employee_code,
                $p->fullname,
                $p->role,
                $p->base_salary ?? 0,
                $p->bonus ?? 0,
                $p->deductions ?? 0,
                $total
            ]);
        }
        fclose($output);
        exit();
    }

    public function profile() {
        $userModel = $this->model('User');
        $user = $userModel->getUserById($_SESSION['user_id']);
        
        $employeeModel = $this->model('Employee');
        $employee = $employeeModel->getEmployeeByUserId($_SESSION['user_id']);
        
        $this->view('admin/profile', [
            'user' => $user,
            'employee' => $employee
        ]);
    }

    public function profile_update_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            $user = $userModel->getUserById($_SESSION['user_id']);
            
            // Verify current password
            if (!password_verify($current_password, $user->password)) {
                flash('profile_error', 'Mật khẩu hiện tại không chính xác', 'bg-red-100 text-red-700 p-4 rounded-xl mb-4');
                header('Location: ' . URLROOT . '/admin/profile');
                exit;
            }
            
            // Check match
            if ($new_password != $confirm_password) {
                flash('profile_error', 'Mật khẩu mới không khớp', 'bg-red-100 text-red-700 p-4 rounded-xl mb-4');
                header('Location: ' . URLROOT . '/admin/profile');
                exit;
            }
            
            // Update
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            if ($userModel->updatePassword($_SESSION['user_id'], $hashed)) {
                flash('profile_success', 'Đã đổi mật khẩu thành công');
            } else {
                flash('profile_error', 'Lỗi hệ thống khi cập nhật mật khẩu');
            }
            
            header('Location: ' . URLROOT . '/admin/profile');
        }
    }

    public function membership_benefits() {
        $this->checkAccess(['admin', 'manager']);
        $db = new Database();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $text = $_POST['benefit_text'];
            $discount = (int)$_POST['discount_percent'];
            $free = isset($_POST['free_service']) ? 1 : 0;
            
            $db->query("UPDATE membership_benefits SET benefit_text = :text, discount_percent = :discount, free_service = :free WHERE id = :id");
            $db->bind(':text', $text);
            $db->bind(':discount', $discount);
            $db->bind(':free', $free);
            $db->bind(':id', $id);
            $db->execute();
            
            flash('benefit_message', 'Đã cập nhật ưu đãi hội viên thành công');
            header('Location: ' . URLROOT . '/admin/membership_benefits');
            return;
        }
        
        $db->query("SELECT * FROM membership_benefits ORDER BY discount_percent ASC");
        $benefits = $db->resultSet();
        
        $this->view('admin/membership_benefits', ['benefits' => $benefits]);
    }

    public function check_updates() {
        if (!isset($_SESSION['user_id'])) exit;
        $db = new Database;
        $db->query("SELECT 
            (SELECT COUNT(*) FROM appointments WHERE status = 'pending') as pending_count,
            (SELECT COUNT(*) FROM orders WHERE status = 'pending') as pending_order_count,
            (SELECT COUNT(*) FROM appointments WHERE status = 'confirmed' AND final_price IS NOT NULL AND final_price > 0) as waiting_pay_count
        ");
        $counts = $db->single();
        header('Content-Type: application/json');
        echo json_encode([
            'pending_count' => $counts->pending_count ?? 0,
            'pending_order_count' => $counts->pending_order_count ?? 0,
            'waiting_pay_count' => $counts->waiting_pay_count ?? 0
        ]);
        exit;
    }
}
