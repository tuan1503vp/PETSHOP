<?php
class PetController extends Controller {
    private $petModel;
    private $healthLogModel;
    private $healthRecordModel;
    private $vaccinationModel;
    private $milestoneModel;

    public function __construct() {
        redirectManagement();
        
        if (!isLoggedIn()) {
            flash('login_required', 'Vui lòng đăng nhập để có thể quản lý thú cưng của bạn.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        $this->petModel = $this->model('Pet');
        $this->healthLogModel = $this->model('PetHealthLog');
        $this->healthRecordModel = $this->model('HealthRecord');
        $this->vaccinationModel = $this->model('Vaccination');
        $this->milestoneModel = $this->model('Milestone');
    }

    // Trang danh sách thú cưng của tôi
    public function index() {
        $pets = $this->petModel->getPetsByCustomer($_SESSION['user_id']);
        
        $data = [
            'pets' => $pets,
            'seo' => [
                'title' => 'Thú cưng của tôi - PETSHOP',
                'description' => 'Quản lý danh sách thú cưng và theo dõi sức khỏe của thú cưng của bạn.'
            ]
        ];

        $this->view('pet/index', $data);
    }

    // Thêm thú cưng mới
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'species' => trim($_POST['species'] ?? ''),
                'breed' => trim($_POST['breed'] ?? ''),
                'age' => trim($_POST['age'] ?? ''),
                'gender' => trim($_POST['gender'] ?? 'unknown'),
                'color' => trim($_POST['color'] ?? ''),
                'weight' => trim($_POST['weight'] ?? ''),
                'customer_id' => $_SESSION['user_id'],
                'image' => '',
                'name_err' => '',
                'species_err' => '',
                'age_err' => ''
            ];

            // Validate fields
            if (empty($data['name'])) {
                $data['name_err'] = 'Vui lòng nhập tên thú cưng';
            }
            if (empty($data['species'])) {
                $data['species_err'] = 'Vui lòng chọn loại thú cưng';
            }
            if (empty($data['age'])) {
                $data['age_err'] = 'Vui lòng nhập số tháng tuổi';
            }

            // Xử lý upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadDir = APPROOT . '/../public/images/pets/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filename = time() . '_' . $_FILES['image']['name'];
                $destination = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $data['image'] = 'pets/' . $filename;
                }
            }

            // Nếu không lỗi, lưu thú cưng
            if (empty($data['name_err']) && empty($data['species_err']) && empty($data['age_err'])) {
                $data['pet_code'] = $this->petModel->generatePetCode();
                
                if ($this->petModel->addPet($data)) {
                    flash('pet_message', 'Đã thêm thú cưng thành công!');
                    header('Location: ' . URLROOT . '/pet');
                    exit();
                } else {
                    flash('pet_message', 'Có lỗi xảy ra khi thêm thú cưng. Vui lòng thử lại.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
                }
            }

            // Render lại form nếu lỗi
            $this->view('pet/add', $data);

        } else {
            $data = [
                'name' => '',
                'species' => '',
                'breed' => '',
                'age' => '',
                'gender' => 'unknown',
                'color' => '',
                'weight' => '',
                'name_err' => '',
                'species_err' => '',
                'age_err' => '',
                'seo' => [
                    'title' => 'Thêm thú cưng mới - PETSHOP'
                ]
            ];

            $this->view('pet/add', $data);
        }
    }

    // Chỉnh sửa thông tin thú cưng
    public function edit($id) {
        $pet = $this->petModel->getPetById($id);

        // Bảo mật: kiểm tra chủ sở hữu
        if (!$pet || $pet->customer_id != $_SESSION['user_id']) {
            flash('pet_message', 'Bạn không có quyền chỉnh sửa thú cưng này hoặc thú cưng không tồn tại.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/pet');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'species' => trim($_POST['species'] ?? ''),
                'breed' => trim($_POST['breed'] ?? ''),
                'age' => trim($_POST['age'] ?? ''),
                'gender' => trim($_POST['gender'] ?? 'unknown'),
                'color' => trim($_POST['color'] ?? ''),
                'weight' => trim($_POST['weight'] ?? ''),
                'image' => $pet->image,
                'name_err' => '',
                'species_err' => '',
                'age_err' => ''
            ];

            // Validate fields
            if (empty($data['name'])) {
                $data['name_err'] = 'Vui lòng nhập tên thú cưng';
            }
            if (empty($data['species'])) {
                $data['species_err'] = 'Vui lòng chọn loại thú cưng';
            }
            if (empty($data['age'])) {
                $data['age_err'] = 'Vui lòng nhập số tháng tuổi';
            }

            // Xử lý upload ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadDir = APPROOT . '/../public/images/pets/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filename = time() . '_' . $_FILES['image']['name'];
                $destination = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    // Xóa ảnh cũ
                    if (!empty($pet->image)) {
                        $oldFile = APPROOT . '/../public/images/' . $pet->image;
                        if (file_exists($oldFile) && is_file($oldFile)) {
                            unlink($oldFile);
                        }
                    }
                    $data['image'] = 'pets/' . $filename;
                }
            }

            // Nếu không lỗi, cập nhật thú cưng
            if (empty($data['name_err']) && empty($data['species_err']) && empty($data['age_err'])) {
                if ($this->petModel->updatePet($data)) {
                    flash('pet_message', 'Cập nhật thông tin thú cưng thành công!');
                    header('Location: ' . URLROOT . '/pet');
                    exit();
                } else {
                    flash('pet_message', 'Có lỗi xảy ra. Vui lòng thử lại.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
                }
            }

            $this->view('pet/edit', $data);

        } else {
            $data = [
                'id' => $pet->id,
                'pet_code' => $pet->pet_code,
                'name' => $pet->name,
                'species' => $pet->species,
                'breed' => $pet->breed,
                'age' => $pet->age,
                'gender' => $pet->gender,
                'color' => $pet->color,
                'weight' => $pet->weight,
                'image' => $pet->image,
                'name_err' => '',
                'species_err' => '',
                'age_err' => '',
                'seo' => [
                    'title' => 'Chỉnh sửa thú cưng - PETSHOP'
                ]
            ];

            $this->view('pet/edit', $data);
        }
    }

    // Xóa thú cưng
    public function delete($id) {
        $pet = $this->petModel->getPetById($id);

        if (!$pet || $pet->customer_id != $_SESSION['user_id']) {
            flash('pet_message', 'Bạn không có quyền xóa thú cưng này.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/pet');
            exit();
        }

        // Xóa ảnh thú cưng trên ổ đĩa
        if (!empty($pet->image)) {
            $oldFile = APPROOT . '/../public/images/' . $pet->image;
            if (file_exists($oldFile) && is_file($oldFile)) {
                unlink($oldFile);
            }
        }

        if ($this->petModel->deletePet($id)) {
            flash('pet_message', 'Đã xóa thú cưng thành công!');
        } else {
            flash('pet_message', 'Lỗi không thể xóa thú cưng.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
        }

        header('Location: ' . URLROOT . '/pet');
        exit();
    }

    // Sổ sức khỏe thú cưng (Theo dõi daily + Khám y bạ + Tiêm phòng)
    public function health_book($id) {
        $pet = $this->petModel->getPetById($id);

        if (!$pet || $pet->customer_id != $_SESSION['user_id']) {
            flash('pet_message', 'Bạn không có quyền truy cập sổ sức khỏe của thú cưng này.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/pet');
            exit();
        }

        $logs = $this->healthLogModel->getLogsByPet($id);
        $records = $this->healthRecordModel->getRecordsByPet($id);
        foreach ($records as $record) {
            $record->prescriptions = $this->healthRecordModel->getPrescriptionsByRecord($record->id);
        }
        $vaccinations = $this->vaccinationModel->getVaccinationsByPet($id);
        $milestones = $this->milestoneModel->getMilestonesByPet($id);

        // Đánh giá thể trạng dựa trên giống loài, tuổi và cân nặng thực tế
        $weightStatus = 'normal';
        $targetPet = '';
        if (!empty($pet->species)) {
            $speciesLower = mb_strtolower($pet->species, 'UTF-8');
            if (strpos($speciesLower, 'mèo') !== false || strpos($speciesLower, 'cat') !== false || strpos($speciesLower, 'miu') !== false) {
                $targetPet = 'cat';
            } else {
                $targetPet = 'dog';
            }
        }

        if (!empty($pet->weight) && !empty($pet->age)) {
            if ($targetPet === 'cat') {
                // Tiêu chuẩn trung bình cho Mèo
                if ($pet->age <= 12) {
                    $expectedMin = $pet->age * 0.35; // 3 tháng tuổi tối thiểu ~1.0kg
                    $expectedMax = $pet->age * 0.6;
                } else {
                    $expectedMin = 3.0;
                    $expectedMax = 5.5;
                }
            } else {
                // Tiêu chuẩn trung bình cho Chó
                if ($pet->age <= 12) {
                    $expectedMin = $pet->age * 0.8;
                    $expectedMax = $pet->age * 1.8;
                } else {
                    $expectedMin = 8.0;
                    $expectedMax = 25.0;
                }
            }

            if ($pet->weight < $expectedMin) {
                $weightStatus = 'underweight';
            } elseif ($pet->weight > $expectedMax) {
                $weightStatus = 'overweight';
            }
        }

        $productModel = $this->model('Product');
        $suggestedProducts = [];
        if (!empty($targetPet)) {
            $suggestedProducts = $productModel->getProducts(['target_pet' => $targetPet]);
            
            // Sắp xếp các sản phẩm chuyên dụng lên đầu dựa trên thể trạng
            if ($weightStatus == 'underweight') {
                usort($suggestedProducts, function($a, $b) {
                    $aScore = (strpos(mb_strtolower($a->name . ' ' . $a->description, 'UTF-8'), 'dinh dưỡng') !== false || strpos(mb_strtolower($a->name . ' ' . $a->description, 'UTF-8'), 'con') !== false) ? 1 : 0;
                    $bScore = (strpos(mb_strtolower($b->name . ' ' . $b->description, 'UTF-8'), 'dinh dưỡng') !== false || strpos(mb_strtolower($b->name . ' ' . $b->description, 'UTF-8'), 'con') !== false) ? 1 : 0;
                    return $bScore - $aScore;
                });
            } elseif ($weightStatus == 'overweight') {
                usort($suggestedProducts, function($a, $b) {
                    $aScore = (strpos(mb_strtolower($a->name . ' ' . $a->description, 'UTF-8'), 'giảm cân') !== false || strpos(mb_strtolower($a->name . ' ' . $a->description, 'UTF-8'), 'vòng cổ') !== false) ? 1 : 0;
                    $bScore = (strpos(mb_strtolower($b->name . ' ' . $b->description, 'UTF-8'), 'giảm cân') !== false || strpos(mb_strtolower($b->name . ' ' . $b->description, 'UTF-8'), 'vòng cổ') !== false) ? 1 : 0;
                    return $bScore - $aScore;
                });
            }
        } else {
            $suggestedProducts = $productModel->getProducts();
        }
        $suggestedProducts = array_slice($suggestedProducts, 0, 6);

        $data = [
            'pet' => $pet,
            'logs' => $logs,
            'records' => $records,
            'vaccinations' => $vaccinations,
            'milestones' => $milestones,
            'suggestedProducts' => $suggestedProducts,
            'weight_status' => $weightStatus,
            'seo' => [
                'title' => 'Sổ sức khỏe của ' . $pet->name . ' - PETSHOP'
            ]
        ];

        $this->view('pet/health_book', $data);
    }

    // Thêm nhật ký sức khỏe daily
    public function add_health_log($pet_id) {
        $pet = $this->petModel->getPetById($pet_id);
        
        if (!$pet || $pet->customer_id != $_SESSION['user_id']) {
            flash('pet_message', 'Không tìm thấy thông tin thú cưng.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/pet');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'pet_id' => $pet_id,
                'log_date' => trim($_POST['log_date'] ?? date('Y-m-d')),
                'weight' => !empty($_POST['weight']) ? floatval($_POST['weight']) : null,
                'temperature' => !empty($_POST['temperature']) ? floatval($_POST['temperature']) : null,
                'status' => trim($_POST['status'] ?? 'Bình thường'),
                'symptoms' => trim($_POST['symptoms'] ?? ''),
                'notes' => trim($_POST['notes'] ?? '')
            ];

            if ($this->healthLogModel->addLog($data)) {
                flash('health_log_message', 'Thêm nhật ký sức khỏe thành công!');
                if (in_array($data['status'], ['Mệt mỏi', 'Ốm yếu'])) {
                    $_SESSION['suggest_booking'] = [
                        'pet_id' => $pet_id,
                        'pet_name' => $pet->name,
                        'symptoms' => $data['symptoms']
                    ];
                }
            } else {
                flash('health_log_message', 'Có lỗi xảy ra, vui lòng thử lại.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            }
        }
        
        header('Location: ' . URLROOT . '/pet/health_book/' . $pet_id);
        exit();
    }

    // Xóa nhật ký sức khỏe daily
    public function delete_health_log($id) {
        $log = $this->healthLogModel->getLogById($id);
        if (!$log) {
            header('Location: ' . URLROOT . '/pet');
            exit();
        }

        $pet = $this->petModel->getPetById($log->pet_id);
        if (!$pet || $pet->customer_id != $_SESSION['user_id']) {
            flash('pet_message', 'Bạn không có quyền thực hiện hành động này.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/pet');
            exit();
        }

        if ($this->healthLogModel->deleteLog($id)) {
            flash('health_log_message', 'Đã xóa nhật ký sức khỏe!');
        } else {
            flash('health_log_message', 'Không thể xóa nhật ký sức khỏe.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
        }

        header('Location: ' . URLROOT . '/pet/health_book/' . $pet->id);
        exit();
    }

    // Thêm tiêm phòng mới (Khách hàng chỉ được xem, không được tự ý thêm)
    public function add_vaccination($pet_id) {
        flash('vaccination_message', 'Khách hàng không thể tự thêm lịch sử tiêm phòng. Vui lòng liên hệ bác sĩ hoặc nhân viên phòng khám.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
        header('Location: ' . URLROOT . '/pet/health_book/' . $pet_id . '?tab=vaccinations');
        exit();
    }

    // Xóa tiêm phòng (Khách hàng chỉ được xem, không được tự ý xóa)
    public function delete_vaccination($id) {
        $vaccination = $this->vaccinationModel->getVaccinationById($id);
        $pet_id = $vaccination ? $vaccination->pet_id : '';
        flash('vaccination_message', 'Khách hàng không thể tự xóa lịch sử tiêm phòng. Vui lòng liên hệ bác sĩ hoặc nhân viên phòng khám.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
        if ($pet_id) {
            header('Location: ' . URLROOT . '/pet/health_book/' . $pet_id . '?tab=vaccinations');
        } else {
            header('Location: ' . URLROOT . '/pet');
        }
        exit();
    }

    // Thêm cột mốc kỷ niệm mới
    public function add_milestone($pet_id) {
        $pet = $this->petModel->getPetById($pet_id);
        if (!$pet || $pet->customer_id != $_SESSION['user_id']) {
            flash('pet_message', 'Không tìm thấy thông tin thú cưng.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/pet');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'pet_id' => $pet_id,
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'milestone_date' => trim($_POST['milestone_date'] ?? date('Y-m-d')),
                'image' => ''
            ];

            // Xử lý upload ảnh cột mốc
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadDir = APPROOT . '/../public/images/pets/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $filename = time() . '_' . $_FILES['image']['name'];
                $destination = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $data['image'] = 'pets/' . $filename;
                }
            }

            if (empty($data['title'])) {
                flash('milestone_message', 'Vui lòng nhập tiêu đề cột mốc!', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            } else {
                if ($this->milestoneModel->addMilestone($data)) {
                    flash('milestone_message', 'Thêm cột mốc kỷ niệm thành công!');
                } else {
                    flash('milestone_message', 'Có lỗi xảy ra, vui lòng thử lại.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
                }
            }
        }

        header('Location: ' . URLROOT . '/pet/health_book/' . $pet_id . '?tab=milestones');
        exit();
    }

    // Xóa cột mốc kỷ niệm
    public function delete_milestone($id) {
        $milestone = $this->milestoneModel->getMilestoneById($id);
        if (!$milestone) {
            header('Location: ' . URLROOT . '/pet');
            exit();
        }

        $pet = $this->petModel->getPetById($milestone->pet_id);
        if (!$pet || $pet->customer_id != $_SESSION['user_id']) {
            flash('pet_message', 'Bạn không có quyền thực hiện hành động này.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/pet');
            exit();
        }

        // Xóa file ảnh nếu tồn tại
        if (!empty($milestone->image)) {
            $imageFile = APPROOT . '/../public/images/' . $milestone->image;
            if (file_exists($imageFile)) {
                unlink($imageFile);
            }
        }

        if ($this->milestoneModel->deleteMilestone($id)) {
            flash('milestone_message', 'Đã xóa cột mốc kỷ niệm thành công!');
        } else {
            flash('milestone_message', 'Lỗi không thể xóa.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
        }

        header('Location: ' . URLROOT . '/pet/health_book/' . $milestone->pet_id . '?tab=milestones');
        exit();
    }
}
/ /  
 f o r c e  
 s y n c  
 