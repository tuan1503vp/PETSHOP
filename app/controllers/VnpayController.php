<?php
/**
 * VNPayController – Xử lý thanh toán trực tuyến qua cổng VNPay
 *
 * Luồng hoạt động:
 *  1. POST /vnpay/create  → Tạo đơn hàng tạm, build URL VNPay → redirect sang cổng VNPay
 *  2. GET  /vnpay/return  → VNPay redirect về sau khi người dùng thanh toán (hiển thị kết quả)
 *  3. GET  /vnpay/ipn     → VNPay gọi server-to-server (IPN) để xác nhận giao dịch
 */
class VnpayController extends Controller {

    // ══════════════════════════════════════════════════════════
    // CẤU HÌNH VNPAY SANDBOX (Thông tin chính thức từ VNPay)
    // Merchant Admin: https://sandbox.vnpayment.vn/merchantv2/
    // Tài liệu: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html
    // ⚠️  Chỉ dùng môi trường TEST – KHÔNG dùng cho giao dịch thật
    // ══════════════════════════════════════════════════════════
    const VNP_TMNCODE    = '4VCHK58N';                          // Terminal ID (Mã Website)
    const VNP_HASHSECRET = 'AB22YEY4K4530OHAEA5HTKOSKCY2JS9T'; // Secret Key (Chuỗi bí mật)
    const VNP_URL        = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    const VNP_API_URL    = 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction';
    const VNP_LOCALE     = 'vn';
    const VNP_CURR_CODE  = 'VND';
    // ══════════════════════════════════════════════════════════

    private $orderModel;

    public function __construct() {
        $this->orderModel = $this->model('Order');
    }

    // ─────────────────────────────────────────────────────────
    // HELPER: Build chuỗi hash đúng chuẩn VNPay
    // Chỉ lấy tham số bắt đầu bằng "vnp_", loại bỏ SecureHash,
    // sort alphabet, rồi join theo urlencode($key)=urlencode($value)
    // ─────────────────────────────────────────────────────────
    private function buildHashData(array $data): string {
        // 1. Chỉ giữ tham số vnp_* (tránh bị ô nhiễm bởi tham số router như url=vnpay/return)
        $filtered = [];
        foreach ($data as $key => $value) {
            if (substr($key, 0, 4) === 'vnp_') {
                $filtered[$key] = $value;
            }
        }

        // 2. Loại bỏ SecureHash và SecureHashType
        unset($filtered['vnp_SecureHash'], $filtered['vnp_SecureHashType']);

        // 3. Sắp xếp theo alphabet (bắt buộc của VNPay)
        ksort($filtered);

        // 4. Build chuỗi theo đúng cách của VNPay: urlencode(key)=urlencode(value)&...
        $hashParts = [];
        foreach ($filtered as $key => $value) {
            $hashParts[] = urlencode($key) . '=' . urlencode($value);
        }

        return implode('&', $hashParts);
    }

    // ─────────────────────────────────────────────────────────
    // HELPER: Tạo chữ ký HMAC-SHA512
    // ─────────────────────────────────────────────────────────
    private function generateHmac(string $data): string {
        return hash_hmac('sha512', $data, self::VNP_HASHSECRET);
    }

    // ─────────────────────────────────────────────────────────
    // HELPER: Xác minh chữ ký từ VNPay trả về
    // ─────────────────────────────────────────────────────────
    private function verifyHash(array $vnpParams): bool {
        $receivedHash = $vnpParams['vnp_SecureHash'] ?? '';
        $hashData     = $this->buildHashData($vnpParams);
        $computedHash = $this->generateHmac($hashData);
        return hash_equals($computedHash, $receivedHash);
    }

