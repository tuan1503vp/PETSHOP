<?php
class PetController extends Controller {
    private $petModel;
    private $healthLogModel;
    private $healthRecordModel;

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

    // Sổ sức khỏe thú cưng (Theo dõi daily + Khám y bạ)
    public function health_book($id) {
        $pet = $this->petModel->getPetById($id);

        if (!$pet || $pet->customer_id != $_SESSION['user_id']) {
            flash('pet_message', 'Bạn không có quyền truy cập sổ sức khỏe của thú cưng này.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            header('Location: ' . URLROOT . '/pet');
            exit();
        }

        $logs = $this->healthLogModel->getLogsByPet($id);
        $records = $this->healthRecordModel->getRecordsByPet($id);

        $data = [
            'pet' => $pet,
            'logs' => $logs,
            'records' => $records,
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
}
