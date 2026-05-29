<?php
class WishlistController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = $this->model('Product');
        if(!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }
    }

    // Toggle (Thêm/Xóa) sản phẩm khỏi Wishlist via AJAX
    public function toggle($id) {
        header('Content-Type: application/json');
        if(!isLoggedIn()) {
            echo json_encode([
                'success' => false,
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập.'
            ]);
            exit;
        }
        
        $id = (int)$id;
        $status = 'added';

        if(($key = array_search($id, $_SESSION['wishlist'])) !== false) {
            unset($_SESSION['wishlist'][$key]);
            $_SESSION['wishlist'] = array_values($_SESSION['wishlist']); // Re-index
            $status = 'removed';
        } else {
            $_SESSION['wishlist'][] = $id;
        }

        echo json_encode([
            'status' => $status,
            'count' => count($_SESSION['wishlist'])
        ]);
        exit;
    }

    // Trang hiển thị danh sách yêu thích
    public function index() {
        if(!isLoggedIn()) {
            flash('login_required', 'Vui lòng đăng nhập để xem danh sách yêu thích', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }
        $products = [];
        if(!empty($_SESSION['wishlist'])) {
            foreach($_SESSION['wishlist'] as $id) {
                $prod = $this->productModel->getProductById($id);
                if($prod) {
                    $products[] = $prod;
                }
            }
        }

        $data = [
            'products' => $products
        ];

        $this->view('wishlist/index', $data);
    }
}
