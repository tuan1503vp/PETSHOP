<?php
class AiController extends Controller {
    private $aiAnalysisModel;

    public function __construct() {
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
                        $this->aiAnalysisModel->saveAnalysis($_SESSION['user_id'], $symptoms, $response);
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
            "model" => "google/gemini-2.0-flash-001", // Model cực nhanh và thông minh qua OpenRouter
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
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
                return "Dạ thưa Sen, bảng giá dịch vụ PetShop cực kỳ ưu đãi ạ! Tắm Spa chỉ từ 80k, Cắt tỉa lông chuyên nghiệp chỉ từ 120k, và Trông giữ khách sạn thú cưng chỉ từ 50k/ngày thôi ạ. Sen có thể vào mục **Dịch vụ** hoặc click vào nút đặt lịch để xem chi tiết và book giờ đẹp cho bé nha! 🐾";
            } elseif (strpos($msg_lower, 'tên') !== false || strpos($msg_lower, 'ai') !== false || strpos($msg_lower, 'là ai') !== false) {
                return "Dạ! Em là **Pawsy** 🐾 - Trợ lý ảo cực kỳ đáng yêu của PetShop đây ạ. Em ở đây 24/7 để hỗ trợ tư vấn chăm sóc sức khỏe, giải đáp thắc mắc về sản phẩm/dịch vụ của PetShop cho Sen nhé!";
            } else {
                return "Dạ Sen ơi, Pawsy đã nhận được câu hỏi: \"" . htmlspecialchars($message) . "\" rồi ạ! Do đây là môi trường thử nghiệm chưa cấu hình API Key, em khuyên Sen nên tham khảo mục **Dịch vụ** hoặc **Cửa hàng** trên thanh điều hướng để xem chi tiết, hoặc chat trực tiếp qua Hotline 0947647052 nhé! Em chúc bé cưng luôn khỏe mạnh ạ! 🐾🐱";
            }
        }

        // Fetch real-time database context for AI
        $db = new Database();
        
        $storeInfo = "PetShop - Cửa hàng và Dịch vụ thú cưng. Hotline: 0947647052. Địa chỉ: Số 3, Vũ Công Đán, P.Tứ Minh, Hải Phòng. STK Chuyển khoản: Vietcombank 1047429167 - NGUYEN MINH TUAN.";
        
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
        $productText = "Một số sản phẩm: " . implode(", ", array_map(function($p) { return $p->name . " (" . number_format($p->price, 0, ',', '.') . "đ)"; }, $products));

        $systemPrompt = "Bạn là trợ lý ảo AI tên Pawsy của hệ thống PetShop. Bạn có nhiệm vụ giải đáp mọi thắc mắc của khách hàng dựa trên dữ liệu thật của cửa hàng.\n"
                      . "THÔNG TIN CỬA HÀNG:\n$storeInfo\n$serviceText\n$memberText\n$categoryText\n$productText\n\n"
                      . "QUY TẮC PHẢN HỒI:\n- Trả lời ngắn gọn, thân thiện, lễ phép (dùng từ 'dạ', 'thưa sen', 'bé cưng', 'sen').\n- Khuyên khách hàng vào mục Dịch vụ để đặt lịch, hoặc mục Cửa hàng để mua sắm.\n- Nếu hỏi bệnh nặng, khuyên đặt lịch khám bác sĩ thú y.\n- Chỉ cung cấp giá/thông tin nếu có trong dữ liệu, nếu không biết hãy nói không rõ và nhắc liên hệ hotline.";

        $url = 'https://openrouter.ai/api/v1/chat/completions';

        $payload = [
            "model" => "google/gemini-2.0-flash-001",
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
