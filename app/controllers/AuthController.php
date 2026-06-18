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
            verify_csrf_token();
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
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Định dạng email không hợp lệ (VD: ten@gmail.com)';
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
            } elseif (!preg_match('/^(0|\+84)(3|5|7|8|9)[0-9]{8}$/', $data['phone'])) {
                $data['phone_err'] = 'Số điện thoại không hợp lệ (Gồm 10 số, bắt đầu bằng 0 hoặc +84)';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Vui lòng nhập mật khẩu';
            } elseif (strlen($data['password']) < 8) {
                $data['password_err'] = 'Mật khẩu phải có ít nhất 8 ký tự';
            } elseif (!preg_match('/[A-Z]/', $data['password'])) {
                $data['password_err'] = 'Mật khẩu phải chứa ít nhất 1 chữ hoa';
            } elseif (!preg_match('/[a-z]/', $data['password'])) {
                $data['password_err'] = 'Mật khẩu phải chứa ít nhất 1 chữ thường';
            } elseif (!preg_match('/[0-9]/', $data['password'])) {
                $data['password_err'] = 'Mật khẩu phải chứa ít nhất 1 số';
            } elseif (!preg_match('/[\W_]/', $data['password'])) {
                $data['password_err'] = 'Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt (@$!%*?&...)';
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
                $user_id = $this->userModel->register($data);
                if ($user_id) {
                    require_once APPROOT . '/helpers/Mailer.php';
                    $otp = sprintf("%06d", mt_rand(1, 999999));
                    $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                    $this->userModel->updateOTP($data['email'], $otp, $expiresAt);
                    $mailer = new Mailer();
                    $mailer->sendOTP($data['email'], $data['fullname'], $otp);

                    // Set cooldown
                    $_SESSION['last_otp_time'] = time();

                    $_SESSION['verify_email'] = $data['email'];
                    flash('verify_msg', 'Vui lòng kiểm tra email của bạn để lấy mã xác thực.');
                    header('Location: ' . URLROOT . '/auth/verify');
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
            verify_csrf_token();
            
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
                    if (isset($loggedInUser->is_verified) && $loggedInUser->is_verified == 0) {
                        // User chưa xác minh
                        $_SESSION['verify_email'] = $loggedInUser->email;
                        
                        // Gửi lại mã OTP
                        require_once APPROOT . '/helpers/Mailer.php';
                        $otp = sprintf("%06d", mt_rand(1, 999999));
                        $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                        $this->userModel->updateOTP($loggedInUser->email, $otp, $expiresAt);
                        
                        $mailer = new Mailer();
                        $mailer->sendOTP($loggedInUser->email, $loggedInUser->fullname, $otp);

                        flash('verify_msg', 'Vui lòng xác thực email trước khi đăng nhập. Một mã OTP mới đã được gửi.', 'bg-yellow-100 text-yellow-700 p-3 rounded-md mb-4 text-sm');
                        header('Location: ' . URLROOT . '/auth/verify');
                    } else {
                        // Tạo Session
                        $this->createUserSession($loggedInUser);
                    }
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

    public function verify() {
        if (!isset($_SESSION['verify_email'])) {
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            verify_csrf_token();
            $otp = trim($_POST['otp']);
            $email = $_SESSION['verify_email'];

            if (empty($otp)) {
                $data = ['otp_err' => 'Vui lòng nhập mã OTP'];
                $this->view('auth/verify', $data);
            } else {
                if ($this->userModel->verifyOTP($email, $otp)) {
                    // Success
                    unset($_SESSION['verify_email']);
                    flash('register_success', 'Xác nhận Email thành công! Bạn đã có thể đăng nhập.');
                    header('Location: ' . URLROOT . '/auth/login');
                } else {
                    $data = ['otp_err' => 'Mã OTP không chính xác hoặc đã hết hạn'];
                    $this->view('auth/verify', $data);
                }
            }
        } else {
            $this->view('auth/verify', []);
        }
    }

    public function resend_otp() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['verify_email'])) {
            verify_csrf_token();
            
            $cooldown = 60;
            if (isset($_SESSION['last_otp_time']) && (time() - $_SESSION['last_otp_time'] < $cooldown)) {
                $remaining = $cooldown - (time() - $_SESSION['last_otp_time']);
                flash('verify_msg', 'Vui lòng đợi ' . $remaining . ' giây trước khi gửi lại mã.', 'error');
                header('Location: ' . URLROOT . '/auth/verify');
                exit;
            }

            $email = $_SESSION['verify_email'];
            $user = $this->userModel->getUserByEmail($email);
            if ($user && isset($user->is_verified) && $user->is_verified == 0) {
                require_once APPROOT . '/helpers/Mailer.php';
                $otp = sprintf("%06d", mt_rand(1, 999999));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                $this->userModel->updateOTP($email, $otp, $expiresAt);
                
                $mailer = new Mailer();
                $mailer->sendOTP($email, $user->fullname, $otp);
                
                $_SESSION['last_otp_time'] = time();
                
                flash('verify_msg', 'Đã gửi lại mã OTP mới đến email của bạn', 'success');
                header('Location: ' . URLROOT . '/auth/verify');
            }
        } else {
            header('Location: ' . URLROOT . '/auth/login');
        }
    }

    public function forgot_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            verify_csrf_token();
            $email = trim($_POST['email']);
            
            $cooldown = 60;
            if (isset($_SESSION['last_otp_time']) && (time() - $_SESSION['last_otp_time'] < $cooldown)) {
                $remaining = $cooldown - (time() - $_SESSION['last_otp_time']);
                $data = ['email_err' => 'Vui lòng đợi ' . $remaining . ' giây trước khi gửi lại mã.'];
                $this->view('auth/forgot_password', $data);
                return;
            }

            if (empty($email)) {
                $data = ['email_err' => 'Vui lòng nhập email'];
                $this->view('auth/forgot_password', $data);
            } else {
                $user = $this->userModel->getUserByEmail($email);
                if ($user) {
                    require_once APPROOT . '/helpers/Mailer.php';
                    $otp = sprintf("%06d", mt_rand(1, 999999));
                    $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                    $this->userModel->updateOTP($email, $otp, $expiresAt);
                    
                    $mailer = new Mailer();
                    // Chúng ta sử dụng hàm sendOTP tương tự, hoặc có thể tạo thêm template sendPasswordResetOTP nếu cần
                    $mailer->sendOTP($email, $user->fullname, $otp);
                    
                    $_SESSION['last_otp_time'] = time();
                    
                    $_SESSION['reset_email'] = $email;
                    header('Location: ' . URLROOT . '/auth/reset_password');
                } else {
                    $data = ['email_err' => 'Email không tồn tại trong hệ thống'];
                    $this->view('auth/forgot_password', $data);
                }
            }
        } else {
            $this->view('auth/forgot_password', ['email_err' => '']);
        }
    }

    public function reset_password() {
        if (!isset($_SESSION['reset_email'])) {
            header('Location: ' . URLROOT . '/auth/forgot_password');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            verify_csrf_token();
            $otp = trim($_POST['otp']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            $email = $_SESSION['reset_email'];

            $data = [
                'otp_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            if (empty($otp)) $data['otp_err'] = 'Vui lòng nhập mã OTP';
            if (empty($password)) $data['password_err'] = 'Vui lòng nhập mật khẩu mới';
            elseif (strlen($password) < 8) $data['password_err'] = 'Mật khẩu phải có ít nhất 8 ký tự';
            if (empty($confirm_password)) $data['confirm_password_err'] = 'Vui lòng xác nhận mật khẩu';
            elseif ($password != $confirm_password) $data['confirm_password_err'] = 'Mật khẩu xác nhận không khớp';

            if (empty($data['otp_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                if ($this->userModel->checkOTP($email, $otp)) {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    if ($this->userModel->updatePasswordByEmail($email, $password_hash)) {
                        $this->userModel->clearOTP($email);
                        unset($_SESSION['reset_email']);
                        flash('register_success', 'Đổi mật khẩu thành công! Vui lòng đăng nhập.');
                        header('Location: ' . URLROOT . '/auth/login');
                    } else {
                        die('Có lỗi xảy ra.');
                    }
                } else {
                    $data['otp_err'] = 'Mã OTP không chính xác hoặc đã hết hạn';
                    $this->view('auth/reset_password', $data);
                }
            } else {
                $this->view('auth/reset_password', $data);
            }
        } else {
            $this->view('auth/reset_password', [
                'otp_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ]);
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
        if (!empty($user->avatar)) {
            $_SESSION['user_avatar'] = URLROOT . '/public/uploads/avatars/' . $user->avatar;
        }
        
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
        
        // session_regenerate_id(true);
        session_destroy();
        header('Location: ' . URLROOT . '/auth/login');
    }

    public function google() {
        if (GOOGLE_CLIENT_ID === 'YOUR_GOOGLE_CLIENT_ID') {
            flash('login_err', 'Tính năng đăng nhập bằng Google đang được cấu hình API. Vui lòng thử lại sau!', 'warning');
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => GOOGLE_REDIRECT_URL,
            'response_type' => 'code',
            'scope' => 'email profile',
            'prompt' => 'select_account'
        ]);
        header('Location: ' . $url);
        exit;
    }

    public function google_callback() {
        if (!isset($_GET['code'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        // 1. Get Access Token
        $token_url = 'https://oauth2.googleapis.com/token';
        $post_data = [
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URL,
            'grant_type' => 'authorization_code',
            'code' => $_GET['code']
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $token_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $token_data = json_decode($response, true);
        if (isset($token_data['error']) || !isset($token_data['access_token'])) {
            flash('login_err', 'Lỗi xác thực Google. Vui lòng thử lại.', 'error');
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        // 2. Get User Info
        $info_url = 'https://www.googleapis.com/oauth2/v2/userinfo';
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $info_url);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token_data['access_token']]);
        $info_response = curl_exec($ch2);
        curl_close($ch2);

        $user_info = json_decode($info_response, true);
        if (!isset($user_info['id'])) {
            flash('login_err', 'Không thể lấy thông tin từ Google.', 'error');
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $this->processOAuthLogin('google', $user_info['id'], $user_info['email'] ?? '', $user_info['name'] ?? 'Google User');
    }

    public function facebook() {
        if (FACEBOOK_APP_ID === 'YOUR_FACEBOOK_APP_ID') {
            flash('login_err', 'Tính năng đăng nhập bằng Facebook đang được cấu hình API. Vui lòng thử lại sau!', 'warning');
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $url = 'https://www.facebook.com/v12.0/dialog/oauth?' . http_build_query([
            'client_id' => FACEBOOK_APP_ID,
            'redirect_uri' => FACEBOOK_REDIRECT_URL,
            'scope' => 'email,public_profile'
        ]);
        header('Location: ' . $url);
        exit;
    }

    public function facebook_callback() {
        if (!isset($_GET['code'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        // 1. Get Access Token
        $token_url = 'https://graph.facebook.com/v12.0/oauth/access_token?' . http_build_query([
            'client_id' => FACEBOOK_APP_ID,
            'client_secret' => FACEBOOK_APP_SECRET,
            'redirect_uri' => FACEBOOK_REDIRECT_URL,
            'code' => $_GET['code']
        ]);

        $response = file_get_contents($token_url);
        $token_data = json_decode($response, true);

        if (isset($token_data['error']) || !isset($token_data['access_token'])) {
            flash('login_err', 'Lỗi xác thực Facebook. Vui lòng thử lại.', 'error');
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        // 2. Get User Info
        $info_url = 'https://graph.facebook.com/me?' . http_build_query([
            'fields' => 'id,name,email',
            'access_token' => $token_data['access_token']
        ]);

        $info_response = file_get_contents($info_url);
        $user_info = json_decode($info_response, true);

        if (!isset($user_info['id'])) {
            flash('login_err', 'Không thể lấy thông tin từ Facebook.', 'error');
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $this->processOAuthLogin('facebook', $user_info['id'], $user_info['email'] ?? '', $user_info['name'] ?? 'Facebook User');
    }

    private function processOAuthLogin($provider, $provider_id, $email, $fullname) {
        $user = null;

        if ($provider === 'google') {
            $user = $this->userModel->findUserByGoogleId($provider_id);
        } else {
            $user = $this->userModel->findUserByFacebookId($provider_id);
        }

        if ($user) {
            // Already linked, login
            $this->createUserSession($user);
            return;
        }

        // Not linked by ID, check by email
        if (!empty($email)) {
            $userByEmail = $this->userModel->getUserByEmail($email);
            if ($userByEmail) {
                // Link account and login
                $this->userModel->updateOAuthId($userByEmail->id, $provider, $provider_id);
                $this->createUserSession($userByEmail);
                return;
            }
        }

        // New user, register and login
        $data = [
            'fullname' => $fullname,
            'email' => empty($email) ? ($provider_id . '@' . $provider . '.local') : $email,
            'google_id' => $provider === 'google' ? $provider_id : null,
            'facebook_id' => $provider === 'facebook' ? $provider_id : null
        ];

        $newUser = $this->userModel->registerOAuthUser($data);
        if ($newUser) {
            $this->createUserSession($newUser);
        } else {
            flash('login_err', 'Có lỗi xảy ra khi tạo tài khoản.', 'error');
            header('Location: ' . URLROOT . '/auth/login');
        }
    }
}
