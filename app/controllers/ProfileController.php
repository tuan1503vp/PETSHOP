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
}
