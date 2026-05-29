<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
// ===== CẤU HÌNH NGÂN HÀNG =====
define('BANK_CODE',    'VCB');
define('BANK_NAME',    'Vietcombank (VCB)');
define('BANK_ACCOUNT', '1047429167');
define('BANK_OWNER',   'NGUYEN MINH TUAN');
define('BANK_BRANCH',  'PGD Yên Mỹ');
// ================================
$transfer_content = 'PETSHOP ' . str_pad($_SESSION['user_id'], 4, '0', STR_PAD_LEFT);
$qr_url = 'https://img.vietqr.io/image/' . BANK_CODE . '-' . BANK_ACCOUNT . '-compact2.jpg'
        . '?amount=' . urlencode($data['total'])
        . '&addInfo=' . urlencode($transfer_content)
        . '&accountName=' . urlencode(BANK_OWNER);
?>

<div class="bg-gradient-to-br from-indigo-50/50 via-white to-pink-50/30 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-8 flex items-center gap-3">
            <i class="fa-solid fa-bag-shopping text-primary"></i> Thanh Toán Đơn Hàng
        </h1>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-10 lg:items-start">
            <!-- Left: Form -->
            <div class="lg:col-span-7">
                <form action="<?php echo URLROOT; ?>/order/process" method="POST" id="checkoutForm">

                    <!-- Shipping Info -->
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 mb-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-primary"></i> Thông tin giao hàng
                        </h2>
                        <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-4">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Họ và tên *</label>
                                <input type="text" id="fullname" name="fullname" value="<?php echo $data['user']->fullname; ?>"
                                       class="block w-full border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm py-2.5 px-4 border transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Số điện thoại *</label>
                                <input type="text" id="phone" name="phone" value="<?php echo $data['user']->phone ?? ''; ?>"
                                       class="block w-full border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm py-2.5 px-4 border transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                <input type="email" value="<?php echo $data['user']->email; ?>"
                                       class="block w-full border-gray-200 rounded-xl text-sm py-2.5 px-4 border bg-gray-50 text-gray-400 cursor-not-allowed" readonly>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Địa chỉ giao hàng *</label>
                                <textarea name="address" rows="3"
                                          class="block w-full border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm py-2.5 px-4 border transition"
                                          required placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành..."><?php echo $data['user']->address ?? ''; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                            <i class="fa-solid fa-credit-card text-primary"></i> Phương thức thanh toán
                        </h2>

                        <div class="space-y-3" id="paymentOptions">
                            <!-- COD -->
                            <label for="payment_cod" id="label_cod"
                                   class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all border-primary bg-primary/5">
                                <input id="payment_cod" name="payment_method" type="radio" value="cod" checked
                                       class="h-4 w-4 text-primary border-gray-300 focus:ring-primary">
                                <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-money-bill-wave text-orange-500"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">Thanh toán khi nhận hàng (COD)</p>
                                    <p class="text-xs text-gray-500">Trả tiền mặt khi nhận hàng tại nhà</p>
                                </div>
                            </label>

                            <!-- Bank Transfer -->
                            <label for="payment_transfer" id="label_transfer"
                                   class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all border-gray-200 hover:border-gray-300">
                                <input id="payment_transfer" name="payment_method" type="radio" value="transfer"
                                       class="h-4 w-4 text-primary border-gray-300 focus:ring-primary">
                                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-building-columns text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800 text-sm">Chuyển khoản ngân hàng</p>
                                    <p class="text-xs text-gray-500">Quét mã VietQR — xác nhận tự động</p>
                                </div>
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Vietcombank_logo.svg/200px-Vietcombank_logo.svg.png"
                                     class="h-6 object-contain opacity-80" alt="VCB">
                            </label>
                        </div>

                        <!-- Bank Transfer Panel -->
                        <div id="bank_transfer_panel" class="hidden mt-5 rounded-2xl border-2 border-green-200 bg-gradient-to-br from-green-50 to-emerald-50 overflow-hidden">
                            <div class="p-5">
                                <div class="flex flex-col sm:flex-row gap-6 items-center">

                                    <!-- QR -->
                                    <div class="flex-shrink-0 text-center">
                                        <div class="bg-white p-3 rounded-2xl shadow-md inline-block border border-green-200">
                                            <img src="<?php echo $qr_url; ?>"
                                                 alt="QR VietQR"
                                                 class="w-44 h-44 rounded-xl object-cover"
                                                 onerror="this.src='https://placehold.co/176x176?text=QR+Code'">
                                        </div>
                                        <p class="text-xs text-green-700 font-bold mt-2">📱 Quét bằng app ngân hàng</p>
                                    </div>

                                    <!-- Bank Info -->
                                    <div class="flex-1 w-full space-y-2.5">
                                        <p class="text-xs font-black text-green-700 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-check"></i> Thông tin chuyển khoản
                                        </p>

                                        <?php
                                        $bankFields = [
                                            ['label' => 'Ngân hàng',         'value' => BANK_NAME,       'copy' => false],
                                            ['label' => 'Chủ tài khoản',     'value' => BANK_OWNER,      'copy' => false],
                                            ['label' => 'Số tài khoản',      'value' => BANK_ACCOUNT,    'copy' => true],
                                            ['label' => 'Số tiền (đ)',        'value' => number_format($data['total'], 0, ',', '.') . 'đ', 'rawValue' => $data['total'], 'copy' => true],
                                            ['label' => 'Nội dung CK',       'value' => $transfer_content, 'copy' => true, 'highlight' => true],
                                        ];
                                        foreach($bankFields as $f):
                                        ?>
                                        <div class="flex items-center justify-between bg-white rounded-xl px-4 py-2.5 border <?php echo !empty($f['highlight']) ? 'border-green-300 bg-green-50' : 'border-gray-100'; ?>">
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase"><?php echo $f['label']; ?></p>
                                                <p class="text-sm font-black <?php echo !empty($f['highlight']) ? 'text-green-700' : 'text-gray-800'; ?>"><?php echo $f['value']; ?></p>
                                            </div>
                                            <?php if($f['copy']): ?>
                                            <button type="button"
                                                    onclick="copyText('<?php echo !empty($f['rawValue']) ? $f['rawValue'] : $f['value']; ?>')"
                                                    class="text-green-600 hover:text-green-800 transition text-xs font-bold flex items-center gap-1 ml-2 flex-shrink-0">
                                                <i class="fa-regular fa-copy"></i> Copy
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Note -->
                                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex gap-3 items-start">
                                    <i class="fa-solid fa-circle-info text-amber-500 mt-0.5 flex-shrink-0"></i>
                                    <p class="text-xs text-amber-700 leading-relaxed">
                                        Nhập đúng <strong>nội dung chuyển khoản</strong> để đơn hàng được xác nhận tự động.
                                        Sau khi chuyển, trang đặt hàng sẽ tự động cập nhật khi chúng tôi xác nhận giao dịch.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="mt-6">
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-primary to-indigo-600 rounded-2xl shadow-lg shadow-primary/20 py-4 px-4 text-base font-black text-white hover:shadow-primary/40 hover:-translate-y-0.5 focus:outline-none transition-all duration-300 flex items-center justify-center gap-3">
                                <i class="fa-solid fa-bag-shopping"></i>
                                <span id="submitText">Đặt Hàng Ngay</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right: Order Summary -->
            <div class="mt-10 lg:mt-0 lg:col-span-5">
                <div class="sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-secondary"></i> Đơn hàng của bạn
                    </h2>
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                        <ul class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
                            <?php foreach($data['cart'] as $item): ?>
                            <li class="flex gap-4 p-4">
                                <div class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden bg-gray-50 border border-gray-100">
                                    <img src="<?php echo !empty($item['image']) ? URLROOT.'/public/images/'.$item['image'] : 'https://placehold.co/100x100?text=IMG'; ?>"
                                         alt="<?php echo $item['name']; ?>" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-800 line-clamp-2"><?php echo $item['name']; ?></p>
                                    <p class="text-xs text-gray-400 mt-0.5">Số lượng: <?php echo $item['quantity']; ?></p>
                                </div>
                                <p class="text-sm font-black text-gray-900 whitespace-nowrap">
                                    <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ
                                </p>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="border-t border-gray-100 p-5 space-y-3 bg-gray-50/50">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Tạm tính</span>
                                <span class="font-semibold text-gray-800"><?php echo number_format($data['total'], 0, ',', '.'); ?>đ</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Phí vận chuyển</span>
                                <span class="font-bold text-green-600">Miễn phí</span>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                <span class="text-base font-black text-gray-900">Tổng cộng</span>
                                <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">
                                    <?php echo number_format($data['total'], 0, ',', '.'); ?>đ
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-center gap-6 text-xs text-gray-400">
                        <span class="flex items-center gap-1"><i class="fa-solid fa-shield-halved text-green-500"></i> An toàn</span>
                        <span class="flex items-center gap-1"><i class="fa-solid fa-lock text-blue-500"></i> SSL</span>
                        <span class="flex items-center gap-1"><i class="fa-solid fa-rotate-left text-orange-500"></i> Đổi trả 7 ngày</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="copyToast" class="fixed bottom-6 right-6 bg-gray-900 text-white text-sm font-bold px-5 py-3 rounded-2xl shadow-2xl opacity-0 translate-y-4 transition-all duration-300 pointer-events-none flex items-center gap-2 z-50">
    <i class="fa-solid fa-check text-green-400"></i> Đã sao chép!
</div>

<script>
// ===== Payment method toggle =====
const labelCod      = document.getElementById('label_cod');
const labelTransfer = document.getElementById('label_transfer');
const panel         = document.getElementById('bank_transfer_panel');
const submitText    = document.getElementById('submitText');

function switchPayment(type) {
    if (type === 'transfer') {
        panel.classList.remove('hidden');
        labelTransfer.classList.replace('border-gray-200', 'border-green-500');
        labelTransfer.classList.add('bg-green-50/50');
        labelCod.classList.replace('border-primary', 'border-gray-200');
        labelCod.classList.remove('bg-primary/5');
        submitText.textContent = 'Xác Nhận & Đặt Hàng';
    } else {
        panel.classList.add('hidden');
        labelCod.classList.replace('border-gray-200', 'border-primary');
        labelCod.classList.add('bg-primary/5');
        labelTransfer.classList.replace('border-green-500', 'border-gray-200');
        labelTransfer.classList.remove('bg-green-50/50');
        submitText.textContent = 'Đặt Hàng Ngay';
    }
}

document.getElementById('payment_cod').addEventListener('change', () => switchPayment('cod'));
document.getElementById('payment_transfer').addEventListener('change', () => switchPayment('transfer'));

// ===== Copy to clipboard =====
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
