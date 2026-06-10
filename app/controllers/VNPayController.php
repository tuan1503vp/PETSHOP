<?php
/**
 * VNPayController – Xử lý thanh toán trực tuyến qua cổng VNPay
 *
 * Luồng hoạt động:
 *  1. POST /vnpay/create  → Tạo đơn hàng tạm, build URL VNPay → redirect sang cổng VNPay
 *  2. GET  /vnpay/return  → VNPay redirect về sau khi người dùng thanh toán (hiển thị kết quả)
 *  3. GET  /vnpay/ipn     → VNPay gọi server-to-server (IPN) để xác nhận giao dịch
 */
class VNPayController extends Controller {

    // ──────────────────────────────────────────────────────────
    // CẤU HÌNH VNPAY SANDBOX
    // Đăng ký tài khoản merchant tại: https://sandbox.vnpayment.vn/devreg/
    // Sau khi đăng ký → thay thế các giá trị bên dưới bằng thông tin thực
    // ──────────────────────────────────────────────────────────
    const VNP_TMNCODE    = 'DEMOV210';                          // Mã Terminal
    const VNP_HASHSECRET = 'RAOEXHYVSDDIIENYWSLDIIZTANLLFQFR'; // Chuỗi bí mật
    const VNP_URL        = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    const VNP_API_URL    = 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction';
    const VNP_LOCALE     = 'vn';
    const VNP_CURR_CODE  = 'VND';
    // ──────────────────────────────────────────────────────────

    private $orderModel;

    public function __construct() {
        $this->orderModel = $this->model('Order');
    }

