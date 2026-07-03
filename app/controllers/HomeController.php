<?php
class HomeController extends Controller {
    private $petModel;
    private $productModel;

    public function __construct() {
        redirectManagement();
        $this->petModel = $this->model('Pet');
        $this->productModel = $this->model('Product');
    }

    public function index() {
        $recommendedPet = null;
        $recommendedProducts = [];

        if (isLoggedIn()) {
            $userPets = $this->petModel->getPetsByCustomer($_SESSION['user_id']);
            if (!empty($userPets)) {
                $recommendedPet = $userPets[0]; // Lấy thú cưng đầu tiên để đề xuất
                
                // Lọc sản phẩm theo giống loài
                $targetPet = 'dog';
                $speciesLower = mb_strtolower($recommendedPet->species, 'UTF-8');
                if (strpos($speciesLower, 'mèo') !== false || strpos($speciesLower, 'cat') !== false || strpos($speciesLower, 'miu') !== false) {
                    $targetPet = 'cat';
                }
                
                // Đánh giá thể trạng thú cưng
                $weightStatus = 'normal';
                if (!empty($recommendedPet->weight) && !empty($recommendedPet->age)) {
                    if ($targetPet === 'cat') {
                        if ($recommendedPet->age <= 12) {
                            $expectedMin = $recommendedPet->age * 0.35;
                            $expectedMax = $recommendedPet->age * 0.6;
                        } else {
                            $expectedMin = 3.0;
                            $expectedMax = 5.5;
                        }
                    } else {
                        if ($recommendedPet->age <= 12) {
                            $expectedMin = $recommendedPet->age * 0.8;
                            $expectedMax = $recommendedPet->age * 1.8;
                        } else {
                            $expectedMin = 8.0;
                            $expectedMax = 25.0;
                        }
                    }

                    if ($recommendedPet->weight < $expectedMin) {
                        $weightStatus = 'underweight';
                    } elseif ($recommendedPet->weight > $expectedMax) {
                        $weightStatus = 'overweight';
                    }
                }
                
                // Lấy danh sách sản phẩm theo bộ lọc loài
                $products = $this->productModel->getProducts(['target_pet' => $targetPet]);
                
                // Tính toán điểm đề xuất dựa trên thể trạng
                foreach ($products as &$product) {
                    $product->is_recommended = false;
                    $score = 0;
                    $nameDesc = mb_strtolower($product->name . ' ' . $product->description, 'UTF-8');
                    
                    if ($weightStatus == 'underweight') {
                        if (strpos($nameDesc, 'dinh dưỡng') !== false || strpos($nameDesc, 'con') !== false || strpos($nameDesc, 'sữa') !== false || strpos($nameDesc, 'tăng cân') !== false) {
                            $score = 10;
                            $product->is_recommended = true;
                        }
                    } elseif ($weightStatus == 'overweight') {
                        if (strpos($nameDesc, 'giảm cân') !== false || strpos($nameDesc, 'ăn kiêng') !== false || strpos($nameDesc, 'ít béo') !== false) {
                            $score = 10;
                            $product->is_recommended = true;
                        }
                    } else {
                        // Thể trạng bình thường: đề xuất thức ăn chính
                        if (strpos($nameDesc, 'hạt') !== false || strpos($nameDesc, 'pate') !== false || strpos($nameDesc, 'thức ăn') !== false) {
                            $score = 5;
                            $product->is_recommended = true;
                        }
                    }
                    $product->rec_score = $score;
                }
                unset($product);
                
                // Sắp xếp sản phẩm theo điểm đề xuất giảm dần
                usort($products, function($a, $b) {
                    $aScore = $a->rec_score ?? 0;
                    $bScore = $b->rec_score ?? 0;
                    return $bScore - $aScore;
                });
                
                $recommendedProducts = array_slice($products, 0, 4);
            }
        }

        $data = [
            'title' => 'Chào mừng đến với PETSHOP',
            'description' => 'Hệ thống quản lý toàn diện dành cho cửa hàng thú cưng.',
            'recommendedPet' => $recommendedPet,
            'recommendedProducts' => $recommendedProducts
        ];
        
        $this->view('home/index', $data);
    }
}
