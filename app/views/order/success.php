<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
// ===== CẤU HÌNH NGÂN HÀNG =====
define('BANK_CODE',    'VCB');
define('BANK_NAME',    'Vietcombank (VCB)');
define('BANK_ACCOUNT', '1047429167');
define('BANK_OWNER',   'NGUYEN MINH TUAN');
define('BANK_BRANCH',  'PGD Yên Mỹ');
// ================================

$orderModel = new Order();
$order      = $orderModel->getOrderById($data['order_id']);
$isTransfer = ($order && $order->payment_method === 'transfer');
$isVNPay    = ($order && $order->payment_method === 'vnpay');
$isPaid     = ($order && in_array($order->status, ['completed', 'shipping']));
$orderId    = $data['order_id'];
$paddedId   = str_pad($orderId, 6, '0', STR_PAD_LEFT);
$transfer_content = 'PETSHOP ' . $paddedId;

$qr_url = '';
if ($order && $isTransfer) {
    $qr_url = 'https://img.vietqr.io/image/' . BANK_CODE . '-' . BANK_ACCOUNT . '-compact2.jpg'
            . '?amount=' . urlencode($order->total_amount)
            . '&addInfo=' . urlencode($transfer_content)
            . '&accountName=' . urlencode(BANK_OWNER);
}
?>

<div class="bg-gradient-to-br from-indigo-50/40 via-white to-pink-50/30 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800 min-h-[calc(100vh-64px)] py-16 flex items-center justify-center px-4">
<div class="max-w-2xl w-full">

