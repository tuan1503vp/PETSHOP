<?php
class AiController extends Controller {
    private $aiAnalysisModel;

    public function __construct() {
        redirectManagement();
        $this->aiAnalysisModel = $this->model('AiAnalysis');
    }

    public function index() {
        $data = [
            'symptoms' => '',
            'ai_response' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $symptoms = trim($_POST['symptoms']);
            $data['symptoms'] = $symptoms;

            if (empty($symptoms)) {
                $data['error'] = 'Vui lòng mô tả triệu chứng của thú cưng.';
            } else {
                $response = $this->callOpenRouterApi($symptoms);
                if ($response) {
                    $data['ai_response'] = $response;
                    
                    // Lưu lịch sử chẩn đoán AI nếu user đã đăng nhập
                    if (isset($_SESSION['user_id'])) {
                        try {
                            $this->aiAnalysisModel->saveAnalysis($_SESSION['user_id'], $symptoms, $response);
                        } catch (Exception $e) {
                            // Bỏ qua lỗi lưu DB trên host (không làm văng 500)
                            error_log("Lỗi lưu lịch sử AI: " . $e->getMessage());
                        }
                    }
                } else {
                    $data['error'] = 'Lỗi kết nối với AI. Vui lòng kiểm tra lại API Key hoặc thử lại sau.';
                }
            }
        }

        $this->view('ai/index', $data);
    }

