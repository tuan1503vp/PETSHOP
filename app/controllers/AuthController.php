<?php
class AuthController extends Controller {
    private $userModel;

    private $activityLogModel;

    public function __construct() {
        $this->userModel = $this->model('User');
        $this->activityLogModel = $this->model('ActivityLog');
    }

    public function index() {
        header('Location: ' . URLROOT . '/auth/login');
    }

    public function register() {
        // Kiểm tra xem đã đăng nhập chưa
        if(isLoggedIn()) {
            header('Location: ' . URLROOT);
            return;
        }

        // Kiểm tra phương thức POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý form (Loại bỏ FILTER_SANITIZE_STRING do đã bị deprecated từ PHP 8.1)

            $data = [
                'fullname' => trim($_POST['fullname']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'fullname_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Vui lòng nhập email';
            } else {
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email này đã được sử dụng';
                }
            }

            // Validate Name
            if (empty($data['fullname'])) {
                $data['fullname_err'] = 'Vui lòng nhập họ tên';
            }

            // Validate Phone
            if (empty($data['phone'])) {
                $data['phone_err'] = 'Vui lòng nhập số điện thoại';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Vui lòng nhập mật khẩu';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }

            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Vui lòng xác nhận mật khẩu';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Mật khẩu xác nhận không khớp';
                }
            }

            // Đảm bảo không có lỗi
            if (empty($data['email_err']) && empty($data['fullname_err']) && empty($data['phone_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Mã hóa mật khẩu
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Đăng ký user
                if ($this->userModel->register($data)) {
                    flash('register_success', 'Đăng ký thành công và có thể đăng nhập');
                    header('Location: ' . URLROOT . '/auth/login');
                } else {
                    die('Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau.');
                }
            } else {
                // Load view với errors
                $this->view('auth/register', $data);
            }

        } else {
            // Init data
            $data = [
                'fullname' => '',
                'email' => '',
                'phone' => '',
                'password' => '',
                'confirm_password' => '',
                'fullname_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            $this->view('auth/register', $data);
        }
    }

    public function login() {
        // Kiểm tra xem đã đăng nhập chưa
        if(isLoggedIn()) {
            header('Location: ' . URLROOT);
            return;
        }

        // Kiểm tra phương thức POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý form (Loại bỏ FILTER_SANITIZE_STRING do đã bị deprecated từ PHP 8.1)

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Vui lòng nhập email';
            } else {
                if (!$this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Không tìm thấy người dùng với email này';
                }
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Vui lòng nhập mật khẩu';
            }

            // Đảm bảo không có lỗi
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Kiểm tra và set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    // Tạo Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Mật khẩu không chính xác';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view với errors
                $this->view('auth/login', $data);
            }

        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];

            // Load view
            $this->view('auth/login', $data);
        }
    }

    public function createUserSession($user) {
        // Tắt session_regenerate_id(true) trên host dùng chung để tránh mất session khi chuyển hướng
        // session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->fullname;
        $_SESSION['user_username'] = $user->fullname;
        $_SESSION['user_role'] = $user->role;
        
        // Ghi nhật ký hành vi đăng nhập
        $this->activityLogModel->log($user->id, $user->fullname, $user->role, 'Đăng nhập', 'Đăng nhập vào hệ thống thành công');
        
        // Chuyển hướng theo vai trò (Admin, Staff, Doctor, Cashier, Manager)
        if ($user->role == 'admin' || $user->role == 'staff' || $user->role == 'doctor' || $user->role == 'cashier' || $user->role == 'manager') {
            header('Location: ' . URLROOT . '/admin');
        } else {
            header('Location: ' . URLROOT);
        }
    }

    public function logout() {
        // Ghi nhật ký hành vi trước khi hủy session
        if (isset($_SESSION['user_id'])) {
            $this->activityLogModel->log($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_role'], 'Đăng xuất', 'Đăng xuất khỏi hệ thống');
        }
        
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_role']);
        
        // Tắt session_regenerate_id(true) trên host dùng chung để tránh lỗi mất cookie
        // session_regenerate_id(true);
        session_destroy();
        header('Location: ' . URLROOT . '/auth/login');
    }
}