<?php if($isTransfer && !$isPaid): ?>
<!-- ==================== CHUYỂN KHOẢN – CHỜ THANH TOÁN ==================== -->
<div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-800 overflow-hidden" id="pendingCard">

    <!-- Header – đang chờ -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-center text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="relative z-10">
            <div class="mx-auto w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4 border-2 border-white/30">
                <i class="fa-solid fa-qrcode text-4xl"></i>
            </div>
            <h2 class="text-2xl font-black mb-1">Hoàn tất chuyển khoản</h2>
            <p class="text-blue-100 text-sm">Quét QR để thanh toán – trang tự động cập nhật</p>
        </div>
    </div>

    <!-- Order ID + status badge -->
    <div class="px-6 pt-5 pb-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-400 mb-0.5">Mã đơn hàng</p>
            <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">#<?php echo $paddedId; ?></span>
        </div>
        <!-- Pulse status badge -->
        <div id="statusBadge" class="flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-black">
            <span class="w-2.5 h-2.5 bg-amber-400 rounded-full animate-pulse inline-block"></span>
            Chờ thanh toán
        </div>
    </div>

    <!-- QR + Bank info -->
    <div class="p-6">
        <div class="flex flex-col sm:flex-row gap-6 items-center mb-5">
            <!-- QR Code -->
            <div class="flex-shrink-0 text-center">
                <div class="bg-white p-3 rounded-2xl shadow-lg inline-block border-2 border-green-200">
                    <img src="<?php echo $qr_url; ?>"
                         alt="QR VietQR"
                         class="w-44 h-44 rounded-xl"
                         onerror="this.src='https://placehold.co/176x176?text=QR'">
                </div>
                <p class="text-xs text-green-700 font-bold mt-2">📱 Quét bằng app ngân hàng</p>
            </div>

            <!-- Bank info -->
            <div class="flex-1 w-full space-y-2">
                <?php
                $fields = [
                    ['Ngân hàng',       BANK_NAME,          false, false, null],
                    ['Chủ tài khoản',   BANK_OWNER,         false, false, null],
                    ['Số tài khoản',    BANK_ACCOUNT,       true,  false, null],
                    ['Số tiền',         number_format($order->total_amount,0,',','.').'đ', true, false, (int)$order->total_amount],
                    ['Nội dung CK',     $transfer_content,  true,  true,  null],
                ];
                foreach($fields as [$label, $val, $canCopy, $highlight, $rawVal]):
                    $copyVal = ($rawVal !== null) ? $rawVal : $val;
                ?>
                <div class="flex items-center justify-between bg-<?php echo $highlight ? 'green' : 'gray'; ?>-50 rounded-xl px-4 py-2.5 border border-<?php echo $highlight ? 'green-200' : 'gray-100'; ?>">
                    <div>
                        <p class="text-[10px] font-bold uppercase text-gray-400"><?php echo $label; ?></p>
                        <p class="text-sm font-black <?php echo $highlight ? 'text-green-700' : 'text-gray-800'; ?>"><?php echo $val; ?></p>
                    </div>
                    <?php if($canCopy): ?>
                    <button type="button" onclick="copyText('<?php echo htmlspecialchars($copyVal, ENT_QUOTES); ?>')"
                            class="ml-2 flex-shrink-0 text-xs font-bold text-green-600 hover:text-green-800 flex items-center gap-1 transition">
                        <i class="fa-regular fa-copy"></i> Copy
                    </button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Lưu ý -->
        <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3 flex gap-3 items-start mb-5">
            <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5 flex-shrink-0"></i>
            <p class="text-xs text-amber-700 leading-relaxed">
                Vui lòng ghi đúng <strong>nội dung chuyển khoản</strong>. Đơn hàng được xác nhận tự động sau khi chúng tôi nhận được giao dịch
                (thường trong <strong>5–15 phút</strong>). Hotline hỗ trợ: <strong>1900 8888</strong>.
            </p>
        </div>

        <!-- Progress indicator -->
        <div id="pollingInfo" class="flex items-center gap-3 text-xs text-gray-400 bg-gray-50 dark:bg-slate-800 rounded-2xl px-4 py-3 border border-gray-100 dark:border-slate-700 mb-5">
            <svg class="animate-spin w-4 h-4 text-primary flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 22 6.477 22 12h-4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span id="pollingText">Đang theo dõi giao dịch...</span>
        </div>

        <!-- Upload Receipt -->
        <div class="border border-dashed border-gray-300 dark:border-slate-600 rounded-2xl p-4 text-center">
            <?php if(!empty($order->receipt_image)): ?>
                <div class="flex items-center justify-between text-green-600 dark:text-green-400">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-file-circle-check text-2xl"></i>
                        <span class="text-sm font-bold">Đã tải lên biên lai! Quản lý đang kiểm tra.</span>
                    </div>
                    <img src="<?php echo URLROOT; ?>/public/uploads/receipts/<?php echo $order->receipt_image; ?>" class="w-10 h-10 rounded-lg object-cover border border-green-200">
                </div>
            <?php else: ?>
                <p class="text-sm text-gray-600 dark:text-slate-400 mb-3 font-medium">Hoặc gửi ảnh chụp biên lai nếu bạn đã chuyển khoản thành công nhưng đơn chưa được duyệt:</p>
                <input type="file" id="receiptFile" accept="image/*" class="hidden" onchange="uploadReceipt(this)">
                <label for="receiptFile" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-xl cursor-pointer transition font-bold text-sm shadow-sm">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Chọn ảnh biên lai
                </label>
                <p id="uploadStatus" class="text-xs mt-2 hidden"></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Actions -->
    <div class="px-6 pb-6 space-y-3">
        <a href="<?php echo URLROOT; ?>/order/history"
           class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 rounded-2xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-200 font-bold bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
            <i class="fa-solid fa-list-check"></i> Xem lịch sử đơn hàng
        </a>
        <a href="<?php echo URLROOT; ?>/product"
           class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 rounded-2xl border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition text-sm">
            <i class="fa-solid fa-arrow-left"></i> Tiếp tục mua sắm
        </a>
    </div>
</div>

