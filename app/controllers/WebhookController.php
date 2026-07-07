<?php
class WebhookController extends Controller {
    public function index() {
        // Only accept POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            return;
        }

        // Get the JSON payload
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
            return;
        }

        // Example structure for SePay Webhook
        // $data['amountIn'] = 500000;
        // $data['transactionContent'] = 'Tuan chuyen tien cho PETSHOP 000123';
        
        $amountIn = isset($data['amountIn']) ? (float)$data['amountIn'] : 0;
        $content = isset($data['transactionContent']) ? strtoupper(trim($data['transactionContent'])) : '';

        if ($amountIn <= 0 || empty($content)) {
            echo json_encode(['success' => false, 'message' => 'Missing data']);
            return;
        }

        // Extract Order ID using regex. Pattern: PETSHOP followed by spaces/zeros then digits
        // Example: PETSHOP 000123, PETSHOP00123, PETSHOP123
        if (preg_match('/(?:PETSHOP|PETSH|PS)\s*0*(\d+)/i', $content, $matches)) {
            $order_id = (int)$matches[1];

            $orderModel = $this->model('Order');
            $order = $orderModel->getOrderById($order_id);

            // Verify order exists, belongs to transfer method, and is pending
            if ($order && $order->payment_method === 'transfer' && $order->status === 'pending') {
                
                $totalRequired = (float)$order->total_amount;
                
                // Approve order with exact amount received
                $admin_note = '';
                if ($amountIn < $totalRequired) {
                    $admin_note = "Webhook tự động: Khách chuyển thiếu " . number_format($totalRequired - $amountIn) . "đ. Cần thu thêm.";
                } elseif ($amountIn > $totalRequired) {
                    $admin_note = "Webhook tự động: Khách chuyển thừa " . number_format($amountIn - $totalRequired) . "đ.";
                } else {
                    $admin_note = "Webhook tự động: Đã thanh toán đủ qua ngân hàng.";
                }

                $orderModel->approveOrder($order_id, $amountIn, $admin_note);

                // Notify User
                $notificationModel = $this->model('Notification');
                $notificationModel->add([
                    'user_id' => $order->customer_id,
                    'title' => 'Nhận thanh toán thành công',
                    'message' => "Chúng tôi đã nhận được khoản thanh toán " . number_format($amountIn) . "đ cho đơn hàng #ORD-" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . ". Đơn hàng đang được chuẩn bị giao cho bạn.",
                    'type' => 'order'
                ]);

                // Hoàn thành đơn thì trừ kho
                $items = $orderModel->getOrderItems($order_id);
                $productModel = $this->model('Product');
                foreach ($items as $item) {
                    $productModel->decreaseStock($item->product_id, $item->quantity);
                }

                // Cập nhật membership không cần ở trạng thái shipping mà đợi lúc Admin chuyển sang completed
                // Admin xác nhận giao xong -> completed, lên hạng.

                echo json_encode(['success' => true, 'message' => 'Order approved automatically', 'order_id' => $order_id]);
                return;
            } else {
                echo json_encode(['success' => false, 'message' => 'Order not found, not transfer, or not pending']);
                return;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No matching order syntax found in content']);
            return;
        }
    }
}