    private function callOpenRouterApi($symptoms) {
        $apiKey = trim(OPENROUTER_API_KEY);
        
        if (empty($apiKey) || strpos($apiKey, 'sk-or') !== 0) {
            return "*(Đây là phản hồi mẫu do chưa cấu hình API Key)*\n\nDựa trên triệu chứng **\"" . htmlspecialchars($symptoms) . "\"**, có thể thú cưng của bạn đang gặp vấn đề về tiêu hóa hoặc thay đổi thời tiết. \n\n**Lời khuyên:** Hãy theo dõi thêm trong 24h. Cung cấp đủ nước uống. Nếu tình trạng không cải thiện, vui lòng đặt lịch khám ngay lập tức.";
        }

        $url = 'https://openrouter.ai/api/v1/chat/completions';

        $payload = [
            "model" => "openrouter/free", // Tự động chọn mô hình AI miễn phí hoạt động ổn định nhất
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Bạn là bác sĩ thú y chuyên nghiệp tại PetShop. Hãy phân tích triệu chứng khách hàng cung cấp và đưa ra phản hồi bằng tiếng Việt. Bố cục gồm: 1. Phân tích triệu chứng, 2. Nguyên nhân có thể, 3. Mức độ khẩn cấp, 4. Lời khuyên chăm sóc tại nhà. Luôn nhắc nhở đây chỉ là tư vấn tham khảo."
                ],
                [
                    "role" => "user",
                    "content" => "Triệu chứng thú cưng của tôi: " . $symptoms
                ]
            ],
            "temperature" => 0.7
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45); // Set 45s to avoid host PHP max_execution_time timeout killing the page
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
            'HTTP-Referer: http://localhost/PETSHOP', // Yêu cầu bởi OpenRouter
            'X-Title: PetShop AI Doctor'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$response) {
            file_put_contents(APPROOT . '/../ai_debug.log', "CURL Error (OpenRouter): " . $err . "\nResponse: " . $response, FILE_APPEND);
            return false;
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['choices'][0]['message']['content'])) {
                return $responseData['choices'][0]['message']['content'];
            }
            file_put_contents(APPROOT . '/../ai_debug.log', "OpenRouter Error: " . json_encode($responseData), FILE_APPEND);
            return false;
        }
    }

    public function chat() {
        header('Content-Type: application/json');
        
        $json = file_get_contents('php://input');
        $input = json_decode($json, true);
        
        if (!$input || empty($input['message'])) {
            echo json_encode(['success' => false, 'message' => 'Nội dung trống.']);
            return;
        }
        
        $message = trim($input['message']);
        $reply = $this->callOpenRouterApiForChat($message);
        
        if ($reply) {
            echo json_encode(['success' => true, 'reply' => $reply]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi kết nối AI.']);
        }
    }

    private function callOpenRouterApiForChat($message) {
        $apiKey = trim(OPENROUTER_API_KEY);
        
        if (empty($apiKey) || strpos($apiKey, 'sk-or') !== 0) {
            $msg_lower = mb_strtolower($message, 'UTF-8');
            if (strpos($msg_lower, 'giá') !== false || strpos($msg_lower, 'bao nhiêu') !== false) {
                return "Dạ, PetShop cung cấp các dịch vụ chăm sóc thú cưng chuyên nghiệp với mức giá rất hợp lý ạ. Dịch vụ Spa chăm sóc toàn diện chỉ từ 80.000đ, Cắt tỉa lông nghệ thuật từ 120.000đ và Lưu chuồng khách sạn từ 50.000đ/ngày. Quý khách vui lòng truy cập mục **Dịch vụ** hoặc liên hệ Hotline để bộ phận CSKH tư vấn chi tiết hơn ạ.";
            } elseif (strpos($msg_lower, 'tên') !== false || strpos($msg_lower, 'ai') !== false || strpos($msg_lower, 'là ai') !== false) {
                return "Dạ, kính chào Quý khách! Tôi là **Pawsy** - Chuyên viên tư vấn AI trực tuyến của hệ thống PetShop. Tôi luôn sẵn sàng 24/7 để hỗ trợ Quý khách giải đáp các thông tin về sản phẩm, dịch vụ và tư vấn chăm sóc sức khỏe cho thú cưng ạ.";
            } else {
                return "Dạ, PetShop đã tiếp nhận thông tin từ Quý khách: \"" . htmlspecialchars($message) . "\". Do hệ thống tư vấn tự động đang trong quá trình nâng cấp định kỳ, Quý khách vui lòng liên hệ trực tiếp qua Hotline **0947.647.052** hoặc xem thêm thông tin tại mục **Cửa hàng** / **Dịch vụ**. Xin chân thành cảm ơn Quý khách!";
            }
        }

        // Fetch real-time database context for AI
        $db = new Database();
        
        $storeInfo = "PetShop - Hệ thống cửa hàng và dịch vụ chăm sóc thú cưng chuyên nghiệp. Hotline: 0947647052. Địa chỉ: Số 3, Vũ Công Đán, P.Tứ Minh, Hải Phòng. STK Chuyển khoản: Vietcombank 1047429167 - NGUYEN MINH TUAN.";
        
        $db->query("SELECT name, price FROM services");
        $services = $db->resultSet();
        $serviceText = "Dịch vụ: " . implode(", ", array_map(function($s) { return $s->name . " (" . number_format($s->price, 0, ',', '.') . "đ)"; }, $services));

        $db->query("SELECT membership_level, discount_percent, benefit_text FROM membership_benefits");
        $memberships = $db->resultSet();
        $memberText = "Hạng hội viên: " . implode("; ", array_map(function($m) { return $m->membership_level . " (giảm " . $m->discount_percent . "%, " . $m->benefit_text . ")"; }, $memberships));

        $db->query("SELECT name FROM categories");
        $categories = $db->resultSet();
        $categoryText = "Danh mục đồ thú cưng: " . implode(", ", array_map(function($c) { return $c->name; }, $categories));

        $db->query("SELECT name, price FROM products LIMIT 15");
        $products = $db->resultSet();
        $productText = "Một số sản phẩm nổi bật: " . implode(", ", array_map(function($p) { return $p->name . " (" . number_format($p->price, 0, ',', '.') . "đ)"; }, $products));

        $systemPrompt = "Bạn là chuyên viên tư vấn AI trực tuyến tên Pawsy của hệ thống PetShop. Nhiệm vụ của bạn là giải đáp thắc mắc của khách hàng một cách chuyên nghiệp, lịch sự và tận tâm.\n"
                      . "THÔNG TIN CỬA HÀNG:\n$storeInfo\n$serviceText\n$memberText\n$categoryText\n$productText\n\n"
                      . "QUY TẮC GIAO TIẾP (QUAN TRỌNG):\n- Luôn xưng hô là 'Dạ', gọi khách hàng là 'Quý khách' hoặc 'Anh/Chị', gọi động vật là 'thú cưng' hoặc 'bé'.\n- Giọng điệu chuyên nghiệp, chuẩn mực của một thương hiệu chăm sóc thú cưng cao cấp 5 sao.\n- Trả lời đúng trọng tâm, rõ ràng, mạch lạc, có xuống dòng cho dễ nhìn.\n- Khéo léo điều hướng khách hàng sử dụng các Dịch vụ hoặc mua sắm tại Cửa hàng.\n- Nếu thú cưng có dấu hiệu bệnh nặng, hãy khuyên Quý khách đưa bé đến cơ sở thú y gần nhất hoặc gọi hotline.\n- Tuyệt đối không tự bịa ra thông tin giá cả hoặc dịch vụ không có thật.";

        $url = 'https://openrouter.ai/api/v1/chat/completions';

        $payload = [
            "model" => "openrouter/free",
            "messages" => [
                [
                    "role" => "system",
                    "content" => $systemPrompt
                ],
                [
                    "role" => "user",
                    "content" => $message
                ]
            ],
            "temperature" => 0.7
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
            'HTTP-Referer: http://localhost/PETSHOP',
            'X-Title: PetShop AI Pawsy'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$response) {
            return false;
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['choices'][0]['message']['content'])) {
                return $responseData['choices'][0]['message']['content'];
            }
            return false;
        }
    }
}