<!-- ==================== OVERLAY: XÁC NHẬN THÀNH CÔNG ==================== -->
<div id="successOverlay"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-500"
     style="display:none">
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl max-w-md w-full p-8 text-center transform scale-90 transition-transform duration-500 border border-gray-100 dark:border-slate-800" id="successCard">
        <!-- Confetti icon -->
        <div class="text-6xl mb-4 animate-bounce">🎉</div>

        <div class="mx-auto w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-5 border border-green-200 dark:border-green-800/50">
            <i class="fa-solid fa-circle-check text-5xl text-green-500 dark:text-green-400"></i>
        </div>
        <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Thanh toán thành công!</h2>
        <p class="text-gray-500 dark:text-slate-400 mb-1 text-sm">Mã đơn hàng</p>
        <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-4">#<?php echo $paddedId; ?></p>
        <p class="text-sm text-gray-500 dark:text-slate-400 mb-6">Chúng tôi đã nhận được thanh toán của bạn và sẽ xử lý đơn hàng ngay!</p>

        <div class="space-y-3">
            <a href="<?php echo URLROOT; ?>/order/history"
               class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 rounded-2xl bg-gradient-to-r from-primary to-indigo-600 text-white font-black shadow-lg hover:-translate-y-0.5 transition-all">
                <i class="fa-solid fa-list-check"></i> Xem lịch sử đơn hàng
            </a>
            <a href="<?php echo URLROOT; ?>/product"
               class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 border border-gray-200 dark:border-slate-700 rounded-2xl text-gray-700 dark:text-slate-200 font-bold bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                <i class="fa-solid fa-cart-shopping"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>

<script>
// ===== POLLING: Kiểm tra trạng thái đơn hàng mỗi 8 giây =====
const CHECK_URL  = '<?php echo URLROOT; ?>/order/check_payment/<?php echo $orderId; ?>';
const POLL_INTERVAL = 8000; // 8 giây
let pollTimer;
let attempts = 0;
const MAX_ATTEMPTS = 75; // ~10 phút

function showSuccessOverlay() {
    const overlay = document.getElementById('successOverlay');
    const card    = document.getElementById('successCard');
    overlay.style.display = 'flex';
    // Trigger animation
    requestAnimationFrame(() => {
        overlay.classList.remove('opacity-0','pointer-events-none');
        overlay.classList.add('opacity-100');
        card.classList.remove('scale-90');
        card.classList.add('scale-100');
    });
    // Update badge
    const badge = document.getElementById('statusBadge');
    badge.className = 'flex items-center gap-2 px-4 py-2 rounded-full bg-green-50 border border-green-200 text-green-700 text-xs font-black';
    badge.innerHTML = '<span class="w-2.5 h-2.5 bg-green-500 rounded-full inline-block"></span> Đã thanh toán';

    // Update polling indicator
    document.getElementById('pollingInfo').innerHTML =
        '<i class="fa-solid fa-circle-check text-green-500 w-4 h-4"></i>' +
        '<span class="text-green-700 font-bold">Giao dịch đã được xác nhận!</span>';
}

async function checkPaymentStatus() {
    if (attempts >= MAX_ATTEMPTS) {
        clearInterval(pollTimer);
        document.getElementById('pollingText').textContent = 'Không thể xác nhận tự động. Vui lòng liên hệ hỗ trợ.';
        return;
    }
    attempts++;
    document.getElementById('pollingText').textContent =
        `Đang kiểm tra giao dịch... (lần ${attempts})`;

    try {
        const res  = await fetch(CHECK_URL, { cache: 'no-store' });
        const data = await res.json();
        if (data.success && data.paid) {
            clearInterval(pollTimer);
            showSuccessOverlay();
        }
    } catch(e) {
        // Ignore network errors, keep polling
    }
}

// Bắt đầu polling sau 5 giây
setTimeout(() => {
    checkPaymentStatus();
    pollTimer = setInterval(checkPaymentStatus, POLL_INTERVAL);
}, 5000);

