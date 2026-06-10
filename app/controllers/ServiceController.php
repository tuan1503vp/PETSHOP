<?php
class ServiceController extends Controller {
    private $serviceModel;
    private $appointmentModel;

    public function __construct() {
        redirectManagement();
        $this->serviceModel = $this->model('Service');
        $this->appointmentModel = $this->model('Appointment');
    }

    // Hiển thị danh sách dịch vụ
    // Hiển thị danh sách dịch vụ hoặc danh mục
    public function index($category_id = null) {
        if ($category_id) {
            $services = $this->serviceModel->getServicesByCategory($category_id);
            $category = null;
            // Tìm tên danh mục để hiển thị tiêu đề
            $categories = $this->serviceModel->getServiceCategories();
            foreach($categories as $c) {
                if ($c->id == $category_id) {
                    $category = $c;
                    break;
                }
            }
            
            $data = [
                'services' => $services,
                'category' => $category
            ];
            $this->view('service/index', $data);
        } else {
            $categories = $this->serviceModel->getServiceCategories();
            $data = [
                'categories' => $categories
            ];
            $this->view('service/index', $data);
        }
    }

    // Trang đặt lịch
    public function book($service_id = null) {
        if (!isLoggedIn()) {
            flash('login_required', 'Vui lòng đăng nhập để đặt lịch dịch vụ', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Loại bỏ FILTER_SANITIZE_STRING do đã bị deprecated từ PHP 8.1

            $data = [
                'customer_id' => $_SESSION['user_id'],
                'service_id' => trim($_POST['service_id']),
                'pet_id' => null,
                'pet_info' => trim($_POST['pet_info'] ?? ''),
                'doctor_id' => null,
                'appointment_date' => trim($_POST['appointment_date']),
                'appointment_time' => trim($_POST['appointment_time']),
                'duration_value' => trim($_POST['duration_value'] ?? 1),
                'duration_unit' => trim($_POST['duration_unit'] ?? 'none'),
                'notes' => trim($_POST['notes']),
                'date_err' => '',
                'time_err' => '',
            ];

            // Validate
            if (empty($data['appointment_date'])) {
                $data['date_err'] = 'Vui lòng chọn ngày hẹn';
            }
            if (empty($data['appointment_time'])) {
                $data['time_err'] = 'Vui lòng chọn giờ hẹn';
            }

            if (empty($data['date_err']) && empty($data['time_err'])) {
                // Đặt mặc định là chưa phân công
                $data['doctor_id'] = null;

                // Gộp pet_info vào notes nếu có
                if (!empty($data['pet_info'])) {
                    $data['notes'] = '[Thú cưng: ' . $data['pet_info'] . '] ' . $data['notes'];
                }

                if ($this->appointmentModel->book($data)) {
                    flash('booking_success', 'Lịch hẹn đang chờ xác nhận! Quản lý sẽ duyệt và thông báo lại cho bạn.');
                    header('Location: ' . URLROOT . '/service');
                } else {
                    die('Lỗi hệ thống khi đặt lịch');
                }
            } else {
                $data['services'] = $this->serviceModel->getServices();
                $data['selected_service'] = $data['service_id'];
                $this->view('service/book', $data);
            }
        } else {
            $services = $this->serviceModel->getServices();

            $data = [
                'services' => $services,
                'selected_service' => $service_id,
                'pet_info' => '',
                'appointment_date' => '',
                'appointment_time' => '',
                'notes' => '',
                'date_err' => '',
                'time_err' => ''
            ];

            $this->view('service/book', $data);
        }
    }
}
