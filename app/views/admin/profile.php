<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-800">Cài Đặt Tài Khoản</h1>
        <p class="text-sm text-gray-500">Xem thông tin cá nhân và quản lý bảo mật</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Thông tin cơ bản -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center">
                <div class="mb-6 relative inline-block">
                    <?php if($data['employee'] && $data['employee']->image): ?>
                        <img src="<?php echo URLROOT; ?>/public/images/employees/<?php echo $data['employee']->image; ?>" 
                             class="h-32 w-32 rounded-3xl object-cover border-4 border-white shadow-xl mx-auto">
                    <?php else: ?>
                        <div class="h-32 w-32 rounded-3xl bg-indigo-50 text-primary flex items-center justify-center text-4xl font-black mx-auto">
                            <?php echo strtoupper(substr($data['user']->fullname, 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h2 class="text-xl font-black text-gray-800"><?php echo $data['user']->fullname; ?></h2>
                <p class="text-sm font-bold text-indigo-500 uppercase tracking-widest mt-1"><?php echo $data['user']->role; ?></p>
                
                <div class="mt-8 pt-8 border-t border-gray-50 text-left space-y-4">
                    <div class="flex items-center text-gray-500">
                        <i class="fa-solid fa-envelope w-6 text-indigo-300"></i>
                        <span class="text-sm"><?php echo $data['user']->email; ?></span>
                    </div>
                    <?php if($data['employee']): ?>
                        <div class="flex items-center text-gray-500">
                            <i class="fa-solid fa-id-card w-6 text-indigo-300"></i>
                            <span class="text-sm">Mã NV: <?php echo $data['employee']->employee_code; ?></span>
                        </div>
                        <div class="flex items-center text-gray-500">
                            <i class="fa-solid fa-address-card w-6 text-indigo-300"></i>
                            <span class="text-sm">CCCD: <?php echo $data['employee']->cccd; ?></span>
                        </div>
                        <div class="flex items-center text-gray-500">
                            <i class="fa-solid fa-location-dot w-6 text-indigo-300"></i>
                            <span class="text-sm line-clamp-2"><?php echo $data['employee']->address; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Đổi mật khẩu -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-lg font-black text-gray-800 mb-6 flex items-center">
                    <i class="fa-solid fa-shield-halved mr-2 text-primary"></i>
                    Đổi mật khẩu bảo mật
                </h3>

                <?php flash('profile_success'); ?>
                <?php flash('profile_error'); ?>

                <form action="<?php echo URLROOT; ?>/admin/profile_update_password" method="POST" class="space-y-6">
                    <div class="max-w-md">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Mật khẩu mới</label>
                            <input type="password" name="new_password" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Xác nhận mật khẩu mới</label>
                            <input type="password" name="confirm_password" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50 flex justify-end">
                        <button type="submit" class="bg-primary text-white px-8 py-3 rounded-2xl font-black shadow-lg shadow-primary/20 hover:bg-indigo-700 transition">
                            Cập Nhật Mật Khẩu
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quyền hạn & Trạng thái -->
            <div class="mt-8 p-6 rounded-3xl bg-indigo-50/50 border border-indigo-100">
                <h4 class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-2">Ghi chú bảo mật</h4>
                <p class="text-sm text-indigo-400">Tài khoản của bạn có quyền truy cập cấp <strong><?php echo strtoupper($data['user']->role); ?></strong>. Hãy đảm bảo mật khẩu có ít nhất 8 ký tự và bao gồm cả chữ và số để bảo vệ hệ thống.</p>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
