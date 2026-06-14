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

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
.checkout-wrap { font-family: 'Inter', sans-serif; }

/* Payment cards */
.pay-card { transition: all .22s cubic-bezier(.4,0,.2,1); cursor: pointer; }
.pay-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
.pay-card.active { border-color: #6366f1 !important; background: #f5f3ff; box-shadow: 0 0 0 3px rgba(99,102,241,.18), 0 8px 24px rgba(99,102,241,.12); }

/* QR glow */
@keyframes qrGlow { 0%,100%{box-shadow:0 0 0 0 rgba(34,197,94,.35)}50%{box-shadow:0 0 0 10px rgba(34,197,94,0)} }
.qr-glow { animation: qrGlow 2.2s infinite; }

/* VNPay gradient */
.vnpay-btn { background: linear-gradient(135deg,#0066cc,#004499); }
.vnpay-btn:hover { background: linear-gradient(135deg,#0055bb,#003388); }

/* Panel slide */
.panel-enter { animation: panelIn .3s ease; }
@keyframes panelIn { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:none} }

.order-summary { position: sticky; top: 88px; }
.badge-bank { border: 1px solid #d1fae5; }
</style>

<div class="checkout-wrap bg-gradient-to-br from-slate-50 via-indigo-50/20 to-purple-50/10 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- BREADCRUMB -->
        <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
            <a href="<?php echo URLROOT; ?>" class="hover:text-primary transition">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <a href="<?php echo URLROOT; ?>/cart" class="hover:text-primary transition">Giỏ hàng</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-gray-600 font-semibold">Thanh toán</span>
        </nav>

        <!-- STEPS -->
        <div class="flex items-center gap-0 max-w-xs mb-8">
            <div class="flex flex-col items-center gap-1 flex-1">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-xs font-black shadow-md shadow-primary/30 z-10">
                    <i class="fa-solid fa-check text-[11px]"></i>
                </div>
                <span class="text-[10px] font-bold text-primary">Giỏ hàng</span>
            </div>
            <div class="flex-1 h-0.5 bg-primary mb-4" style="margin:0 -4px;z-index:0"></div>
            <div class="flex flex-col items-center gap-1 flex-1">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-xs font-black shadow-md shadow-primary/30 z-10">2</div>
                <span class="text-[10px] font-bold text-primary">Thanh toán</span>
            </div>
            <div class="flex-1 h-0.5 bg-gray-200 mb-4" style="margin:0 -4px;z-index:0"></div>
            <div class="flex flex-col items-center gap-1 flex-1">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs font-black z-10">3</div>
                <span class="text-[10px] font-bold text-gray-400">Xác nhận</span>
            </div>
        </div>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-10 lg:items-start">

            <!-- ══════════════════════ FORM LEFT ══════════════════════ -->
            <div class="lg:col-span-7 space-y-5">

                <!-- ① THÔNG TIN GIAO HÀNG -->
                <div class="bg-white shadow-sm rounded-3xl border border-gray-100 p-6 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-primary to-indigo-400 rounded-l-3xl"></div>
                    <h2 class="text-sm font-black text-gray-900 mb-5 pl-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-indigo-50 text-primary flex items-center justify-center text-xs"><i class="fa-solid fa-location-dot"></i></span>
                        Thông tin giao hàng
                    </h2>
                    <!-- Shipping fields dùng chung cho tất cả form -->
                    <div id="shippingFields" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Họ và tên *</label>
                            <input type="text" id="f_fullname" value="<?php echo htmlspecialchars($data['user']->fullname); ?>"
                                   class="block w-full border border-gray-200 rounded-2xl text-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary transition bg-gray-50/50" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Số điện thoại *</label>
                            <input type="text" id="f_phone" value="<?php echo htmlspecialchars($data['user']->phone ?? ''); ?>"
                                   class="block w-full border border-gray-200 rounded-2xl text-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary transition bg-gray-50/50" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Email</label>
                            <input type="email" value="<?php echo htmlspecialchars($data['user']->email); ?>"
                                   class="block w-full border border-gray-100 rounded-2xl text-sm py-3 px-4 bg-gray-50 text-gray-400 cursor-not-allowed" readonly>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Địa chỉ giao hàng *</label>
                            <textarea id="f_address" rows="3"
                                      class="block w-full border border-gray-200 rounded-2xl text-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary transition bg-gray-50/50 resize-none"
                                      placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành..."><?php echo htmlspecialchars($data['user']->address ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- ② PHƯƠNG THỨC THANH TOÁN -->
                <div class="bg-white shadow-sm rounded-3xl border border-gray-100 p-6 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-violet-500 to-purple-400 rounded-l-3xl"></div>
                    <h2 class="text-sm font-black text-gray-900 mb-5 pl-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-xs"><i class="fa-solid fa-credit-card"></i></span>
                        Phương thức thanh toán
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5" id="paymentCards">

                        <!-- COD -->
                        <div class="pay-card active rounded-2xl border-2 border-primary p-4 flex flex-col items-center text-center gap-2" data-method="cod" onclick="selectMethod('cod')">
                            <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center">
                                <i class="fa-solid fa-money-bill-wave text-orange-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-black text-gray-800 text-xs leading-tight">Thanh toán<br>khi nhận hàng</p>
                                <span class="text-[10px] text-orange-500 font-bold bg-orange-50 px-2 py-0.5 rounded-full mt-1 inline-block">COD</span>
                            </div>
                        </div>

                        <!-- CHUYỂN KHOẢN -->
                        <div class="pay-card rounded-2xl border-2 border-gray-200 p-4 flex flex-col items-center text-center gap-2" data-method="transfer" onclick="selectMethod('transfer')">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center">
                                <i class="fa-solid fa-building-columns text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-black text-gray-800 text-xs leading-tight">Chuyển khoản<br>VietQR</p>
                                <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full mt-1 inline-block border border-green-200">Tự động</span>
                            </div>
                        </div>

                        <!-- VNPAY -->
                        <div class="pay-card rounded-2xl border-2 border-gray-200 p-4 flex flex-col items-center text-center gap-2" data-method="vnpay" onclick="selectMethod('vnpay')">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center overflow-hidden">
                                <img src="https://vnpay.vn/s1/statics/img/logo-new.35c5b5c.svg" alt="VNPay" class="h-7 object-contain">
                            </div>
                            <div>
                                <p class="font-black text-gray-800 text-xs leading-tight">Thanh toán<br>trực tuyến</p>
                                <span class="text-[10px] text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded-full mt-1 inline-block border border-blue-200">VNPay</span>
                            </div>
                        </div>
                    </div>

                    <!-- ── Panel: COD ── -->
                    <div id="panel_cod" class="panel-enter rounded-2xl border border-orange-100 bg-orange-50/50 p-4">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-orange-400 mt-0.5 flex-shrink-0"></i>
                            <div class="text-sm text-orange-800 space-y-1">
                                <p class="font-bold">Thanh toán tiền mặt khi nhận hàng (COD)</p>
                                <ul class="text-xs text-orange-600 space-y-0.5 list-disc list-inside">
                                    <li>Giao hàng dự kiến <strong>2–4 ngày làm việc</strong></li>
                                    <li>Nhân viên giao hàng sẽ gọi điện xác nhận trước</li>
                                    <li>Thanh toán tiền mặt trực tiếp khi nhận hàng</li>
                                    <li>Phí ship: <strong>Miễn phí</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- ── Panel: Chuyển khoản ── -->
                    <div id="panel_transfer" class="hidden panel-enter rounded-2xl border-2 border-green-200 bg-gradient-to-br from-green-50 to-emerald-50 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 px-5 py-2.5 flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-white text-sm"></i>
                            <span class="text-xs font-bold text-white">Thanh toán an toàn qua VietQR</span>
                        </div>
                        <div class="p-5">
                            <div class="flex flex-col sm:flex-row gap-5 items-start">
                                <div class="flex-shrink-0 text-center mx-auto sm:mx-0">
                                    <div class="bg-white p-3 rounded-2xl shadow-lg inline-block border border-green-200 qr-glow">
                                        <img src="<?php echo $qr_url; ?>" alt="QR VietQR" class="w-40 h-40 rounded-xl object-cover"
                                             onerror="this.src='https://placehold.co/160x160?text=QR'">
                                    </div>
                                    <p class="text-xs text-green-700 font-bold mt-2">📱 Quét bằng app ngân hàng</p>
                                </div>
                                <div class="flex-1 w-full space-y-2">
                                    <p class="text-[10px] font-black text-green-700 uppercase tracking-widest mb-2"><i class="fa-solid fa-circle-check mr-1"></i> Thông tin chuyển khoản</p>
                                    <?php
                                    $bankFields = [
                                        ['Ngân hàng',     BANK_NAME,    false, false, null],
                                        ['Chủ tài khoản', BANK_OWNER,   false, false, null],
                                        ['Số tài khoản',  BANK_ACCOUNT, true,  false, null],
                                        ['Số tiền',       number_format($data['total'],0,',','.').'đ', true, false, (int)$data['total']],
                                        ['Nội dung CK',   $transfer_content, true, true, null],
                                    ];
                                    foreach($bankFields as [$label,$val,$canCopy,$hl,$rawVal]):
                                        $cv = ($rawVal !== null) ? $rawVal : $val;
                                    ?>
                                    <div class="flex items-center justify-between bg-white rounded-xl px-3 py-2.5 border <?php echo $hl?'border-green-300':'border-gray-100'; ?>">
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase"><?php echo $label; ?></p>
                                            <p class="text-sm font-black <?php echo $hl?'text-green-700':'text-gray-800'; ?>"><?php echo $val; ?></p>
                                        </div>
                                        <?php if($canCopy): ?>
                                        <button type="button" onclick="copyText('<?php echo htmlspecialchars($cv,ENT_QUOTES); ?>')"
                                                class="ml-2 flex-shrink-0 text-xs font-bold text-green-600 hover:text-green-800 flex items-center gap-1 transition px-2 py-1 rounded-lg hover:bg-green-50">
                                            <i class="fa-regular fa-copy"></i> Copy
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                    <div class="mt-3 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2.5 flex gap-2 items-start">
                                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5 flex-shrink-0 text-xs"></i>
                                        <p class="text-xs text-amber-700 leading-relaxed">Nhập đúng <strong>nội dung chuyển khoản</strong> để được xác nhận tự động trong 5–15 phút.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Panel: VNPay ── -->
                    <div id="panel_vnpay" class="hidden panel-enter rounded-2xl border-2 border-blue-200 bg-gradient-to-br from-blue-50 to-indigo-50 overflow-hidden">
                        <!-- Top bar -->
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-white">
                                <i class="fa-solid fa-shield-halved text-sm"></i>
                                <span class="text-xs font-bold">Cổng thanh toán VNPay</span>
                            </div>
                            <span class="text-[10px] text-blue-100 font-medium">Bảo mật SSL</span>
                        </div>
                        <div class="p-5">
                            <!-- Logos các ví/thẻ -->
                            <p class="text-[10px] font-black text-blue-700 uppercase tracking-widest mb-3">Hỗ trợ hình thức thanh toán</p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <?php
                                $paymentLogos = [
                                    ['https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png',   'MoMo'],
                                    ['https://upload.wikimedia.org/wikipedia/vi/9/97/ZaloPay_Logo.png', 'ZaloPay'],
                                    ['https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Vietcombank_logo.svg/200px-Vietcombank_logo.svg.png', 'VCB'],
                                    ['https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Techcombank_logo.svg/200px-Techcombank_logo.svg.png', 'TCB'],
                                    ['https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/200px-Mastercard-logo.svg.png', 'Mastercard'],
                                    ['https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/200px-Visa_Inc._logo.svg.png', 'Visa'],
                                ];
                                foreach($paymentLogos as [$logo, $alt]):
                                ?>
                                <div class="bg-white rounded-xl px-3 py-2 border border-blue-100 flex items-center h-10 shadow-sm">
                                    <img src="<?php echo $logo; ?>" alt="<?php echo $alt; ?>" class="h-5 object-contain max-w-[56px]"
                                         onerror="this.parentElement.innerHTML='<span class=\'text-[10px] font-bold text-gray-500\'><?php echo $alt; ?></span>'">
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Steps -->
                            <div class="grid grid-cols-3 gap-3 mb-4 text-center">
                                <?php
                                $steps = [
                                    ['fa-bag-shopping','Đặt hàng','Xác nhận thông tin'],
                                    ['fa-arrow-up-right-from-square','Cổng VNPay','Chọn ví / thẻ / ngân hàng'],
                                    ['fa-circle-check','Hoàn tất','Về trang xác nhận'],
                                ];
                                foreach($steps as $i => [$icon,$title,$sub]):
                                ?>
                                <div class="bg-white rounded-2xl p-3 border border-blue-100 shadow-sm">
                                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-2">
                                        <i class="fa-solid <?php echo $icon; ?> text-sm"></i>
                                    </div>
                                    <p class="text-xs font-black text-gray-800"><?php echo $title; ?></p>
                                    <p class="text-[10px] text-gray-400 mt-0.5"><?php echo $sub; ?></p>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="bg-blue-100/50 border border-blue-200 rounded-xl px-4 py-3 flex gap-2 items-start">
                                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 flex-shrink-0 text-xs"></i>
                                <p class="text-xs text-blue-700 leading-relaxed">
                                    Bạn sẽ được chuyển đến <strong>trang thanh toán bảo mật của VNPay</strong>. 
                                    Sau khi hoàn tất, hệ thống tự động xác nhận và cập nhật đơn hàng.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ③ KHUYẾN MÃI (VOUCHER) -->
                <div class="bg-white shadow-sm rounded-3xl border border-gray-100 p-6 relative overflow-hidden mb-6">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-emerald-400 to-teal-400 rounded-l-3xl"></div>
                    <h2 class="text-sm font-black text-gray-900 mb-4 pl-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs"><i class="fa-solid fa-ticket"></i></span>
                        Áp dụng mã giảm giá
                    </h2>
                    
                    <div class="space-y-4 pl-3">
                        <div class="flex gap-2">
                            <input type="text" id="voucherInput" placeholder="Nhập mã giảm giá..." class="flex-1 border border-gray-200 rounded-2xl text-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-emerald-300/40 transition bg-white uppercase font-bold text-emerald-700">
                            <button type="button" onclick="applyVoucher()" class="px-6 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition">Áp dụng</button>
                        </div>
                        <p id="voucherMessage" class="text-xs font-bold text-gray-500 hidden"></p>

                        <?php if (!empty($data['vouchers'])): ?>
                            <div class="pt-3 border-t border-gray-100">
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-2">Voucher của bạn</p>
                                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                                    <?php foreach ($data['vouchers'] as $v): ?>
                                        <button type="button" onclick="document.getElementById('voucherInput').value='<?php echo htmlspecialchars($v->unique_code); ?>'; applyVoucher()" class="flex-shrink-0 text-left border border-emerald-100 bg-emerald-50 hover:bg-emerald-100 rounded-xl px-3 py-2 transition min-w-[150px]">
                                            <p class="text-xs font-black text-emerald-700"><?php echo htmlspecialchars($v->title); ?></p>
                                            <p class="text-[10px] text-emerald-600 mt-0.5">Mã: <?php echo htmlspecialchars($v->unique_code); ?></p>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-3xl border border-gray-100 p-6 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-amber-400 to-orange-400 rounded-l-3xl"></div>
                    <h2 class="text-sm font-black text-gray-900 mb-3 pl-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xs"><i class="fa-solid fa-note-sticky"></i></span>
                        Ghi chú <span class="text-xs font-normal text-gray-400">(tuỳ chọn)</span>
                    </h2>
                    <textarea id="f_note" rows="2"
                              class="block w-full border border-gray-200 rounded-2xl text-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-amber-300/40 transition bg-gray-50/50 resize-none"
                              placeholder="Giao giờ hành chính, gọi trước khi giao..."></textarea>
                </div>

                <!-- ── HIDDEN FORMS ── -->

                <!-- Form COD -->
                <form id="form_cod" action="<?php echo URLROOT; ?>/order/process" method="POST" class="hidden">
                    <input type="hidden" name="payment_method" value="cod">
                    <input type="hidden" name="fullname"  id="cod_fullname">
                    <input type="hidden" name="phone"     id="cod_phone">
                    <input type="hidden" name="address"   id="cod_address">
                    <input type="hidden" name="note"      id="cod_note">
                    <input type="hidden" name="voucher_code" id="cod_voucher">
                </form>

                <!-- Form Transfer -->
                <form id="form_transfer" action="<?php echo URLROOT; ?>/order/process" method="POST" class="hidden">
                    <input type="hidden" name="payment_method" value="transfer">
                    <input type="hidden" name="fullname"  id="tf_fullname">
                    <input type="hidden" name="phone"     id="tf_phone">
                    <input type="hidden" name="address"   id="tf_address">
                    <input type="hidden" name="note"      id="tf_note">
                    <input type="hidden" name="voucher_code" id="tf_voucher">
                </form>

                <!-- Form VNPay -->
                <form id="form_vnpay" action="<?php echo URLROOT; ?>/vnpay/create" method="POST" class="hidden">
                    <input type="hidden" name="payment_method" value="vnpay">
                    <input type="hidden" name="fullname"  id="vp_fullname">
                    <input type="hidden" name="phone"     id="vp_phone">
                    <input type="hidden" name="address"   id="vp_address">
                    <input type="hidden" name="note"      id="vp_note">
                    <input type="hidden" name="voucher_code" id="vp_voucher">
                </form>

                <!-- SUBMIT BUTTON -->
                <button type="button" id="submitBtn" onclick="handleSubmit()"
                        class="w-full rounded-2xl shadow-xl py-4 px-4 text-base font-black text-white focus:outline-none transition-all duration-300 flex items-center justify-center gap-3 bg-gradient-to-r from-primary via-indigo-600 to-violet-600 hover:shadow-primary/40 hover:-translate-y-1">
                    <i class="fa-solid fa-bag-shopping text-lg" id="submitIcon"></i>
                    <span id="submitText">Đặt Hàng Ngay (COD)</span>
                    <i class="fa-solid fa-arrow-right text-sm opacity-70"></i>
                </button>

                <!-- Security badges -->
                <div class="flex items-center justify-center gap-6 text-xs text-gray-400 flex-wrap">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-green-500 text-base"></i> SSL 256-bit</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-lock text-blue-500 text-base"></i> Mã hoá dữ liệu</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-rotate-left text-orange-500 text-base"></i> Đổi trả 7 ngày</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-headset text-violet-500 text-base"></i> Hỗ trợ 24/7</span>
                </div>
            </div>

            <!-- ══════════════════════ ORDER SUMMARY RIGHT ══════════════════════ -->
            <div class="mt-8 lg:mt-0 lg:col-span-5">
                <div class="order-summary space-y-4">

                    <!-- Order card -->
                    <div class="bg-white shadow-sm rounded-3xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center justify-between">
                            <h2 class="text-sm font-black text-white flex items-center gap-2">
                                <i class="fa-solid fa-receipt text-indigo-300"></i> Đơn hàng của bạn
                            </h2>
                            <span class="text-xs text-slate-400"><?php echo count($data['cart']); ?> sản phẩm</span>
                        </div>
                        <ul class="divide-y divide-gray-50 max-h-64 overflow-y-auto">
                            <?php foreach($data['cart'] as $item): ?>
                            <li class="flex gap-4 p-4 hover:bg-gray-50/60 transition">
                                <div class="relative flex-shrink-0 w-16 h-16 rounded-2xl overflow-hidden bg-gray-50 border border-gray-100">
                                    <img src="<?php echo !empty($item['image']) ? URLROOT.'/public/images/'.$item['image'] : 'https://placehold.co/100x100?text=IMG'; ?>"
                                         alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-full object-cover">
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-primary text-white text-[10px] font-black rounded-full flex items-center justify-center"><?php echo $item['quantity']; ?></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-800 line-clamp-2"><?php echo htmlspecialchars($item['name']); ?></p>
                                    <p class="text-xs text-gray-400 mt-0.5"><?php echo number_format($item['price'],0,',','.'); ?>đ / cái</p>
                                </div>
                                <p class="text-sm font-black text-gray-900 whitespace-nowrap"><?php echo number_format($item['price']*$item['quantity'],0,',','.'); ?>đ</p>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="border-t border-gray-100 p-5 space-y-3 bg-gray-50/40">
                            <?php
                            $memDiscountInfo = $data['membership_discount'] ?? ['level' => 'Đồng', 'discount_percent' => 0];
                            $memDiscountPercent = $memDiscountInfo['discount_percent'];
                            $memDiscountAmount = floor(($data['total'] * $memDiscountPercent) / 100);
                            $initialTotal = max(0, $data['total'] - $memDiscountAmount);
                            ?>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Tạm tính</span>
                                <span class="font-semibold text-gray-700"><?php echo number_format($data['total'],0,',','.'); ?>đ</span>
                            </div>
                            <?php if($memDiscountPercent > 0): ?>
                            <div class="flex items-center justify-between text-sm" id="mem_discount_row">
                                <span class="text-gray-500">Ưu đãi hạng thẻ (<?php echo $memDiscountInfo['level']; ?> - <?php echo $memDiscountPercent; ?>%)</span>
                                <span class="font-bold text-orange-500">-<?php echo number_format($memDiscountAmount,0,',','.'); ?>đ</span>
                            </div>
                            <?php endif; ?>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Phí vận chuyển</span>
                                <span class="font-bold text-green-600 flex items-center gap-1"><i class="fa-solid fa-truck-fast text-xs"></i> Miễn phí</span>
                            </div>
                            <div class="flex items-center justify-between text-sm hidden" id="discount_row">
                                <span class="text-gray-500">Giảm giá (Voucher)</span>
                                <span class="font-bold text-primary" id="discount_amount_text">-0đ</span>
                            </div>
                            <div class="border-t border-dashed border-gray-200 my-1"></div>
                            <div class="flex items-center justify-between">
                                <span class="font-black text-gray-900">Tổng cộng</span>
                                <span id="checkout_total_price" class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary to-violet-600">
                                    <?php echo number_format($initialTotal,0,',','.'); ?>đ
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery info -->
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-truck text-primary"></i> Thông tin giao hàng
                        </h3>
                        <div class="space-y-2.5">
                            <?php
                            $deliveryInfo = [
                                ['fa-box','blue','Giao hàng tiêu chuẩn','Dự kiến 2–4 ngày làm việc'],
                                ['fa-phone','green','Xác nhận qua điện thoại','Gọi trước khi giao hàng'],
                                ['fa-rotate-left','purple','Đổi trả miễn phí','Trong vòng 7 ngày'],
                            ];
                            foreach($deliveryInfo as [$icon,$color,$title,$sub]):
                            ?>
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 bg-<?php echo $color; ?>-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid <?php echo $icon; ?> text-<?php echo $color; ?>-500 text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-700 text-xs"><?php echo $title; ?></p>
                                    <p class="text-[10px] text-gray-400"><?php echo $sub; ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Hotline -->
                    <div class="bg-gradient-to-r from-primary/5 to-violet-50 rounded-2xl border border-primary/10 p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary flex-shrink-0">
                            <i class="fa-solid fa-headset text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-medium">Hỗ trợ đặt hàng</p>
                            <p class="font-black text-gray-800 text-sm">Hotline: <a href="tel:0947647052" class="text-primary hover:underline">0947 647 052</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast copy -->
<div id="copyToast" class="fixed bottom-6 right-6 bg-gray-900 text-white text-sm font-bold px-5 py-3 rounded-2xl shadow-2xl opacity-0 translate-y-4 transition-all duration-300 pointer-events-none flex items-center gap-2 z-50">
    <i class="fa-solid fa-check text-green-400"></i> Đã sao chép!
</div>

<script>
let currentMethod = 'cod';
const originalTotal = <?php echo $data['total']; ?>;
let memDiscountAmount = <?php echo $memDiscountAmount; ?>;

const methodConfig = {
    cod:      { text: 'Đặt Hàng Ngay (COD)',            icon: 'fa-money-bill-wave', btnClass: 'from-orange-500 via-orange-600 to-amber-600'  },
    transfer: { text: 'Xác Nhận & Đặt Hàng (CK)',        icon: 'fa-building-columns', btnClass: 'from-green-600 via-emerald-600 to-teal-600' },
    vnpay:    { text: 'Tiếp tục thanh toán qua VNPay',   icon: 'fa-arrow-up-right-from-square', btnClass: 'from-blue-600 via-blue-700 to-indigo-700' },
};

function applyVoucher() {
    const code = document.getElementById('voucherInput').value.trim();
    const msgEl = document.getElementById('voucherMessage');
    const discountRow = document.getElementById('discount_row');
    const discountText = document.getElementById('discount_amount_text');
    const memDiscountRow = document.getElementById('mem_discount_row');
    const totalEl = document.getElementById('checkout_total_price');

    if (!code) {
        msgEl.classList.remove('hidden', 'text-emerald-600', 'text-red-500');
        msgEl.classList.add('text-gray-500');
        msgEl.innerText = 'Vui lòng nhập mã!';
        
        document.getElementById('cod_voucher').value = '';
        document.getElementById('tf_voucher').value = '';
        document.getElementById('vp_voucher').value = '';
        
        msgEl.classList.add('hidden');
        if (discountRow) discountRow.classList.add('hidden');
        if (totalEl) totalEl.innerHTML = new Intl.NumberFormat('vi-VN').format(originalTotal) + 'đ';
        return;
    }

    // Call API via fetch
    fetch('<?php echo URLROOT; ?>/cart/apply_voucher', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            'code': code
        })
    })
    .then(response => response.json())
    .then(data => {
        msgEl.classList.remove('hidden', 'text-gray-500', 'text-emerald-600', 'text-red-500', 'text-amber-500');
        if (data.success) {
            const discount = data.discount_amount;
            const isCombinable = data.is_combinable;
            
            let finalMemDiscount = memDiscountAmount;
            
            if (!isCombinable && memDiscountAmount > 0) {
                if (discount <= memDiscountAmount) {
                    // Reject voucher
                    msgEl.classList.add('text-amber-500');
                    msgEl.innerText = `Ưu đãi hạng thẻ (${new Intl.NumberFormat('vi-VN').format(memDiscountAmount)}đ) đang cao hơn mã này. Bạn không cần dùng mã!`;
                    
                    document.getElementById('cod_voucher').value = '';
                    document.getElementById('tf_voucher').value = '';
                    document.getElementById('vp_voucher').value = '';
                    
                    if (discountRow) discountRow.classList.add('hidden');
                    if (memDiscountRow) memDiscountRow.classList.remove('opacity-50', 'line-through');
                    if (totalEl) totalEl.innerHTML = new Intl.NumberFormat('vi-VN').format(Math.max(0, originalTotal - memDiscountAmount)) + 'đ';
                    return;
                } else {
                    // Voucher is better, disable mem discount
                    finalMemDiscount = 0;
                    if (memDiscountRow) memDiscountRow.classList.add('opacity-50', 'line-through');
                    msgEl.classList.add('text-emerald-600');
                    msgEl.innerText = `Mã không áp dụng cộng dồn. Đã chuyển sang dùng mã (giảm ${new Intl.NumberFormat('vi-VN').format(discount)}đ)!`;
                }
            } else {
                if (memDiscountRow) memDiscountRow.classList.remove('opacity-50', 'line-through');
                msgEl.classList.add('text-emerald-600');
                msgEl.innerText = `Đã áp dụng giảm ${new Intl.NumberFormat('vi-VN').format(discount)}đ!`;
            }
            
            document.getElementById('cod_voucher').value = data.code;
            document.getElementById('tf_voucher').value = data.code;
            document.getElementById('vp_voucher').value = data.code;

            const newTotal = Math.max(0, originalTotal - finalMemDiscount - discount);
            if (totalEl) totalEl.innerHTML = new Intl.NumberFormat('vi-VN').format(newTotal) + 'đ';

            if (discount > 0) {
                if (discountRow) discountRow.classList.remove('hidden');
                if (discountText) discountText.innerText = `-${new Intl.NumberFormat('vi-VN').format(discount)}đ`;
            } else {
                if (discountRow) discountRow.classList.add('hidden');
            }
        } else {
            document.getElementById('cod_voucher').value = '';
            document.getElementById('tf_voucher').value = '';
            document.getElementById('vp_voucher').value = '';

            if (totalEl) totalEl.innerHTML = new Intl.NumberFormat('vi-VN').format(Math.max(0, originalTotal - memDiscountAmount)) + 'đ';
            if (discountRow) discountRow.classList.add('hidden');
            if (memDiscountRow) memDiscountRow.classList.remove('opacity-50', 'line-through');

            msgEl.classList.add('text-red-500');
            msgEl.innerText = data.message;
        }
    })
    .catch(error => {
        console.error('Error applying voucher:', error);
    });
}

function selectMethod(method) {
    currentMethod = method;

    // Card styles
    document.querySelectorAll('.pay-card').forEach(c => {
        c.classList.remove('active');
        c.classList.replace('border-primary','border-gray-200');
        c.classList.remove('border-primary');
        c.style.borderColor = '';
    });
    const card = document.querySelector(`[data-method="${method}"]`);
    card.classList.add('active');

    // Panels
    ['cod','transfer','vnpay'].forEach(m => {
        const p = document.getElementById('panel_' + m);
        if (m === method) {
            p.classList.remove('hidden');
            p.classList.add('panel-enter');
        } else {
            p.classList.add('hidden');
        }
    });

    // Button
    const cfg = methodConfig[method];
    const btn = document.getElementById('submitBtn');
    document.getElementById('submitText').textContent = cfg.text;
    document.getElementById('submitIcon').className   = `fa-solid ${cfg.icon} text-lg`;
    btn.className = btn.className.replace(/from-\S+\s+via-\S+\s+to-\S+/, '') + ' ' + cfg.btnClass;
}

function handleSubmit() {
    // Validate
    const fullname = document.getElementById('f_fullname').value.trim();
    const phone    = document.getElementById('f_phone').value.trim();
    const address  = document.getElementById('f_address').value.trim();
    const note     = document.getElementById('f_note').value.trim();

    if (!fullname) { alert('Vui lòng nhập họ và tên!'); document.getElementById('f_fullname').focus(); return; }
    if (!phone)    { alert('Vui lòng nhập số điện thoại!'); document.getElementById('f_phone').focus(); return; }
    if (!address)  { alert('Vui lòng nhập địa chỉ giao hàng!'); document.getElementById('f_address').focus(); return; }

    // Copy values to hidden form
    const prefix = { cod:'cod', transfer:'tf', vnpay:'vp' }[currentMethod];
    document.getElementById(prefix + '_fullname').value = fullname;
    document.getElementById(prefix + '_phone').value    = phone;
    document.getElementById(prefix + '_address').value  = address;
    document.getElementById(prefix + '_note').value     = note;

    // Loading state
    const btn  = document.getElementById('submitBtn');
    const icon = document.getElementById('submitIcon');
    btn.disabled = true;
    btn.classList.add('opacity-80','cursor-not-allowed');
    icon.className = 'fa-solid fa-circle-notch fa-spin text-lg';
    document.getElementById('submitText').textContent = currentMethod === 'vnpay'
        ? 'Đang chuyển sang VNPay...'
        : 'Đang xử lý đơn hàng...';

    // Submit correct form
    document.getElementById('form_' + currentMethod).submit();
}

function copyText(text) {
    navigator.clipboard.writeText(String(text)).then(() => {
        const toast = document.getElementById('copyToast');
        toast.classList.remove('opacity-0','translate-y-4');
        toast.classList.add('opacity-100','translate-y-0');
        setTimeout(() => {
            toast.classList.add('opacity-0','translate-y-4');
            toast.classList.remove('opacity-100','translate-y-0');
        }, 2200);
    });
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
