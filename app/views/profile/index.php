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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Cột trái: Thông tin User -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Avatar & Basic Info -->
                <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] text-center relative overflow-hidden border border-gray-100">
                    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br <?php echo $gradientClass; ?> opacity-20"></div>
                    
                    <div class="relative z-10">
                        <div class="w-32 h-32 mx-auto bg-gradient-to-tr <?php echo $gradientClass; ?> rounded-[2.5rem] flex items-center justify-center text-5xl font-black text-white shadow-xl mb-6 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                            <?php echo strtoupper(substr($user->fullname, 0, 1)); ?>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 mb-2"><?php echo $user->fullname; ?></h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest border <?php echo $badgeClass; ?>">
                            Hạng <?php echo $level; ?>
                        </span>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fa-solid fa-address-card mr-3 text-primary"></i> Thông tin liên hệ
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-gray-500 mr-4 shrink-0">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Email</p>
                                <p class="text-sm font-medium text-gray-800 break-all"><?php echo $user->email; ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-gray-500 mr-4 shrink-0">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Số điện thoại</p>
                                <p class="text-sm font-medium text-gray-800"><?php echo !empty($user->phone) ? $user->phone : '<span class="text-gray-400 italic">Chưa cập nhật</span>'; ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-gray-500 mr-4 shrink-0">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Địa chỉ</p>
                                <p class="text-sm font-medium text-gray-800"><?php echo !empty($user->address) ? $user->address : '<span class="text-gray-400 italic">Chưa cập nhật</span>'; ?></p>
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
