<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
    $user = $data['user'];
    $stats = $data['membership']['stats'];
    $member = $data['membership']['member'];
    
    $level = $member->membership_level ?? 'Đồng';
    
    $badgeClass = "bg-orange-100 text-orange-700 border-orange-200"; 
    $gradientClass = "from-orange-400 to-orange-600";
    if ($level == 'Bạc') { $badgeClass = "bg-slate-100 text-slate-700 border-slate-300"; $gradientClass = "from-slate-400 to-slate-600"; }
    if ($level == 'Vàng') { $badgeClass = "bg-yellow-100 text-yellow-700 border-yellow-300"; $gradientClass = "from-yellow-400 to-amber-500"; }
    if ($level == 'Bạch kim') { $badgeClass = "bg-blue-100 text-blue-700 border-blue-300"; $gradientClass = "from-cyan-500 to-blue-600"; }
    if ($level == 'VIP') { $badgeClass = "bg-purple-100 text-purple-700 border-purple-300 animate-pulse"; $gradientClass = "from-purple-500 to-pink-500"; }

    $annual_spent = $stats->annual_spent ?? 0;
    $total_spent = $stats->total_spent ?? 0;
    
    $next_level = '';
    $level_min = 0;
    $level_max = 0;

    if ($level === 'Đồng') {
        $next_level = 'Bạc';
        $level_min = 0;
        $level_max = 1000000;
    } elseif ($level === 'Bạc') {
        $next_level = 'Vàng';
        $level_min = 1000000;
        $level_max = 5000000;
    } elseif ($level === 'Vàng') {
        $next_level = 'Bạch kim';
        $level_min = 5000000;
        $level_max = 10000000;
    } elseif ($level === 'Bạch kim') {
        $next_level = 'VIP';
        $level_min = 10000000;
        $level_max = 10000000; // VIP is currently max logic, wait VIP requires 10m/yr + constraints, but visually let's show it as maxed
    }

    if ($level_max > $level_min) {
        $progress_percent = (($annual_spent - $level_min) / ($level_max - $level_min)) * 100;
        $progress_percent = max(0, min(100, $progress_percent));
        $needed_amount = $level_max - $annual_spent;
    } else {
        $progress_percent = 100;
        $needed_amount = 0;
    }
?>

