<?php
class CartController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = $this->model('Product');
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Hiển thị giỏ hàng
    public function index() {
        if(!isLoggedIn()) {
            flash('login_required', 'Vui lòng đăng nhập để xem giỏ hàng', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }
        $data = [
            'cart' => $_SESSION['cart'],
            'total' => $this->calculateTotal()
        ];
        $this->view('cart/index', $data);
    }

    // Thêm sản phẩm vào giỏ
    public function add($id) {
        if(!isLoggedIn()) {
            flash('login_required', 'Vui lòng đăng nhập để sử dụng giỏ hàng', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            $product = $this->productModel->getProductById($id);
            if ($product) {
                // Kiểm tra xem sản phẩm đã có trong giỏ chưa
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$id] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image' => $product->image,
                        'quantity' => $quantity
                    ];
                }
                flash('cart_success', 'Đã thêm sản phẩm vào giỏ hàng');
            }
            // Quay lại trang trước đó
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: ' . URLROOT . '/product');
        }
    }

    // Cập nhật số lượng
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach ($_POST['quantity'] as $id => $quantity) {
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$id]);
                } else {
                    $_SESSION['cart'][$id]['quantity'] = $quantity;
                }
            }
            header('Location: ' . URLROOT . '/cart');
        }
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: ' . URLROOT . '/cart');
    }

    // Tính tổng tiền
    private function calculateTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    // Checkout (Đặt hàng)
    public function checkout() {
        if (!isLoggedIn()) {
            flash('login_required', 'Vui lòng đăng nhập để đặt hàng', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }

        if (empty($_SESSION['cart'])) {
            header('Location: ' . URLROOT . '/product');
            return;
        }

        // Tạm thời chỉ hiển thị form xác nhận
        $data = [
            'cart' => $_SESSION['cart'],
            'total' => $this->calculateTotal(),
            'user' => $this->model('User')->getUserById($_SESSION['user_id'])
        ];

        $this->view('cart/checkout', $data);
    }
}
