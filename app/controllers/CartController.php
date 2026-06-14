<?php
class CartController extends Controller {
    private $productModel;

    public function __construct() {
        redirectManagement();
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
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để sử dụng giỏ hàng.'
                ]);
                exit;
            }
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
                        'quantity' => $quantity,
                        'category_id' => $product->category_id
                    ];
                }
                flash('cart_success', 'Đã thêm sản phẩm vào giỏ hàng');
                
                if ($this->isAjaxRequest()) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'message' => 'Đã thêm ' . $product->name . ' vào giỏ hàng thành công! 🛒',
                        'cartCount' => $this->getCartCount()
                    ]);
                    exit;
                }
            } else {
                if ($this->isAjaxRequest()) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'Sản phẩm không tồn tại.'
                    ]);
                    exit;
                }
            }
            // Quay lại trang trước đó
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Yêu cầu không hợp lệ.'
                ]);
                exit;
            }
            header('Location: ' . URLROOT . '/product');
        }
    }

    private function isAjaxRequest() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
            || (isset($_POST['ajax']) && $_POST['ajax'] == 1)
            || (isset($_GET['ajax']) && $_GET['ajax'] == 1)
            || (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false);
    }

    private function getCartCount() {
        $count = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += $item['quantity'];
            }
        }
        return $count;
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

        require_once APPROOT . '/models/Voucher.php';
        $voucherModel = new Voucher();

        // Fetch user's active internal vouchers
        $allUserVouchers = $voucherModel->getActiveUserVouchers($_SESSION['user_id']);
        
        $usableVouchers = [];
        $total = $this->calculateTotal();
        
        foreach ($allUserVouchers as $v) {
            // Check min_order_value
            if ($total < $v->min_order_value) continue;
            
            // Check category logic
            $eligible_amount = 0;
            if ($v->category_id) {
                foreach ($_SESSION['cart'] as $item) {
                    if ($item['category_id'] == $v->category_id) {
                        $eligible_amount += $item['price'] * $item['quantity'];
                    }
                }
                if ($eligible_amount <= 0) continue;
            } else {
                $eligible_amount = $total;
            }
            
            $usableVouchers[] = $v;
        }

        $userModel = $this->model('User');
        $memDiscountInfo = $userModel->getMembershipDiscount($_SESSION['user_id']);

        // Tạm thời chỉ hiển thị form xác nhận
        $data = [
            'cart' => $_SESSION['cart'],
            'total' => $total,
            'user' => $userModel->getUserById($_SESSION['user_id']),
            'vouchers' => $usableVouchers,
            'membership_discount' => $memDiscountInfo
        ];

        $this->view('cart/checkout', $data);
    }

    public function apply_voucher() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->isAjaxRequest()) {
            if (!isLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
                return;
            }

            $code = trim($_POST['code'] ?? '');
            if (empty($code)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã voucher']);
                return;
            }

            if (empty($_SESSION['cart'])) {
                echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống']);
                return;
            }

            require_once APPROOT . '/models/Voucher.php';
            $voucherModel = new Voucher();
            $voucher = $voucherModel->getVoucherByCode($code, $_SESSION['user_id']);

            if (!$voucher) {
                echo json_encode(['success' => false, 'message' => 'Mã voucher không hợp lệ, không áp dụng được hoặc đã hết lượt sử dụng']);
                return;
            }

            $total = $this->calculateTotal();
            if ($total < $voucher->min_order_value) {
                echo json_encode(['success' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($voucher->min_order_value, 0, ',', '.') . 'đ']);
                return;
            }

            $eligible_amount = 0;
            if (!empty($voucher->category_id)) {
                // Fetch product category mapping if missing in session
                $this->productModel = $this->model('Product');
                foreach ($_SESSION['cart'] as $item) {
                    $item_cat_id = $item['category_id'] ?? null;
                    if (!$item_cat_id) {
                        $p = $this->productModel->getProductById($item['id']);
                        $item_cat_id = $p->category_id ?? null;
                    }
                    if ($item_cat_id == $voucher->category_id) {
                        $eligible_amount += $item['price'] * $item['quantity'];
                    }
                }
                if ($eligible_amount == 0) {
                    echo json_encode(['success' => false, 'message' => 'Giỏ hàng không có sản phẩm nào thuộc danh mục được giảm giá']);
                    return;
                }
            } else {
                $eligible_amount = $total;
            }

            $discount = 0;
            if ($voucher->discount_type == 'percent') {
                $discount = $eligible_amount * ($voucher->discount_amount / 100);
                if (!empty($voucher->max_discount) && $discount > $voucher->max_discount) {
                    $discount = $voucher->max_discount;
                }
            } else {
                $discount = min($voucher->discount_amount, $eligible_amount);
            }

            echo json_encode([
                'success' => true, 
                'discount_amount' => floor($discount),
                'code' => $voucher->code,
                'is_combinable' => $voucher->is_combinable == 1
            ]);
        }
    }
}
