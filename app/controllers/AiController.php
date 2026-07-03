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
            "models" => [
                "meta-llama/llama-3.3-70b-instruct:free",
                "nousresearch/hermes-3-llama-3.1-405b:free",
                "google/gemma-4-31b-it:free"
            ], // Tự động dự phòng tối đa 3 models
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
            error_log("CURL Error (OpenRouter): " . $err);
            return false;
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['choices'][0]['message']['content'])) {
                return $responseData['choices'][0]['message']['content'];
            }
            error_log("OpenRouter Error: " . json_encode($responseData));
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
        $msg_lower = mb_strtolower(trim($message), 'UTF-8');
        
        $db = new Database();
        
        // Hàm loại bỏ dấu tiếng Việt để dễ so sánh
        $msg_no_accent = $this->removeVietnameseAccents($msg_lower);

        // Xin chào
        if (strpos($msg_lower, 'chào') !== false || strpos($msg_lower, 'hello') !== false || strpos($msg_lower, 'hi') !== false || strpos($msg_no_accent, 'chao') !== false) {
            return "Dạ Pawsy xin chào Quý khách! Em là Trợ lý ảo AI nội bộ của PetShop. Em có thể giúp gì cho Quý khách về việc tìm kiếm sản phẩm, dịch vụ chăm sóc, hoặc đặt lịch khám cho các bé thú cưng ạ?";
        }
        
        // Hỏi địa chỉ / liên hệ
        if (strpos($msg_lower, 'địa chỉ') !== false || strpos($msg_lower, 'đại chỉ') !== false || strpos($msg_lower, 'đại chỉu') !== false || strpos($msg_no_accent, 'dia chi') !== false || strpos($msg_no_accent, 'dai chi') !== false || strpos($msg_lower, 'liên hệ') !== false || strpos($msg_no_accent, 'lien he') !== false || strpos($msg_lower, 'số điện thoại') !== false || strpos($msg_no_accent, 'so dien thoai') !== false || strpos($msg_lower, 'sdt') !== false || strpos($msg_lower, 'ở đâu') !== false || strpos($msg_no_accent, 'o dau') !== false) {
            return "Dạ PetShop hiện đang có địa chỉ tại:\n📍 **Số 3, Vũ Công Đán, P.Tứ Minh, Hải Phòng**\n📞 **Hotline:** 0947647052\n📧 **Email:** nmtvp11223311@gmail.com\n⏰ **Giờ mở cửa:** 8:00 - 21:00 mỗi ngày.\n\n👉 Quý khách có thể xem bản đồ và để lại lời nhắn tại [Trang Liên Hệ](" . URLROOT . "/contact) ạ!";
        }
        
        // Hỏi về bác sĩ / y tế / bệnh
        if (strpos($msg_lower, 'bệnh') !== false || strpos($msg_no_accent, 'benh') !== false || strpos($msg_lower, 'ốm') !== false || strpos($msg_no_accent, 'om') !== false || strpos($msg_lower, 'bác sĩ') !== false || strpos($msg_lower, 'bác sỹ') !== false || strpos($msg_no_accent, 'bac si') !== false || strpos($msg_no_accent, 'bac sy') !== false || strpos($msg_lower, 'khám') !== false || strpos($msg_no_accent, 'kham') !== false || strpos($msg_lower, 'tiêm') !== false || strpos($msg_no_accent, 'tiem') !== false) {
            return "Dạ đối với các vấn đề về y tế và khám chữa bệnh, Quý khách nên đăng ký lịch hẹn để Bác sĩ Thú y kiểm tra trực tiếp cho bé ạ.\n\n👉 Quý khách có thể xem và đặt lịch tại đây: [Dịch vụ Khám & Chữa bệnh](" . URLROOT . "/service/book/5)\n👉 Hoặc sử dụng tính năng [Bác sĩ AI](" . URLROOT . "/ai) để được tư vấn phác đồ phòng bệnh chuyên sâu nhé!";
        }
        
        // Hỏi về dịch vụ spa / tắm
        if (strpos($msg_lower, 'tắm') !== false || strpos($msg_no_accent, 'tam') !== false || strpos($msg_lower, 'spa') !== false || strpos($msg_lower, 'cắt tỉa') !== false || strpos($msg_no_accent, 'cat tia') !== false || strpos($msg_lower, 'vệ sinh') !== false || strpos($msg_no_accent, 've sinh') !== false || strpos($msg_lower, 'lông') !== false || strpos($msg_no_accent, 'long') !== false) {
            return "Dạ PetShop có cung cấp dịch vụ Spa tắm gội và cắt tỉa lông chuyên nghiệp giúp các bé luôn thơm tho, sạch sẽ, lông bồng bềnh ạ.\n\n👉 Mời Quý khách tham khảo và đặt lịch ngay tại đây: [Dịch vụ Chăm sóc Spa](" . URLROOT . "/service/book/6)";
        }
        
        // Hỏi về vắc xin
        if (strpos($msg_lower, 'vắc xin') !== false || strpos($msg_no_accent, 'vac xin') !== false || strpos($msg_lower, 'vaccine') !== false || strpos($msg_lower, 'vắc-xin') !== false || strpos($msg_lower, 'tiêm phòng') !== false || strpos($msg_no_accent, 'tiem phong') !== false) {
            $db->query("SELECT id, name, price FROM products WHERE category_id = 13 LIMIT 5");
            $products = $db->resultSet();
            if(count($products) > 0) {
                $reply = "Dạ hiện tại kho của PetShop đang có sẵn các loại Vắc-xin chuẩn Y khoa cực kỳ chất lượng sau đây ạ:\n\n";
                foreach($products as $p) {
                    $reply .= "- [" . $p->name . "](" . URLROOT . "/product/show/" . $p->id . ") - Giá: " . number_format($p->price, 0, ',', '.') . "đ\n";
                }
                $reply .= "\n👉 Quý khách có thể xem thêm tại [Kho hàng Sản phẩm](" . URLROOT . "/shop) hoặc liên hệ Bác sĩ Thú y để được tư vấn lịch tiêm chi tiết nhất nhé!";
                return $reply;
            }
        }

        // Hỏi về thức ăn / hạt / pate
        if (strpos($msg_lower, 'thức ăn') !== false || strpos($msg_no_accent, 'thuc an') !== false || strpos($msg_lower, 'hạt') !== false || strpos($msg_no_accent, 'hat') !== false || strpos($msg_lower, 'pate') !== false || strpos($msg_lower, 'đồ ăn') !== false || strpos($msg_no_accent, 'do an') !== false) {
            // Lấy 4 sản phẩm ngẫu nhiên thuộc danh mục thức ăn chó / mèo (1, 2)
            $db->query("SELECT id, name, price FROM products WHERE category_id IN (1, 2) ORDER BY RAND() LIMIT 4");
            $products = $db->resultSet();
            
            $reply = "Dạ hệ thống cửa hàng em đang có rất nhiều loại thức ăn thơm ngon và giàu dinh dưỡng. Em xin gợi ý ngẫu nhiên một vài sản phẩm bán chạy nhất hiện tại:\n\n";
            foreach($products as $p) {
                $reply .= "- [" . $p->name . "](" . URLROOT . "/product/show/" . $p->id . ") - Giá: " . number_format($p->price, 0, ',', '.') . "đ\n";
            }
            $reply .= "\n👉 Quý khách có thể xem toàn bộ danh mục đồ ăn tại [Cửa hàng](" . URLROOT . "/shop) ạ.";
            return $reply;
        }

        // Hỏi về đơn hàng / giao hàng
        if (strpos($msg_lower, 'đơn hàng') !== false || strpos($msg_no_accent, 'don hang') !== false || strpos($msg_lower, 'giao hàng') !== false || strpos($msg_no_accent, 'giao hang') !== false || strpos($msg_lower, 'vận chuyển') !== false || strpos($msg_lower, 'mua hàng') !== false) {
            return "Dạ để theo dõi tình trạng đơn hàng hoặc lịch sử mua sắm, Quý khách vui lòng truy cập vào phần **[Đơn hàng của tôi](" . URLROOT . "/order/history)** trên hệ thống ạ. PetShop cam kết giao hàng hỏa tốc trong nội thành và ship COD toàn quốc nhé!";
        }

        // Hỏi về hội viên / tích điểm / giảm giá / vip
        if (strpos($msg_lower, 'hội viên') !== false || strpos($msg_no_accent, 'hoi vien') !== false || strpos($msg_lower, 'tích điểm') !== false || strpos($msg_no_accent, 'tich diem') !== false || strpos($msg_lower, 'giảm giá') !== false || strpos($msg_no_accent, 'giam gia') !== false || strpos($msg_lower, 'vip') !== false) {
            $db->query("SELECT membership_level, discount_percent, benefit_text FROM membership_benefits ORDER BY discount_percent ASC");
            $memberships = $db->resultSet();
            if(count($memberships) > 0) {
                $reply = "Dạ PetShop có chương trình **Hội viên Thân thiết** với vô vàn đặc quyền ưu đãi dành cho Quý khách ạ:\n\n";
                foreach($memberships as $m) {
                    $reply .= "- 🌟 **Hạng " . $m->membership_level . ":** Giảm " . $m->discount_percent . "% toàn bộ hóa đơn (" . $m->benefit_text . ")\n";
                }
                $reply .= "\n👉 Hệ thống sẽ tự động tích điểm mỗi khi Quý khách mua sắm hoặc sử dụng dịch vụ ạ!";
                return $reply;
            }
        }

        // Hỏi về hồ sơ thú cưng
        if (strpos($msg_lower, 'hồ sơ') !== false || strpos($msg_no_accent, 'ho so') !== false || strpos($msg_lower, 'thú cưng') !== false || strpos($msg_no_accent, 'thu cung') !== false || strpos($msg_lower, 'sổ khám') !== false) {
            return "Dạ PetShop có hệ thống **Sổ sức khỏe điện tử** dành riêng cho từng bé thú cưng. Ở đó Quý khách có thể theo dõi cân nặng, nhắc lịch tiêm, và chat riêng với AI Dinh dưỡng.\n\n👉 Mời Quý khách truy cập tại đây: **[Hồ sơ Thú cưng](" . URLROOT . "/pet)**";
        }
        
        // Tìm kiếm thông minh qua Database (Danh mục, Sản phẩm, Dịch vụ)
        $keywords = explode(" ", $msg_lower);
        $searchWord = "";
        // Bỏ qua các từ nối tiếng Việt
        $stopwords = ['cho', 'và', 'của', 'có', 'không', 'em', 'anh', 'chị', 'bạn', 'mua', 'bán', 'tìm', 'ở', 'đâu', 'như', 'thế', 'nào'];
        foreach($keywords as $k) {
            if(mb_strlen($k, 'UTF-8') > 2 && !in_array($k, $stopwords)) {
                $searchWord = $k;
                break;
            }
        }
        
        if($searchWord) {
            // Kiểm tra xem từ khóa có khớp với tên danh mục nào không
            $db->query("SELECT id, name FROM categories WHERE name LIKE :word LIMIT 1");
            $db->bind(':word', "%$searchWord%");
            $categoryMatch = $db->single();
            
            if ($categoryMatch) {
                $db->query("SELECT id, name, price FROM products WHERE category_id = :cid LIMIT 4");
                $db->bind(':cid', $categoryMatch->id);
                $p_results = $db->resultSet();
                
                if(count($p_results) > 0) {
                    $reply = "Dạ em thấy Quý khách đang quan tâm đến danh mục **" . $categoryMatch->name . "**. Đây là một số sản phẩm nổi bật:\n\n";
                    foreach($p_results as $p) {
                        $reply .= "- 🛍️ [" . $p->name . "](" . URLROOT . "/product/show/" . $p->id . ") - Giá: " . number_format($p->price, 0, ',', '.') . "đ\n";
                    }
                    $reply .= "\n👉 Bấm vào từng sản phẩm để xem chi tiết hoặc ghé thăm [Cửa hàng](" . URLROOT . "/shop) ạ.";
                    return $reply;
                }
            }

            // Nếu không khớp danh mục, tìm trực tiếp sản phẩm & dịch vụ
            $db->query("SELECT id, name, price FROM products WHERE name LIKE :word LIMIT 3");
            $db->bind(':word', "%$searchWord%");
            $p_results = $db->resultSet();
            
            $db->query("SELECT id, name, price FROM services WHERE name LIKE :word LIMIT 2");
            $db->bind(':word', "%$searchWord%");
            $s_results = $db->resultSet();
            
            if(count($p_results) > 0 || count($s_results) > 0) {
                $reply = "Dạ dựa theo từ khóa '**" . htmlspecialchars($searchWord) . "**', hệ thống AI nội bộ đã trích xuất được một số kết quả phù hợp đang có tại cửa hàng:\n\n";
                foreach($p_results as $p) {
                    $reply .= "- 🛍️ Sản phẩm: [" . $p->name . "](" . URLROOT . "/product/show/" . $p->id . ") - Giá: " . number_format($p->price, 0, ',', '.') . "đ\n";
                }
                foreach($s_results as $s) {
                    $reply .= "- 🗓️ Dịch vụ: [" . $s->name . "](" . URLROOT . "/service/book/" . $s->id . ") - Giá: " . number_format($s->price, 0, ',', '.') . "đ\n";
                }
                return $reply;
            }
        }
        
        // Fallback cuối cùng
        return "Dạ Pawsy xin lỗi, hình như em chưa hiểu rõ ý của Quý khách lắm ạ. Quý khách có thể vui lòng diễn đạt lại câu hỏi cụ thể hơn giúp em được không ạ?\n\n👉 Hoặc để được giải đáp chi tiết và nhanh chóng nhất, Quý khách vui lòng liên hệ trực tiếp với nhân viên cửa hàng qua các kênh sau nhé:\n💬 **Zalo/Hotline:** 0947647052\n📧 **Email:** nmtvp11223311@gmail.com\n🌐 Hoặc xem thêm thông tin tại **[Trang Liên Hệ](" . URLROOT . "/contact)**.\n\nCác bạn tư vấn viên sẽ phản hồi Quý khách ngay lập tức ạ!";
    }

    // Hàm phụ trợ: Bỏ dấu tiếng Việt
    private function removeVietnameseAccents($str) {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }

    // Chat tư vấn chuyên sâu về sức khỏe thú cưng cụ thể
    public function pet_chat() {
        header('Content-Type: application/json');
        
        $json = file_get_contents('php://input');
        $input = json_decode($json, true);
        
        if (!$input || empty($input['message']) || empty($input['pet_id'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không đầy đủ.']);
            return;
        }
        
        $petId = intval($input['pet_id']);
        $message = trim($input['message']);
        
        // Lấy thông tin thú cưng
        $petModel = $this->model('Pet');
        $pet = $petModel->getPetById($petId);
        
        if (!$pet || $pet->customer_id != ($_SESSION['user_id'] ?? 0)) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập thông tin thú cưng này.']);
            return;
        }
        
        // Lấy 5 nhật ký sức khỏe gần nhất
        $healthLogModel = $this->model('PetHealthLog');
        $logs = $healthLogModel->getLogsByPet($petId);
        // Lấy tối đa 5 logs
        $logs = array_slice($logs, 0, 5);
        
        $reply = $this->callOpenRouterApiForPetChat($pet, $logs, $message);
        
        if ($reply) {
            echo json_encode(['success' => true, 'reply' => $reply]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi kết nối AI.']);
        }
    }

    private function callOpenRouterApiForPetChat($pet, $logs, $message) {
        $apiKey = trim(OPENROUTER_API_KEY);
        
        // Fetch store products and services
        $db = new Database();
        $db->query("SELECT p.id, p.name, p.price, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id");
        $products = $db->resultSet();
        $productsList = "";
        foreach ($products as $p) {
            $productsList .= "- [" . $p->name . "](" . URLROOT . "/product/show/" . $p->id . ") - Giá: " . number_format($p->price, 0, ',', '.') . "đ (Danh mục: " . $p->cat_name . ")\n";
        }

        $db->query("SELECT s.id, s.name, s.price, c.name as cat_name FROM services s LEFT JOIN categories c ON s.category_id = c.id");
        $services = $db->resultSet();
        $servicesList = "";
        foreach ($services as $s) {
            $servicesList .= "- [" . $s->name . "](" . URLROOT . "/service/book/" . $s->id . ") - Giá: " . number_format($s->price, 0, ',', '.') . "đ (Danh mục: " . $s->cat_name . ")\n";
        }
        
        // Định dạng nhật ký sức khỏe gần nhất
        $logSummary = "";
        if (!empty($logs)) {
            foreach ($logs as $index => $log) {
                $logSummary .= "- Ngày " . date('d/m/Y', strtotime($log->log_date)) 
                             . ": Cân nặng: " . ($log->weight ? $log->weight . "kg" : "chưa ghi nhận")
                             . ", Thân nhiệt: " . ($log->temperature ? $log->temperature . "°C" : "chưa ghi nhận")
                             . ", Trạng thái: " . $log->status
                             . ($log->symptoms ? ", Triệu chứng: " . $log->symptoms : "")
                             . ($log->notes ? ", Ghi chú: " . $log->notes : "") . "\n";
            }
        } else {
            $logSummary = "Chưa có nhật ký sức khỏe nào được ghi nhận.";
        }

        $petContext = "THÔNG TIN THÚ CƯNG HIỆN TẠI:\n"
                    . "- Tên: " . $pet->name . "\n"
                    . "- Loài: " . $pet->species . "\n"
                    . "- Giống: " . ($pet->breed ? $pet->breed : "Chưa rõ") . "\n"
                    . "- Tuổi: " . $pet->age . " tháng tuổi\n"
                    . "- Giới tính: " . ($pet->gender == 'male' ? 'Đực' : ($pet->gender == 'female' ? 'Cái' : 'Chưa rõ')) . "\n"
                    . "- Cân nặng hiện tại: " . ($pet->weight ? $pet->weight . "kg" : "Chưa rõ") . "\n"
                    . "- Nhật ký sức khỏe 5 ngày gần nhất:\n" . $logSummary;
        
        $petSpecies = $pet->species ?? '';
        
        if (empty($apiKey) || strpos($apiKey, 'sk-or') !== 0) {
            return "Dạ Pawsy xin lỗi, hiện tại tính năng Trợ lý AI nâng cao đang tạm bảo trì do chưa cấu hình Mã khóa API hợp lệ (OpenRouter API Key). Quý khách vui lòng liên hệ Ban quản trị để cấu hình lại tính năng này ạ!";
        }

        $systemPrompt = "Bạn là Pawsy - Trợ lý dinh dưỡng AI chuyên nghiệp tại PetShop.\n"
                      . "Nhiệm vụ chính của bạn là tư vấn dinh dưỡng, chế độ ăn uống, và hướng dẫn chăm sóc thể trạng cho bé thú cưng cụ thể của khách hàng dựa trên dữ liệu được cung cấp.\n\n"
                      . $petContext . "\n\n"
                      . "DANH SÁCH SẢN PHẨM & DỊCH VỤ CỦA CỬA HÀNG:\n"
                      . "Khi tư vấn thức ăn hay dịch vụ chăm sóc, hãy đề xuất trực tiếp các sản phẩm/dịch vụ phù hợp dưới đây kèm link Markdown chính xác (không tự bịa link):\n"
                      . "SẢN PHẨM CỦA CỬA HÀNG:\n" . $productsList . "\n"
                      . "DỊCH VỤ CỦA CỬA HÀNG:\n" . $servicesList . "\n\n"
                      . "QUY TẮC PHẢN HỒI QUAN TRỌNG:\n"
                      . "- Nếu khách hàng hỏi về bệnh tật, triệu chứng lạ hoặc các vấn đề sức khỏe cần chẩn đoán chuyên sâu (ví dụ: sốt, nôn ói, tiêu chảy, lờ đờ, ghẻ ngứa, bỏ ăn nặng,...), hãy lịch sự trả lời rằng bạn chỉ là trợ lý tư vấn dinh dưỡng, không có chuyên môn chẩn đoán lâm sàng. Sau đó, hướng dẫn họ click vào link [Bác sĩ AI](" . URLROOT . "/ai) để phân tích triệu chứng chuyên sâu.\n"
                      . "- Luôn xưng hô là 'Dạ Pawsy nghe', gọi khách hàng là 'Quý khách' hoặc 'Anh/Chị', gọi thú cưng là 'bé'.\n"
                      . "- Nhắc đến tên của bé thú cưng một cách thân thiện trong câu trả lời.\n"
                      . "- Đề xuất đúng sản phẩm/thức ăn cho chó nếu bé là chó, và cho mèo nếu bé là mèo. Không nhầm lẫn giữa thức ăn chó và mèo.\n"
                      . "- Trình bày câu trả lời gọn gàng, đẹp mắt bằng Markdown, xuống dòng rõ ràng, dễ đọc.";

        $url = 'https://openrouter.ai/api/v1/chat/completions';
        $payload = [
            "models" => [
                "meta-llama/llama-3.3-70b-instruct:free",
                "nousresearch/hermes-3-llama-3.1-405b:free",
                "google/gemma-4-31b-it:free"
            ],
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
            'HTTP-Referer: https://petshop.id.vn',
            'X-Title: PetShop AI Pet Care'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$response) {
            return "Dạ Pawsy xin lỗi, hệ thống máy chủ AI bên ngoài đang không phản hồi (Lỗi mạng hoặc Hosting chặn kết nối). Xin vui lòng thử lại sau ạ!";
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['choices'][0]['message']['content'])) {
                return $responseData['choices'][0]['message']['content'];
            } else if (isset($responseData['error'])) {
                return "Dạ Pawsy xin lỗi, máy chủ AI từ chối kết nối (Mã khóa API có thể đã hết hạn hoặc hết tín dụng). Thông báo từ máy chủ: " . htmlspecialchars($responseData['error']['message'] ?? 'Lỗi không xác định');
            }
            return "Dạ Pawsy xin lỗi, có lỗi không xác định khi nhận dữ liệu từ AI. Xin vui lòng thử lại!";
        }
    }

    public function getVaccineSchedule() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
            $pet_id = isset($_POST['pet_id']) ? (int)$_POST['pet_id'] : 0;
            $petModel = $this->model('Pet');
            $pet = $petModel->getPetById($pet_id);
            if (!$pet) {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy thú cưng.']);
                return;
            }

            // AI Simulator (Rule-based)
            $species = mb_strtolower(trim($pet->species), 'UTF-8');
            $html = "";

            if (strpos($species, 'chó') !== false || strpos($species, 'cún') !== false || strpos($species, 'dog') !== false) {
                $html .= "### Phác đồ tiêm phòng cho Chó (" . htmlspecialchars($pet->name) . ")\n\n";
                $html .= "**1. Mũi 1 (Lúc 6 - 8 tuần tuổi)**\n";
                $html .= "- **Vắc-xin:** 5 bệnh hoặc 7 bệnh (Care, Parvo, Viêm gan, Ho cũi chó...)\n\n";
                $html .= "**2. Mũi 2 (Lúc 9 - 11 tuần tuổi)**\n";
                $html .= "- **Vắc-xin:** 5 bệnh hoặc 7 bệnh (Nhắc lại lần 1)\n\n";
                $html .= "**3. Mũi 3 (Lúc 12 - 14 tuần tuổi)**\n";
                $html .= "- **Vắc-xin:** 5 bệnh hoặc 7 bệnh + Vắc-xin Dại (Rabies)\n\n";
                $html .= "**4. Tái chủng hàng năm**\n";
                $html .= "- Nhắc lại 1 mũi đa bệnh và 1 mũi Dại mỗi năm.\n";
            } elseif (strpos($species, 'mèo') !== false || strpos($species, 'cat') !== false) {
                $html .= "### Phác đồ tiêm phòng cho Mèo (" . htmlspecialchars($pet->name) . ")\n\n";
                $html .= "**1. Mũi 1 (Lúc 6 - 8 tuần tuổi)**\n";
                $html .= "- **Vắc-xin:** 4 bệnh (Giảm bạch cầu, Viêm mũi khí quản, Calicivirus...)\n\n";
                $html .= "**2. Mũi 2 (Lúc 9 - 11 tuần tuổi)**\n";
                $html .= "- **Vắc-xin:** 4 bệnh (Nhắc lại lần 1)\n\n";
                $html .= "**3. Mũi 3 (Lúc 12 - 14 tuần tuổi)**\n";
                $html .= "- **Vắc-xin:** 4 bệnh + Vắc-xin Dại (Rabies)\n\n";
                $html .= "**4. Tái chủng hàng năm**\n";
                $html .= "- Tiêm nhắc lại 1 mũi 4 bệnh và 1 mũi Dại mỗi năm.\n";
            } elseif (strpos($species, 'thỏ') !== false || strpos($species, 'rabbit') !== false) {
                $html .= "### Phác đồ y tế cho Thỏ (" . htmlspecialchars($pet->name) . ")\n\n";
                $html .= "**1. Vắc-xin xuất huyết truyền nhiễm (RHDV)**\n";
                $html .= "- **Thời điểm:** Từ 5-7 tuần tuổi. Nhắc lại hàng năm.\n\n";
                $html .= "**2. Xổ giun và ký sinh trùng**\n";
                $html .= "- Lịch định kỳ 3-6 tháng/lần tùy môi trường sống.\n";
            } elseif (strpos($species, 'chuột') !== false || strpos($species, 'hamster') !== false || strpos($species, 'bọ') !== false) {
                $html .= "### Tư vấn sức khỏe cho Gặm nhấm (" . htmlspecialchars($pet->name) . ")\n\n";
                $html .= "**Lưu ý y tế:**\n";
                $html .= "- Các loài gặm nhấm nhỏ như Hamster, Bọ Ú (Guinea Pig) hiện tại **không có vắc-xin tiêm phòng thương mại**.\n";
                $html .= "- **Phòng bệnh:** Quan trọng nhất là giữ lồng nuôi sạch sẽ, khô ráo, tránh gió lùa và cho ăn thức ăn chuyên dụng.\n";
                $html .= "- **Tẩy giun:** Có thể tẩy giun bằng thuốc nước định kỳ theo chỉ định bác sĩ thú y.\n";
            } elseif (strpos($species, 'chim') !== false || strpos($species, 'vẹt') !== false || strpos($species, 'bird') !== false) {
                $html .= "### Phác đồ y tế cho Chim/Vẹt (" . htmlspecialchars($pet->name) . ")\n\n";
                $html .= "**Lưu ý y tế:**\n";
                $html .= "- Các dòng chim cảnh/vẹt thường không tiêm vắc-xin định kỳ như chó mèo (trừ quy mô trang trại lớn tiêm NewCastle/Cúm).\n";
                $html .= "- **Chăm sóc cốt lõi:** Bổ sung Vitamin (A, D3, Canxi) vào nước uống. Tẩy giun sán định kỳ 6 tháng/lần.\n";
                $html .= "- **Kiểm tra:** Thường xuyên kiểm tra phân và đường hô hấp.\n";
            } elseif (strpos($species, 'lợn') !== false || strpos($species, 'heo') !== false || strpos($species, 'pig') !== false) {
                $html .= "### Phác đồ tiêm phòng cho Heo cảnh (" . htmlspecialchars($pet->name) . ")\n\n";
                $html .= "**1. Từ 14 - 21 ngày tuổi:**\n";
                $html .= "- Tiêm vắc-xin Suyễn heo và Mycoplasma.\n\n";
                $html .= "**2. Từ 30 - 45 ngày tuổi:**\n";
                $html .= "- Vắc-xin Tai xanh (PRRS) và Dịch tả heo (CSF).\n\n";
                $html .= "**3. Từ 60 ngày tuổi:**\n";
                $html .= "- Vắc-xin Lở mồm long móng (FMD).\n";
            } else {
                $html .= "### Phác đồ y tế cho " . htmlspecialchars($pet->species) . " (" . htmlspecialchars($pet->name) . ")\n\n";
                $html .= "Loài **" . htmlspecialchars($pet->species) . "** là một thú cưng đặc biệt. Đối với dòng thú cưng độc lạ (Exotic Pets) như bò sát, lưỡng cư, hay các loài ít phổ biến:\n\n";
                $html .= "- Thường **không có vắc-xin bắt buộc**.\n";
                $html .= "- Chế độ phòng bệnh chủ yếu dựa vào: Kiểm soát nhiệt độ/độ ẩm chuồng nuôi, chiếu đèn UV (với bò sát), và chế độ dinh dưỡng đặc thù.\n";
                $html .= "- Xin vui lòng liên hệ Bác sĩ thú y chuyên khoa Exotic để có phác đồ cá nhân hóa nhất!\n";
            }

            $html .= "\n> **Lưu ý từ Bác sĩ AI (Hệ thống nội bộ):** Phác đồ trên mang tính chất tham khảo chuẩn. Vui lòng kiểm tra thể trạng thực tế của bé " . htmlspecialchars($pet->name) . " trước khi thực hiện bất kỳ can thiệp y tế nào.";

            echo json_encode(['status' => 'success', 'data' => $html]);
            return;
        }
    }
}