    /**
     * Bước 1: Nhận dữ liệu form checkout → tạo đơn hàng "pending" → chuyển hướng sang VNPay
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isLoggedIn()) {
            header('Location: ' . URLROOT . '/cart');
            return;
        }

        // ── Validate giỏ hàng ──────────────────────────────
        if (empty($_SESSION['cart'])) {
            header('Location: ' . URLROOT . '/product');
            return;
        }

        // ── Tính tổng tiền ─────────────────────────────────
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        // ── Tạo đơn hàng với payment_method = 'vnpay' ─────
        $orderData = [
            'customer_id'      => $_SESSION['user_id'],
            'total_amount'     => $total_amount,
            'payment_method'   => 'vnpay',
            'shipping_name'    => trim($_POST['fullname'] ?? ''),
            'shipping_phone'   => trim($_POST['phone'] ?? ''),
            'shipping_address' => trim($_POST['address'] ?? ''),
            'status'           => 'pending',  // chờ VNPay xác nhận
            'order_type'       => 'online',
        ];

        $order_id = $this->orderModel->createOrder($orderData);
        if (!$order_id) {
            die('Có lỗi khi tạo đơn hàng. Vui lòng thử lại.');
        }

        // ── Lưu chi tiết sản phẩm vào đơn hàng ────────────
        foreach ($_SESSION['cart'] as $item) {
            $this->orderModel->addOrderItem($order_id, $item['id'], $item['quantity'], $item['price']);
        }

        // ── Lưu order_id vào session để xác minh khi VNPay return ──
        $_SESSION['vnpay_order_id'] = $order_id;

        // ── Build tham số VNPay ────────────────────────────
        $vnp_TxnRef    = $order_id . '_' . time();   // mã giao dịch duy nhất
        $vnp_OrderInfo = 'PETSHOP Thanh toan don hang #' . str_pad($order_id, 6, '0', STR_PAD_LEFT);
        $vnp_ReturnUrl = URLROOT . '/vnpay/return';
        $vnp_IpnUrl    = URLROOT . '/vnpay/ipn';
        $vnp_Amount    = $total_amount * 100; // VNPay tính theo đơn vị 1/100 đồng
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
            'vnp_ReturnUrl'  => $vnp_ReturnUrl,
            'vnp_TxnRef'     => $vnp_TxnRef,
            'vnp_ExpireDate' => date('YmdHis', strtotime('+15 minutes')),
        ];

        // Sắp xếp theo thứ tự alphabet (bắt buộc của VNPay)
        ksort($inputData);

        // Build query string và tạo chữ ký HMAC-SHA512
        $query     = http_build_query($inputData, '', '&');
        $hmac      = hash_hmac('sha512', $query, self::VNP_HASHSECRET);
        $paymentUrl = self::VNP_URL . '?' . $query . '&vnp_SecureHash=' . $hmac;

        // ── Chuyển hướng sang trang thanh toán VNPay ───────
        header('Location: ' . $paymentUrl);
        exit;
    }

    /**
     * Bước 2: VNPay redirect về sau khi người dùng hoàn thành / huỷ thanh toán
     */
    public function return() {
        if (!isLoggedIn()) {
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }

        $vnpData       = $_GET;
        $vnp_SecureHash = $vnpData['vnp_SecureHash'] ?? '';
        unset($vnpData['vnp_SecureHash'], $vnpData['vnp_SecureHashType']);

        ksort($vnpData);
        $query    = http_build_query($vnpData, '', '&');
        $hashCheck = hash_hmac('sha512', $query, self::VNP_HASHSECRET);

        $isValid    = hash_equals($hashCheck, $vnp_SecureHash);
        $isSuccess  = ($vnpData['vnp_ResponseCode'] ?? '') === '00';

        // Tách order_id từ vnp_TxnRef (format: orderId_timestamp)
        $txnRef  = $vnpData['vnp_TxnRef'] ?? '';
        $order_id = (int) explode('_', $txnRef)[0];

        if ($isValid && $isSuccess && $order_id) {
            // ── Cập nhật đơn hàng thành "completed" ─────────
            $this->orderModel->updateStatus($order_id, 'completed');

            // ── Xoá giỏ hàng ─────────────────────────────────
            unset($_SESSION['cart'], $_SESSION['vnpay_order_id']);

            // ── Cập nhật hạng thành viên ──────────────────────
            $userModel = $this->model('User');
            $userModel->updateMembershipTier($_SESSION['user_id']);

            // ── Gửi email xác nhận ────────────────────────────
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
        } else {
            // Thanh toán thất bại / bị huỷ → hiển thị trang thất bại
            $responseCode = $vnpData['vnp_ResponseCode'] ?? 'XX';
            $data = [
                'order_id'      => $order_id,
                'response_code' => $responseCode,
                'message'       => $this->getVNPayMessage($responseCode),
            ];
            $this->view('order/vnpay_fail', $data);
        }
    }

    /**
     * Bước 3 (tuỳ chọn): IPN – VNPay gọi server-to-server để xác nhận
     * Đây là bước quan trọng trong môi trường production
     */
    public function ipn() {
        header('Content-Type: application/json');

        $vnpData        = $_GET;
        $vnp_SecureHash = $vnpData['vnp_SecureHash'] ?? '';
        unset($vnpData['vnp_SecureHash'], $vnpData['vnp_SecureHashType']);

        ksort($vnpData);
        $query     = http_build_query($vnpData, '', '&');
        $hashCheck = hash_hmac('sha512', $query, self::VNP_HASHSECRET);

        if (!hash_equals($hashCheck, $vnp_SecureHash)) {
            echo json_encode(['RspCode' => '97', 'Message' => 'Invalid Checksum']);
            return;
        }

        $txnRef   = $vnpData['vnp_TxnRef'] ?? '';
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

        if (($vnpData['vnp_ResponseCode'] ?? '') === '00') {
            $this->orderModel->updateStatus($order_id, 'completed');
            echo json_encode(['RspCode' => '00', 'Message' => 'Confirm Success']);
        } else {
            $this->orderModel->updateStatus($order_id, 'cancelled');
            echo json_encode(['RspCode' => '00', 'Message' => 'Confirm Success']);
        }
    }

    /**
     * Trả về thông điệp lỗi theo mã VNPay
     */
    private function getVNPayMessage($code) {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
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
        return $messages[$code] ?? ('Giao dịch thất bại (Mã lỗi: ' . $code . ').');
    }
}
