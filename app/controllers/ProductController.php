<?php
class ProductController extends Controller {
    private $productModel;
    private $reviewModel;
    private $petModel;

    public function __construct() {
        redirectManagement();
        $this->productModel = $this->model('Product');
        $this->reviewModel = $this->model('Review');
        $this->petModel = $this->model('Pet');
    }

    // Trang chủ cửa hàng (Liệt kê sản phẩm)
    public function index() {
        $params = [
            'category' => $_GET['category'] ?? '',
            'search' => $_GET['search'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest',
            'price_min' => $_GET['price_min'] ?? '',
            'price_max' => $_GET['price_max'] ?? '',
            'target_pet' => $_GET['target_pet'] ?? '',
            'pet_id' => $_GET['pet_id'] ?? '',
            'page' => isset($_GET['page']) ? (int)$_GET['page'] : 1
        ];

        if ($params['page'] < 1) {
            $params['page'] = 1;
        }

        $userPets = [];
        $selectedPet = null;
        $weightStatus = 'normal';

        if (isLoggedIn()) {
            $userPets = $this->petModel->getPetsByCustomer($_SESSION['user_id']);
            if (!empty($params['pet_id'])) {
                foreach ($userPets as $p) {
                    if ($p->id == $params['pet_id']) {
                        $selectedPet = $p;
                        break;
                    }
                }
            }
        }

        // Nếu chọn thú cưng, tự động lọc theo loài của thú cưng đó
        if ($selectedPet) {
            $speciesLower = mb_strtolower($selectedPet->species, 'UTF-8');
            if (strpos($speciesLower, 'mèo') !== false || strpos($speciesLower, 'cat') !== false || strpos($speciesLower, 'miu') !== false) {
                $params['target_pet'] = 'cat';
            } else {
                $params['target_pet'] = 'dog';
            }

            // Đánh giá thể trạng thú cưng
            if (!empty($selectedPet->weight) && !empty($selectedPet->age)) {
                if ($params['target_pet'] === 'cat') {
                    if ($selectedPet->age <= 12) {
                        $expectedMin = $selectedPet->age * 0.35;
                        $expectedMax = $selectedPet->age * 0.6;
                    } else {
                        $expectedMin = 3.0;
                        $expectedMax = 5.5;
                    }
                } else {
                    if ($selectedPet->age <= 12) {
                        $expectedMin = $selectedPet->age * 0.8;
                        $expectedMax = $selectedPet->age * 1.8;
                    } else {
                        $expectedMin = 8.0;
                        $expectedMax = 25.0;
                    }
                }

                if ($selectedPet->weight < $expectedMin) {
                    $weightStatus = 'underweight';
                } elseif ($selectedPet->weight > $expectedMax) {
                    $weightStatus = 'overweight';
                }
            }
        }

        // Cấu hình phân trang
        $limit = 9; // 9 sản phẩm mỗi trang cho hiển thị 3 cột tối ưu
        $offset = ($params['page'] - 1) * $limit;
        
        $totalProducts = $this->productModel->getProductsCount($params);
        $totalPages = ceil($totalProducts / $limit);

        // Lấy sản phẩm có giới hạn phân trang
        $dbParams = $params;
        $dbParams['limit'] = $limit;
        $dbParams['offset'] = $offset;
        $products = $this->productModel->getProducts($dbParams);
        
        // Thực hiện đánh dấu và sắp xếp đề xuất nếu có chọn thú cưng
        if ($selectedPet) {
            foreach ($products as &$product) {
                $product->is_recommended = false;
                $nameDesc = mb_strtolower($product->name . ' ' . $product->description, 'UTF-8');
                
                if ($weightStatus == 'underweight') {
                    if (strpos($nameDesc, 'dinh dưỡng') !== false || strpos($nameDesc, 'con') !== false || strpos($nameDesc, 'sữa') !== false || strpos($nameDesc, 'tăng cân') !== false) {
                        $product->is_recommended = true;
                    }
                } elseif ($weightStatus == 'overweight') {
                    if (strpos($nameDesc, 'giảm cân') !== false || strpos($nameDesc, 'ăn kiêng') !== false || strpos($nameDesc, 'ít béo') !== false) {
                        $product->is_recommended = true;
                    }
                } else {
                    // Thể trạng bình thường: ưu tiên hạt khô, pate hoặc thức ăn chính của loài đó
                    if (strpos($nameDesc, 'hạt') !== false || strpos($nameDesc, 'pate') !== false || strpos($nameDesc, 'thức ăn') !== false) {
                        $product->is_recommended = true;
                    }
                }
            }
            unset($product);

            // Đưa các sản phẩm được đề xuất lên vị trí đầu tiên
            usort($products, function($a, $b) {
                $aRec = isset($a->is_recommended) && $a->is_recommended ? 1 : 0;
                $bRec = isset($b->is_recommended) && $b->is_recommended ? 1 : 0;
                return $bRec - $aRec;
            });
        }

        $categories = $this->productModel->getProductCategories();
        
        $data = [
            'products' => $products,
            'categories' => $categories,
            'params' => $params,
            'userPets' => $userPets,
            'selectedPet' => $selectedPet,
            'pagination' => [
                'total_products' => $totalProducts,
                'total_pages' => $totalPages,
                'current_page' => $params['page'],
                'limit' => $limit
            ],
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

        $additionalImages = $this->productModel->getProductImages($id);

        $data = [
            'product' => $product,
            'additional_images' => $additionalImages,
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
        header('Location: ' . URLROOT . '/product/show/' . $product_id);
        exit();
    }
}
