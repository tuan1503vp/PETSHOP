<?php
class ContactController extends Controller {
    private $contactModel;

    public function __construct() {
        redirectManagement();
        $this->contactModel = $this->model('Contact');
    }

    public function index() {
        if (!isLoggedIn()) {
            flash('login_required', 'Vui lòng đăng nhập để có thể gửi yêu cầu liên hệ.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }
        $data = [
            'seo' => [
                'title' => 'Liên hệ - PETSHOP',
                'description' => 'Gửi phản hồi, thắc mắc hoặc yêu cầu hỗ trợ đến PETSHOP. Chúng tôi luôn lắng nghe bạn.',
                'image' => URLROOT . '/public/img/contact-banner.jpg'
            ]
        ];
        $this->view('pages/contact', $data);
    }

    public function submit() {
        if (!isLoggedIn()) {
            flash('login_required', 'Vui lòng đăng nhập để có thể gửi yêu cầu liên hệ.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'message' => trim($_POST['message'] ?? '')
            ];

            if (!empty($data['name']) && !empty($data['email']) && !empty($data['message'])) {
                if ($this->contactModel->addContact($data)) {
                    require_once APPROOT . '/helpers/Mailer.php';
                    $mailer = new Mailer();
                    $mailer->sendContactNotificationToAdmin($data['name'], $data['email'], $data['message']);

                    flash('contact_success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ gửi phản hồi qua email. Vui lòng kiểm tra hộp thư email của bạn thường xuyên nhé!', 'success');
                } else {
                    flash('contact_error', 'Có lỗi xảy ra, vui lòng thử lại sau.', 'error');
                }
            } else {
                flash('contact_error', 'Vui lòng điền đầy đủ thông tin.', 'error');
            }
        }
        header('Location: ' . URLROOT . '/contact');
        exit();
    }
}