async function uploadReceipt(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const formData = new FormData();
    formData.append('receipt', file);

    const statusEl = document.getElementById('uploadStatus');
    statusEl.textContent = "Đang tải lên...";
    statusEl.className = "text-xs mt-2 text-blue-500 block animate-pulse";

    try {
        const res = await fetch('<?php echo URLROOT; ?>/order/upload_receipt/<?php echo $orderId; ?>', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        
        if(data.success) {
            statusEl.textContent = "Đã tải lên biên lai thành công! Hệ thống đang chờ Admin duyệt.";
            statusEl.className = "text-xs mt-2 text-green-600 block font-bold";
            // Hide the label to prevent double upload
            input.nextElementSibling.style.display = 'none';
        } else {
            statusEl.textContent = data.message || "Lỗi khi tải lên.";
            statusEl.className = "text-xs mt-2 text-red-500 block";
        }
    } catch(err) {
        statusEl.textContent = "Đã xảy ra lỗi mạng.";
        statusEl.className = "text-xs mt-2 text-red-500 block";
    }
}
</script>

<?php elseif($isTransfer && $isPaid): ?>
<!-- ==================== CHUYỂN KHOẢN – ĐÃ HOÀN THÀNH ==================== -->
<div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-800 overflow-hidden">
    <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-8 text-center text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="relative z-10">
            <div class="mx-auto w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4 border-2 border-white/30">
                <i class="fa-solid fa-circle-check text-4xl"></i>
            </div>
            <h2 class="text-2xl font-black mb-1">Thanh Toán Thành Công!</h2>
            <p class="text-green-100 text-sm">Đơn hàng đang được xử lý</p>
        </div>
    </div>
    <div class="p-8 text-center">
        <p class="text-gray-400 dark:text-slate-500 text-sm mb-1">Mã đơn hàng</p>
        <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-6">#<?php echo $paddedId; ?></p>
        <div class="space-y-3">
            <a href="<?php echo URLROOT; ?>/order/history"
               class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 rounded-2xl bg-gradient-to-r from-primary to-indigo-600 text-white font-black shadow-lg hover:-translate-y-0.5 transition-all">
                <i class="fa-solid fa-list-check"></i> Xem lịch sử đơn hàng
            </a>
            <a href="<?php echo URLROOT; ?>/product"
               class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 border border-gray-200 dark:border-slate-700 rounded-2xl text-gray-700 dark:text-slate-200 font-bold hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                <i class="fa-solid fa-cart-shopping"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>

<?php elseif($isVNPay && $isPaid): ?>
<!-- ==================== VNPAY – ĐÃ THANH TOÁN ==================== -->
<div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-800 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-center text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="relative z-10">
            <div class="text-5xl mb-3 animate-bounce">🎉</div>
            <div class="mx-auto w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4 border-2 border-white/30">
                <i class="fa-solid fa-circle-check text-4xl"></i>
            </div>
            <h2 class="text-2xl font-black mb-1">Thanh Toán VNPay Thành Công!</h2>
            <p class="text-blue-100 text-sm">Đơn hàng đã được xác nhận tự động</p>
        </div>
    </div>
    <div class="p-8 text-center">
        <p class="text-gray-400 dark:text-slate-500 text-sm mb-1">Mã đơn hàng</p>
        <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-4">#<?php echo $paddedId; ?></p>

        <!-- VNPay badge -->
        <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-700 rounded-full px-4 py-2 text-xs font-bold mb-5">
            <img src="https://vnpay.vn/s1/statics/img/logo-new.35c5b5c.svg" alt="VNPay" class="h-4 object-contain">
            Đã xác nhận qua VNPay
        </div>

        <div class="bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm text-gray-500 dark:text-slate-400 text-left space-y-2 mb-6">
            <div class="flex items-center gap-2"><i class="fa-solid fa-bolt text-blue-500 w-4"></i><span>Thanh toán <strong class="text-gray-800 dark:text-slate-200">tức thì qua VNPay</strong></span></div>
            <div class="flex items-center gap-2"><i class="fa-solid fa-truck text-primary w-4"></i><span>Giao hàng dự kiến <strong class="text-gray-800 dark:text-slate-200">2–4 ngày</strong></span></div>
            <div class="flex items-center gap-2"><i class="fa-solid fa-envelope text-green-500 w-4"></i><span>Email xác nhận đã được gửi</span></div>
        </div>

        <div class="space-y-3">
            <a href="<?php echo URLROOT; ?>/order/history"
               class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 rounded-2xl bg-gradient-to-r from-primary to-indigo-600 text-white font-black shadow-lg hover:-translate-y-0.5 transition-all">
                <i class="fa-solid fa-list-check"></i> Xem lịch sử đơn hàng
            </a>
            <a href="<?php echo URLROOT; ?>/product"
               class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 border border-gray-200 dark:border-slate-700 rounded-2xl text-gray-700 dark:text-slate-200 font-bold hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                <i class="fa-solid fa-cart-shopping"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>

<?php else: ?>
<!-- ==================== COD ==================== -->
<div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-800 overflow-hidden">
    <div class="bg-gradient-to-r from-indigo-500 to-primary p-8 text-center text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="relative z-10">
            <div class="mx-auto w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4 border-2 border-white/30">
                <i class="fa-solid fa-circle-check text-4xl"></i>
            </div>
            <h2 class="text-2xl font-black mb-1">Đặt Hàng Thành Công!</h2>
            <p class="text-indigo-100 text-sm">Chúng tôi sẽ liên hệ xác nhận sớm nhất</p>
        </div>
    </div>
    <div class="p-8 text-center">
        <p class="text-gray-400 dark:text-slate-500 text-sm mb-1">Mã đơn hàng của bạn</p>
        <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-6">#<?php echo $paddedId; ?></p>
        <div class="bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm text-gray-500 dark:text-slate-400 text-left space-y-2 mb-6">
            <div class="flex items-center gap-2"><i class="fa-solid fa-truck text-primary w-4"></i><span>Giao hàng dự kiến <strong class="text-gray-800 dark:text-slate-200">2–4 ngày</strong></span></div>
            <div class="flex items-center gap-2"><i class="fa-solid fa-phone text-green-500 w-4"></i><span>Chúng tôi sẽ gọi xác nhận trước khi giao</span></div>
            <div class="flex items-center gap-2"><i class="fa-solid fa-money-bill-wave text-orange-500 w-4"></i><span>Thanh toán tiền mặt khi nhận hàng</span></div>
        </div>
        <div class="space-y-3">
            <a href="<?php echo URLROOT; ?>/order/history"
               class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 rounded-2xl bg-gradient-to-r from-primary to-indigo-600 text-white font-black shadow-lg hover:-translate-y-0.5 transition-all">
                <i class="fa-solid fa-list-check"></i> Xem lịch sử đơn hàng
            </a>
            <a href="<?php echo URLROOT; ?>/product"
               class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 border border-gray-200 dark:border-slate-700 rounded-2xl text-gray-700 dark:text-slate-200 font-bold hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                <i class="fa-solid fa-cart-shopping"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

</div><!-- /max-w-2xl -->
</div>

<!-- Toast copy -->
<div id="copyToast" class="fixed bottom-6 right-6 bg-gray-900 text-white text-sm font-bold px-5 py-3 rounded-2xl shadow-2xl opacity-0 translate-y-4 transition-all duration-300 pointer-events-none flex items-center gap-2 z-50">
    <i class="fa-solid fa-check text-green-400"></i> Đã sao chép!
</div>

<script>
function copyText(text) {
    navigator.clipboard.writeText(String(text)).then(() => {
        const toast = document.getElementById('copyToast');
        toast.classList.remove('opacity-0','translate-y-4');
        toast.classList.add('opacity-100','translate-y-0');
        setTimeout(() => {
            toast.classList.add('opacity-0','translate-y-4');
            toast.classList.remove('opacity-100','translate-y-0');
        }, 2000);
    });
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
