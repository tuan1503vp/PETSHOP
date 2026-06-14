<?php
class ProfileController extends Controller {
    private $userModel;

    public function __construct() {
        if (!isLoggedIn()) {
            flash('login_err', 'Vui lòng đăng nhập để xem thông tin', 'warning');
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
        $this->userModel = $this->model('User');
    }

    public function index() {
        try {
            $user_id = $_SESSION['user_id'];
            $userInfo = $this->userModel->getUserById($user_id);
            $membershipInfo = $this->userModel->getMembershipFullInfo($user_id);
            
            $data = [
                'user' => $userInfo,
                'membership' => $membershipInfo
            ];
            
            $this->view('profile/index', $data);
        } catch (Exception $e) {
            flash('error', 'Có lỗi xảy ra khi tải hồ sơ', 'error');
            header('Location: ' . URLROOT);
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            check_csrf();
            $user_id = $_SESSION['user_id'];
            
            // Validate inputs (Fullname is locked, get from session or db)
            $userInfo = $this->userModel->getUserById($user_id);
            $fullname = $userInfo->fullname;
            
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            
            if (empty($fullname)) {
                flash('profile_err', 'Họ tên không được để trống.', 'error');
                header('Location: ' . URLROOT . '/profile');
                exit;
            }

            // Handle Avatar Upload
            $avatar = null;
            if (!empty($_FILES['avatar']['name'])) {
                if (is_valid_image($_FILES['avatar'])) {
                    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                    $avatarName = 'avatar_' . $user_id . '_' . time() . '.' . $ext;
                    $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/avatars/';
                    
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath . $avatarName)) {
                        $avatar = $avatarName;
                        // Delete old avatar if exists
                        $userInfo = $this->userModel->getUserById($user_id);
                        if (!empty($userInfo->avatar) && file_exists($uploadPath . $userInfo->avatar)) {
                            unlink($uploadPath . $userInfo->avatar);
                        }
                    } else {
                        flash('profile_err', 'Lỗi khi tải ảnh lên.', 'error');
                        header('Location: ' . URLROOT . '/profile');
                        exit;
                    }
                } else {
                    flash('profile_err', 'Định dạng ảnh không hợp lệ.', 'error');
                    header('Location: ' . URLROOT . '/profile');
                    exit;
                }
            }

            // Cập nhật Database
            if ($this->userModel->updateProfile($user_id, $fullname, $phone, $address, $avatar)) {
                // Update session
                $_SESSION['user_name'] = $fullname;
                if ($avatar) {
                    $_SESSION['user_avatar'] = URLROOT . '/public/uploads/avatars/' . $avatar;
                }
                flash('profile_success', 'Cập nhật hồ sơ thành công!', 'success');
            } else {
                flash('profile_err', 'Đã có lỗi xảy ra. Vui lòng thử lại sau.', 'error');
            }
            header('Location: ' . URLROOT . '/profile');
        } else {
            header('Location: ' . URLROOT . '/profile');
        }
    }

    public function change_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            check_csrf();
            $user_id = $_SESSION['user_id'];
            $userInfo = $this->userModel->getUserById($user_id);
            
            $old_password = $_POST['old_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
                flash('profile_err', 'Vui lòng nhập đầy đủ thông tin mật khẩu.', 'error');
                header('Location: ' . URLROOT . '/profile');
                exit;
            }

            if ($new_password !== $confirm_password) {
                flash('profile_err', 'Mật khẩu mới không khớp.', 'error');
                header('Location: ' . URLROOT . '/profile');
                exit;
            }

            if (strlen($new_password) < 6) {
                flash('profile_err', 'Mật khẩu phải từ 6 ký tự trở lên.', 'error');
                header('Location: ' . URLROOT . '/profile');
                exit;
            }

            // Verify old password
            if (!password_verify($old_password, $userInfo->password)) {
                flash('profile_err', 'Mật khẩu cũ không chính xác.', 'error');
                header('Location: ' . URLROOT . '/profile');
                exit;
            }

            if ($this->userModel->updatePassword($user_id, $new_password)) {
                flash('profile_success', 'Đổi mật khẩu thành công!', 'success');
            } else {
                flash('profile_err', 'Lỗi khi cập nhật mật khẩu.', 'error');
            }
            
            header('Location: ' . URLROOT . '/profile');
            exit;
        }
    }

    public function send_delete_otp() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            check_csrf();
            $user_id = $_SESSION['user_id'];
            $userInfo = $this->userModel->getUserById($user_id);
            
            $otp = sprintf("%06d", mt_rand(1, 999999));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            if ($this->userModel->updateOTP($userInfo->email, $otp, $expiresAt)) {
                $mailer = new Mailer();
                if ($mailer->sendOTP($userInfo->email, $otp, $userInfo->fullname)) {
                    echo json_encode(['status' => 'success', 'message' => 'Mã OTP xác nhận xóa đã được gửi đến email của bạn!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Lỗi gửi email. Vui lòng thử lại!']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi máy chủ. Vui lòng thử lại sau!']);
            }
            exit;
        }
    }

    public function verify_delete_account() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            check_csrf();
            $user_id = $_SESSION['user_id'];
            $userInfo = $this->userModel->getUserById($user_id);
            
            $otp = trim($_POST['otp'] ?? '');

            if (empty($otp)) {
                flash('profile_err', 'Vui lòng nhập mã OTP.', 'error');
                header('Location: ' . URLROOT . '/profile');
                exit;
            }

            if ($this->userModel->verifyOTP($userInfo->email, $otp)) {
                // Tắt session cũ
                unset($_SESSION['user_id']);
                unset($_SESSION['user_email']);
                unset($_SESSION['user_name']);
                unset($_SESSION['user_role']);
                session_destroy();
                
                // Xóa user khỏi db
                if ($this->userModel->deleteUser($user_id)) {
                    session_start();
                    flash('login_success', 'Tài khoản của bạn đã được xóa vĩnh viễn.', 'success');
                    header('Location: ' . URLROOT . '/');
                } else {
                    session_start();
                    flash('error', 'Có lỗi xảy ra khi xóa tài khoản. Vui lòng thử lại sau.', 'error');
                    header('Location: ' . URLROOT . '/');
                }
            } else {
                flash('profile_err', 'Mã OTP không hợp lệ hoặc đã hết hạn.', 'error');
                header('Location: ' . URLROOT . '/profile');
            }
            exit;
        } else {
            header('Location: ' . URLROOT . '/profile');
        }
    }
}