    /**
     * Bước 1: Nhận dữ liệu form checkout → tạo đơn hàng "pending" → chuyển hướng sang VNPay
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isLoggedIn()) {
            header('Location: ' . URLROOT . '/cart');
            return;
        }

        if (empty($_SESSION['cart'])) {
            header('Location: ' . URLROOT . '/product');
            return;
        }

        // Tính tổng tiền
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        // Tạo đơn hàng với status = 'pending'
        $orderData = [
            'customer_id'      => $_SESSION['user_id'],
            'total_amount'     => $total_amount,
            'payment_method'   => 'vnpay',
            'shipping_name'    => trim($_POST['fullname'] ?? ''),
            'shipping_phone'   => trim($_POST['phone'] ?? ''),
            'shipping_address' => trim($_POST['address'] ?? ''),
            'status'           => 'pending',
            'order_type'       => 'online',
        ];

        $order_id = $this->orderModel->createOrder($orderData);
        if (!$order_id) {
            die('Có lỗi khi tạo đơn hàng. Vui lòng thử lại.');
        }

        // Lưu chi tiết sản phẩm
        foreach ($_SESSION['cart'] as $item) {
            $this->orderModel->addOrderItem($order_id, $item['id'], $item['quantity'], $item['price']);
        }

        // Lưu order_id vào session
        $_SESSION['vnpay_order_id'] = $order_id;

        // Build tham số gửi sang VNPay
        // Lưu ý: vnp_OrderInfo KHÔNG dùng ký tự đặc biệt (#, &, =, ...)
        $vnp_TxnRef    = $order_id . '_' . time();
        $vnp_OrderInfo = 'PETSHOP don hang ' . str_pad($order_id, 6, '0', STR_PAD_LEFT);
        $vnp_Amount    = (int)($total_amount * 100); // VNPay tính x100
        $vnp_IpAddr    = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        $inputData = [
            'vnp_Version'    => '2.1.0',
            'vnp_TmnCode'    => self::VNP_TMNCODE,
            'vnp_Amount'     => $vnp_Amount,
            'vnp_Command'    => 'pay',
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_CurrCode'   => self::VNP_CURR_CODE,
            'vnp_IpAddr'     => $vnp_IpAddr,
            'vnp_Locale'     => self::VNP_LOCALE,
            'vnp_OrderInfo'  => $vnp_OrderInfo,
            'vnp_OrderType'  => 'other',
            'vnp_ReturnUrl'  => URLROOT . '/vnpay/return',
            'vnp_TxnRef'     => $vnp_TxnRef,
            'vnp_ExpireDate' => date('YmdHis', strtotime('+15 minutes')),
        ];

        // Build hash string đúng chuẩn VNPay rồi tạo SecureHash
        ksort($inputData);
        $hashParts = [];
        foreach ($inputData as $key => $value) {
            $hashParts[] = urlencode($key) . '=' . urlencode($value);
        }
        $hashData   = implode('&', $hashParts);
        $secureHash = $this->generateHmac($hashData);

        // Build URL thanh toán
        $paymentUrl = self::VNP_URL . '?' . http_build_query($inputData, '', '&') . '&vnp_SecureHash=' . $secureHash;

        header('Location: ' . $paymentUrl);
        exit;
    }

    /**
     * Bước 2: VNPay redirect về sau khi người dùng hoàn thành / huỷ thanh toán
     *
     * QUAN TRỌNG: $_GET có thể chứa tham số "url" từ router .htaccess (url=vnpay/return)
     * → phải lọc chỉ lấy tham số bắt đầu bằng "vnp_" trước khi tính hash
     */
    public function return() {
        if (!isLoggedIn()) {
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }

        // Xác minh chữ ký (tự động lọc tham số vnp_* bên trong verifyHash)
        $isValid   = $this->verifyHash($_GET);
        $isSuccess = (($_GET['vnp_ResponseCode'] ?? '') === '00');

        // Tách order_id từ vnp_TxnRef (format: orderId_timestamp)
        $txnRef   = $_GET['vnp_TxnRef'] ?? '';
        $order_id = (int) explode('_', $txnRef)[0];

        // Ghi log debug tìm lỗi hash
        $receivedHash = $_GET['vnp_SecureHash'] ?? '';
        $hashData     = $this->buildHashData($_GET);
        $computedHash = $this->generateHmac($hashData);
        $logMsg = date('Y-m-d H:i:s') . " - return() - Order ID: " . $order_id . " (TxnRef: " . $txnRef . ")\n"
                . "Is Valid: " . ($isValid ? 'TRUE' : 'FALSE') . " | Is Success: " . ($isSuccess ? 'TRUE' : 'FALSE') . "\n"
                . "Received Hash: " . $receivedHash . "\n"
                . "Computed Hash: " . $computedHash . "\n"
                . "Hash Data: " . $hashData . "\n"
                . "GET Params: " . json_encode($_GET, JSON_UNESCAPED_UNICODE) . "\n\n";
        file_put_contents(dirname(APPROOT) . '/vnpay_debug.log', $logMsg, FILE_APPEND);

        if ($isValid && $isSuccess && $order_id) {
            // Thanh toán thành công → chuyển sang 'shipping' (đã thanh toán, đang chuẩn bị giao hàng)
            // Admin sẽ chuyển sang 'completed' khi giao hàng xong và xác nhận
            $this->orderModel->updateStatus($order_id, 'shipping');

            // Xoá giỏ hàng
            unset($_SESSION['cart'], $_SESSION['vnpay_order_id']);

            // Cập nhật hạng thành viên
            $userModel = $this->model('User');
            $userModel->updateMembershipTier($_SESSION['user_id']);

            // Gửi email xác nhận
            try {
                $mailer = new Mailer();
                $order  = $this->orderModel->getOrderById($order_id);
                $mailer->sendOrderConfirmation(
                    $_SESSION['user_email'] ?? '',
                    $_SESSION['user_name']  ?? 'Khách hàng',
                    $order_id,
                    $order->total_amount ?? 0,
                    'vnpay'
                );
            } catch (Exception $e) {
                // Không dừng luồng nếu email lỗi
            }

            header('Location: ' . URLROOT . '/order/success/' . $order_id);
            exit;

        } else {
            // Thanh toán thất bại / bị huỷ
            $responseCode = $_GET['vnp_ResponseCode'] ?? 'XX';
            $data = [
                'order_id'      => $order_id ?: null,
                'response_code' => $responseCode,
                'message'       => $this->getVNPayMessage($responseCode),
            ];
            $this->view('order/vnpay_fail', $data);
        }
    }