<div class="bg-slate-50 min-h-[calc(100vh-64px)] py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Hồ sơ cá nhân</h1>
        </div>
        <div class="mb-6">
            <?php flash('profile_success'); flash('profile_err'); ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Cột trái: Thông tin User -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Avatar & Basic Info -->
                <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] text-center relative overflow-hidden border border-gray-100">
                    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br <?php echo $gradientClass; ?> opacity-20"></div>
                    
                    <div class="relative z-10 group">
                        <!-- Hiển thị Avatar -->
                        <?php if (!empty($user->avatar)): ?>
                            <img src="<?php echo URLROOT; ?>/public/uploads/avatars/<?php echo $user->avatar; ?>" class="w-32 h-32 mx-auto rounded-[2.5rem] object-cover shadow-xl mb-6 transform group-hover:scale-105 transition-transform duration-300">
                        <?php else: ?>
                            <div class="w-32 h-32 mx-auto bg-gradient-to-tr <?php echo $gradientClass; ?> rounded-[2.5rem] flex items-center justify-center text-5xl font-black text-white shadow-xl mb-6 transform group-hover:scale-105 transition-transform duration-300">
                                <?php echo strtoupper(substr($user->fullname, 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Form Đổi Avatar ẩn -->
                        <form id="avatarForm" action="<?php echo URLROOT; ?>/profile/update" method="POST" enctype="multipart/form-data" class="hidden">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                            <input type="hidden" name="fullname" value="<?php echo $user->fullname; ?>">
                            <input type="hidden" name="phone" value="<?php echo $user->phone ?? ''; ?>">
                            <input type="hidden" name="address" value="<?php echo $user->address ?? ''; ?>">
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="document.getElementById('avatarForm').submit();">
                        </form>
                        
                        <button type="button" onclick="document.getElementById('avatarInput').click();" class="absolute top-[80px] right-1/2 translate-x-12 bg-white text-gray-800 p-2 rounded-full shadow-lg hover:bg-indigo-50 hover:text-indigo-600 transition-colors opacity-0 group-hover:opacity-100">
                            <i class="fa-solid fa-camera"></i>
                        </button>

                        <h2 class="text-2xl font-black text-gray-900 mb-2"><?php echo $user->fullname; ?></h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest border <?php echo $badgeClass; ?>">
                            Hạng <?php echo $level; ?>
                        </span>
                    </div>
                </div>

                <!-- Contact Info Form -->
                <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fa-solid fa-address-card mr-3 text-primary"></i> Thông tin liên hệ
                    </h3>
                    
                    <form action="<?php echo URLROOT; ?>/profile/update" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        <div class="space-y-4">
                            <!-- Email (Disabled) -->
                            <div>
                                <label class="block text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Email</label>
                                <div class="relative">
                                    <input type="email" value="<?php echo $user->email; ?>" disabled class="w-full bg-slate-50 border border-slate-200 text-gray-500 rounded-xl px-4 py-3 focus:outline-none cursor-not-allowed">
                                    <i class="fa-solid fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>
                            
                            <!-- Họ Tên -->
                            <div>
                                <label class="block text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Họ tên</label>
                                <input type="text" name="fullname" value="<?php echo $user->fullname; ?>" required class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition">
                            </div>

                            <!-- Số điện thoại -->
                            <div>
                                <label class="block text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Số điện thoại</label>
                                <input type="text" name="phone" value="<?php echo $user->phone ?? ''; ?>" placeholder="Nhập số điện thoại" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition">
                            </div>

                            <!-- Địa chỉ -->
                            <div>
                                <label class="block text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Địa chỉ</label>
                                <textarea name="address" rows="2" placeholder="Nhập địa chỉ" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition"><?php echo $user->address ?? ''; ?></textarea>
                            </div>
                            
                            <button type="submit" class="w-full mt-2 bg-gradient-to-r from-primary to-secondary text-white font-bold rounded-xl px-4 py-3 hover:shadow-lg hover:-translate-y-0.5 transition-all">Lưu Thông Tin</button>
                        </div>
                    </form>
                </div>
                
                <!-- Security / Change Password -->
                <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100" x-data="passwordModal()">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fa-solid fa-shield-halved mr-3 text-emerald-500"></i> Bảo mật
                    </h3>
                    <button @click="openModal()" type="button" class="w-full flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-indigo-500 hover:bg-indigo-50 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-700">Đổi mật khẩu</p>
                                <p class="text-xs text-gray-500">Xác thực qua OTP Email</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-indigo-500"></i>
                    </button>
                    
                    <!-- Password Modal -->
                    <div x-show="isOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-transition>
                        <div @click.away="closeModal()" class="bg-white rounded-3xl p-8 max-w-md w-full mx-4 shadow-2xl relative">
                            <button @click="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 w-8 h-8 rounded-full flex items-center justify-center transition">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            
                            <h2 class="text-2xl font-black text-gray-900 mb-2">Đổi Mật Khẩu</h2>
                            <p class="text-sm text-gray-500 mb-6">Mã OTP sẽ được gửi đến email <strong class="text-gray-800"><?php echo $user->email; ?></strong></p>
                            
                            <!-- Step 1: Send OTP -->
                            <div x-show="step === 1">
                                <form @submit.prevent="requestOTP">
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Mật khẩu mới</label>
                                        <input type="password" x-model="newPassword" required minlength="6" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition" placeholder="Nhập mật khẩu mới">
                                    </div>
                                    <button type="submit" :disabled="isLoading" class="w-full bg-slate-900 text-white font-bold rounded-xl px-4 py-3 hover:bg-primary transition disabled:opacity-70">
                                        <span x-show="!isLoading">Gửi mã OTP Xác Nhận</span>
                                        <span x-show="isLoading"><i class="fa-solid fa-spinner fa-spin"></i> Đang gửi...</span>
                                    </button>
                                </form>
                            </div>

                            <!-- Step 2: Verify OTP -->
                            <div x-show="step === 2">
                                <form action="<?php echo URLROOT; ?>/profile/verify_password_change" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                    <input type="hidden" name="new_password" :value="newPassword">
                                    <div class="mb-4 text-center">
                                        <div class="w-16 h-16 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                                            <i class="fa-regular fa-envelope-open"></i>
                                        </div>
                                        <p class="text-emerald-600 font-bold mb-4" x-text="message"></p>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 text-left">Nhập mã OTP 6 số</label>
                                        <input type="text" name="otp" required maxlength="6" pattern="\d{6}" class="w-full bg-white border-2 border-slate-200 focus:border-emerald-500 text-center text-2xl tracking-widest font-black rounded-xl px-4 py-3 outline-none transition" placeholder="------">
                                    </div>
                                    <button type="submit" class="w-full bg-emerald-500 text-white font-bold rounded-xl px-4 py-3 hover:bg-emerald-600 transition">
                                        Xác Nhận Đổi Mật Khẩu
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Membership Progress & Stats -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Tiến trình hạng -->
                <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                    <div class="flex justify-between items-end mb-8">
                        <div>
                            <h3 class="text-xl font-black text-gray-900 mb-2 flex items-center">
                                <i class="fa-solid fa-ranking-star mr-3 text-yellow-500"></i> Tiến trình thăng hạng
                            </h3>
                            <p class="text-sm text-gray-500 font-medium">Chi tiêu trong năm nay để xét hạng</p>
                        </div>
                        <?php if(!empty($next_level)): ?>
                            <div class="text-right">
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Mục tiêu tiếp theo</p>
                                <span class="inline-block bg-primary/10 text-primary font-black px-3 py-1.5 rounded-lg text-sm">
                                    Hạng <?php echo $next_level; ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="text-right">
                                <span class="inline-block bg-purple-100 text-purple-600 font-black px-3 py-1.5 rounded-lg text-sm animate-pulse">
                                    Hạng Đỉnh Cao 👑
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="relative pt-8 pb-4">
                        <!-- Progress Track -->
                        <div class="w-full bg-slate-100 h-4 rounded-full overflow-hidden shadow-inner relative z-0">
                            <div class="h-full bg-gradient-to-r <?php echo $gradientClass; ?> rounded-full relative" style="width: <?php echo $progress_percent; ?>%">
                                <div class="absolute inset-0 bg-white/20" style="background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;"></div>
                            </div>
                        </div>

                        <!-- Markers (Mốc hạng) -->
                        <div class="absolute top-0 w-full flex justify-between text-[10px] font-black uppercase tracking-widest text-gray-400 px-1">
                            <div class="flex flex-col items-center">
                                <span class="mb-2">Đồng</span>
                                <div class="h-4 w-0.5 bg-gray-200"></div>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="mb-2 text-slate-500">Bạc (1M)</span>
                                <div class="h-4 w-0.5 bg-gray-200"></div>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="mb-2 text-yellow-500">Vàng (5M)</span>
                                <div class="h-4 w-0.5 bg-gray-200"></div>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="mb-2 text-blue-500">B.Kim (10M)</span>
                                <div class="h-4 w-0.5 bg-gray-200"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div>
                            <p class="text-xs text-gray-500 font-bold mb-1">Đã chi tiêu (năm nay)</p>
                            <p class="text-xl font-black text-gray-900"><?php echo number_format($annual_spent, 0, ',', '.'); ?> ₫</p>
                        </div>
                        
                        <div class="text-right">
                            <?php if(!empty($next_level)): ?>
                                <p class="text-xs text-gray-500 font-bold mb-1">Cần chi tiêu thêm</p>
                                <p class="text-xl font-black text-primary"><?php echo number_format($needed_amount, 0, ',', '.'); ?> ₫</p>
                            <?php else: ?>
                                <p class="text-sm font-black text-purple-600">Bạn đã đạt hạng cao nhất!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Thống kê & Đặc quyền -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tổng quan -->
                    <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                        <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Tổng quan tài khoản</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                                <span class="text-sm font-medium text-gray-600">Tổng chi tiêu trọn đời</span>
                                <span class="font-black text-gray-900"><?php echo number_format($total_spent, 0, ',', '.'); ?> ₫</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                                <span class="text-sm font-medium text-gray-600">Chi tiêu tháng này</span>
                                <span class="font-black text-gray-900"><?php echo number_format($stats->monthly_spent ?? 0, 0, ',', '.'); ?> ₫</span>
                            </div>
                            <a href="<?php echo URLROOT; ?>/order/history" class="block w-full py-3 text-center text-sm font-bold text-secondary hover:text-pink-600 transition bg-pink-50 hover:bg-pink-100 rounded-xl mt-4">
                                Xem lịch sử mua sắm <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Đặc quyền -->
                    <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 text-9xl opacity-5 text-gray-800 pointer-events-none">
                            <i class="fa-solid fa-crown"></i>
                        </div>
                        <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6 relative z-10">Đặc quyền hiện tại</h3>
                        
                        <div class="prose prose-sm prose-pink relative z-10 text-gray-600 font-medium leading-relaxed">
                            <?php 
                                if (!empty($member->benefit_text)) {
                                    echo nl2br($member->benefit_text);
                                } else {
                                    echo "Mua sắm và sử dụng dịch vụ tại PETSHOP để tích lũy chi tiêu và nhận nhiều ưu đãi hấp dẫn!";
                                }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
    function passwordModal() {
        return {
            isOpen: false,
            step: 1,
            isLoading: false,
            newPassword: '',
            message: '',
            openModal() {
                this.isOpen = true;
                this.step = 1;
                this.newPassword = '';
            },
            closeModal() {
                this.isOpen = false;
            },
            async requestOTP() {
                if(this.newPassword.length < 6) {
                    alert('Mật khẩu phải từ 6 ký tự trở lên!');
                    return;
                }
                this.isLoading = true;
                try {
                    const formData = new FormData();
                    formData.append('csrf_token', '<?php echo $_SESSION['csrf_token'] ?? ''; ?>');
                    
                    const res = await fetch('<?php echo URLROOT; ?>/profile/send_password_otp', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();
                    if(data.status === 'success') {
                        this.step = 2;
                        this.message = data.message;
                    } else {
                        alert(data.message);
                    }
                } catch(e) {
                    alert('Lỗi kết nối. Vui lòng thử lại!');
                } finally {
                    this.isLoading = false;
                }
            }
        }
    }
</script>
