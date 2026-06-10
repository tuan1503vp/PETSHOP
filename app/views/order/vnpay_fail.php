<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
$responseCode = $data['response_code'] ?? '99';
$message      = $data['message'] ?? 'Giao dịch thất bại.';
$order_id     = $data['order_id'] ?? null;
$paddedId     = $order_id ? str_pad($order_id, 6, '0', STR_PAD_LEFT) : '------';
$isCancelled  = ($responseCode === '24');
?>

<div class="bg-gradient-to-br from-red-50/40 via-white to-orange-50/20 min-h-[calc(100vh-64px)] py-16 flex items-center justify-center px-4">
    <div class="max-w-lg w-full">

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden text-center">

            <!-- Header -->
            <div class="bg-gradient-to-r from-<?php echo $isCancelled ? 'orange-400 to-amber-500' : 'red-500 to-rose-600'; ?> p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="mx-auto w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4 border-2 border-white/30">
                        <i class="fa-solid <?php echo $isCancelled ? 'fa-ban' : 'fa-circle-xmark'; ?> text-4xl"></i>
                    </div>
                    <h1 class="text-2xl font-black mb-1">
                        <?php echo $isCancelled ? 'Thanh Toán Đã Huỷ' : 'Thanh Toán Thất Bại'; ?>
                    </h1>
                    <p class="text-<?php echo $isCancelled ? 'orange' : 'red'; ?>-100 text-sm">
                        <?php echo $isCancelled ? 'Bạn đã huỷ quá trình thanh toán' : 'Có lỗi xảy ra trong quá trình thanh toán'; ?>
                    </p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-8">
                <!-- Order ID -->
                <?php if($order_id): ?>
                <div class="mb-5">
                    <p class="text-xs text-gray-400 mb-1">Mã đơn hàng</p>
                    <p class="text-2xl font-black text-gray-400">#<?php echo $paddedId; ?></p>
                    <p class="text-xs text-gray-400 mt-1">(Đơn hàng chưa được thanh toán — sẽ tự huỷ sau 24h)</p>
                </div>
                <?php endif; ?>

                <!-- Error message -->
                <div class="bg-<?php echo $isCancelled ? 'orange' : 'red'; ?>-50 border border-<?php echo $isCancelled ? 'orange' : 'red'; ?>-200 rounded-2xl px-5 py-4 mb-6 text-left flex gap-3 items-start">
                    <i class="fa-solid fa-circle-exclamation text-<?php echo $isCancelled ? 'orange' : 'red'; ?>-500 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-sm font-bold text-<?php echo $isCancelled ? 'orange' : 'red'; ?>-700 mb-0.5">Lý do</p>
                        <p class="text-sm text-<?php echo $isCancelled ? 'orange' : 'red'; ?>-600"><?php echo htmlspecialchars($message); ?></p>
                    </div>
                </div>

                <!-- Options -->
                <p class="text-sm text-gray-600 mb-5 font-medium">Bạn có thể thử lại hoặc chọn phương thức thanh toán khác:</p>

                <div class="space-y-3">
                    <!-- Thử lại -->
                    <a href="<?php echo URLROOT; ?>/cart/checkout"
                       class="w-full inline-flex justify-center items-center gap-2.5 px-4 py-3.5 rounded-2xl bg-gradient-to-r from-primary to-indigo-600 text-white font-black shadow-lg shadow-primary/25 hover:-translate-y-0.5 transition-all">
                        <i class="fa-solid fa-rotate-right"></i> Thử lại thanh toán
                    </a>

                    <!-- Xem giỏ hàng -->
                    <a href="<?php echo URLROOT; ?>/cart"
                       class="w-full inline-flex justify-center items-center gap-2.5 px-4 py-3.5 rounded-2xl border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 transition">
                        <i class="fa-solid fa-cart-shopping"></i> Quay lại giỏ hàng
                    </a>

                    <!-- Tiếp tục mua sắm -->
                    <a href="<?php echo URLROOT; ?>/product"
                       class="w-full inline-flex justify-center items-center gap-2.5 px-4 py-3 rounded-2xl text-gray-500 font-medium hover:text-primary transition text-sm">
                        <i class="fa-solid fa-store"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>

            <!-- Footer: hotline -->
            <div class="px-8 pb-6">
                <div class="bg-gray-50 rounded-2xl px-4 py-3 flex items-center justify-center gap-2 text-sm text-gray-600">
                    <i class="fa-solid fa-headset text-primary"></i>
                    Cần hỗ trợ? Gọi <a href="tel:19008888" class="font-black text-primary hover:underline ml-1">1900 8888</a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