    /**
     * Bước 3: IPN – VNPay gọi server-to-server để xác nhận giao dịch
     * Cần khai báo URL này trên Merchant Admin VNPay
     */
    public function ipn() {
        header('Content-Type: application/json');

        $isValid  = $this->verifyHash($_GET);

        // Ghi log debug tìm lỗi IPN
        $txnRef   = $_GET['vnp_TxnRef'] ?? '';
        $order_id = (int) explode('_', $txnRef)[0];
        $receivedHash = $_GET['vnp_SecureHash'] ?? '';
        $hashData     = $this->buildHashData($_GET);
        $computedHash = $this->generateHmac($hashData);
        $logMsg = date('Y-m-d H:i:s') . " - ipn() - Order ID: " . $order_id . " (TxnRef: " . $txnRef . ")\n"
                . "Is Valid: " . ($isValid ? 'TRUE' : 'FALSE') . "\n"
                . "Received Hash: " . $receivedHash . "\n"
                . "Computed Hash: " . $computedHash . "\n"
                . "Hash Data: " . $hashData . "\n"
                . "GET Params: " . json_encode($_GET, JSON_UNESCAPED_UNICODE) . "\n\n";
        file_put_contents(dirname(APPROOT) . '/vnpay_debug.log', $logMsg, FILE_APPEND);

        if (!$isValid) {
            echo json_encode(['RspCode' => '97', 'Message' => 'Invalid Checksum']);
            return;
        }

        $txnRef   = $_GET['vnp_TxnRef'] ?? '';
        $order_id = (int) explode('_', $txnRef)[0];
        $order    = $this->orderModel->getOrderById($order_id);

        if (!$order) {
            echo json_encode(['RspCode' => '01', 'Message' => 'Order not found']);
            return;
        }

        if ($order->status === 'completed') {
            echo json_encode(['RspCode' => '02', 'Message' => 'Order already confirmed']);
            return;
        }

        $responseCode = $_GET['vnp_ResponseCode'] ?? '';
        if ($responseCode === '00') {
            // IPN xác nhận thành công → shipping (đang giao hàng)
            $this->orderModel->updateStatus($order_id, 'shipping');
        } else {
            $this->orderModel->updateStatus($order_id, 'cancelled');
        }

        echo json_encode(['RspCode' => '00', 'Message' => 'Confirm Success']);
    }

    /**
     * Trả về thông điệp lỗi theo mã VNPay
     */
    private function getVNPayMessage(string $code): string {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công nhưng giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
            '09' => 'Thẻ/Tài khoản chưa đăng ký dịch vụ InternetBanking.',
            '10' => 'Xác thực thông tin thẻ/tài khoản không đúng quá 3 lần.',
            '11' => 'Đã hết hạn chờ thanh toán. Vui lòng thực hiện lại giao dịch.',
            '12' => 'Thẻ/Tài khoản bị khoá.',
            '13' => 'Sai mật khẩu OTP. Vui lòng thực hiện lại giao dịch.',
            '24' => 'Bạn đã huỷ giao dịch thanh toán.',
            '51' => 'Tài khoản không đủ số dư để thực hiện giao dịch.',
            '65' => 'Tài khoản đã vượt quá hạn mức giao dịch trong ngày.',
            '75' => 'Ngân hàng thanh toán đang bảo trì.',
            '79' => 'Nhập sai mật khẩu thanh toán quá số lần quy định.',
            '99' => 'Lỗi không xác định. Vui lòng thử lại hoặc liên hệ hỗ trợ.',
        ];
        return $messages[$code] ?? 'Giao dịch thất bại (Mã lỗi: ' . $code . ').';
    }
}
