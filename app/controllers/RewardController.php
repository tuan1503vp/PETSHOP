<?php
class RewardController extends Controller {
    private $voucherModel;
    private $coinModel;
    private $userModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
        $this->voucherModel = $this->model('Voucher');
        $this->coinModel = $this->model('Coin');
        $this->userModel = $this->model('User');
    }

    public function index() {
        // Có thể gộp vào profile index
        header('Location: ' . URLROOT . '/profile');
        exit;
    }

    public function exchange() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $voucher_id = $_POST['voucher_id'];
            $voucher = $this->voucherModel->getVoucherById($voucher_id);
            $user_id = $_SESSION['user_id'];
            $user = $this->userModel->getUserById($user_id);

            if ($voucher && $user->coins >= $voucher->cost_coins) {
                // Trừ xu
                if ($this->coinModel->deductCoins($user_id, $voucher->cost_coins, 'Đổi ' . $voucher->title)) {
                    // Thêm voucher cho user
                    if($this->voucherModel->addVoucherToUser($user_id, $voucher_id)) {
                        flash('profile_success', 'Tuyệt vời! Bạn đã đổi Xu lấy Voucher thành công.');
                    } else {
                        flash('profile_error', 'Lỗi cấp voucher.');
                    }
                } else {
                    flash('profile_error', 'Có lỗi xảy ra khi trừ xu.');
                }
            } else {
                flash('profile_error', 'Bạn không đủ Xu để đổi voucher này.');
            }
            header('Location: ' . URLROOT . '/profile?tab=reward');
            exit;
        }
    }
}
?>
