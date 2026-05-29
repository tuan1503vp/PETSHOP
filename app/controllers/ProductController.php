<?php
class ProductController extends Controller {
    private $productModel;
    private $reviewModel;

    public function __construct() {
        $this->productModel = $this->model('Product');
        $this->reviewModel = $this->model('Review');
    }

    // Trang chủ cửa hàng (Liệt kê sản phẩm)
    public function index() {
        $params = [
            'category' => $_GET['category'] ?? '',
            'search' => $_GET['search'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest',
            'price_min' => $_GET['price_min'] ?? '',
            'price_max' => $_GET['price_max'] ?? '',
            'target_pet' => $_GET['target_pet'] ?? ''
        ];

        $products = $this->productModel->getProducts($params);
        $categories = $this->productModel->getProductCategories();
        
        $data = [
            'products' => $products,
            'categories' => $categories,
            'params' => $params,
            'seo' => [
                'title' => 'Cửa hàng Thú cưng - PETSHOP',
                'description' => 'Khám phá ngay hàng trăm sản phẩm thức ăn, phụ kiện, đồ chơi chính hãng cho thú cưng của bạn tại PETSHOP. Cam kết chất lượng, giao hàng tận nơi.',
                'image' => URLROOT . '/public/img/shop-banner.jpg'
            ]
        ];

        $this->view('product/index', $data);
    }

    // Xem chi tiết sản phẩm
    public function show($id) {
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            die('Sản phẩm không tồn tại');
        }

        $reviews = $this->reviewModel->getReviewsByProductId($id);
        $ratingInfo = $this->reviewModel->getAverageRating($id);

        $data = [
            'product' => $product,
            'reviews' => $reviews,
            'ratingInfo' => $ratingInfo,
            'seo' => [
                'title' => $product->name . ' - PETSHOP',
                'description' => mb_substr(strip_tags($product->description), 0, 150) . '...',
                'image' => !empty($product->image) ? URLROOT . '/public/images/' . $product->image : ''
            ]
        ];

        $this->view('product/show', $data);
    }

    // Xử lý gửi đánh giá
    public function addReview($product_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()) {
            $data = [
                'product_id' => $product_id,
                'user_id' => $_SESSION['user_id'],
                'rating' => $_POST['rating'] ?? 5,
                'comment' => trim($_POST['comment'] ?? '')
            ];

            if ($this->reviewModel->addReview($data)) {
                flash('review_success', 'Đánh giá của bạn đã được gửi thành công!', 'success');
            } else {
                flash('review_error', 'Đã có lỗi xảy ra, vui lòng thử lại!', 'error');
            }
        }
        redirect('/product/show/' . $product_id);
    }
}
