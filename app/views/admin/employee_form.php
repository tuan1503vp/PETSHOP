<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="mb-8">
        <a href="<?php echo URLROOT; ?>/admin/employees" class="text-sm text-gray-500 hover:text-primary transition flex items-center mb-2">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại danh sách
        </a>
        <h1 class="text-2xl font-black text-gray-800">Thêm Nhân Viên Mới</h1>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?php echo URLROOT; ?>/admin/employee_add" method="POST" enctype="multipart/form-data" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Thông tin tài khoản -->
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-2">Thông tin tài khoản</h3>
                    
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-400 uppercase mb-2">Email Đăng Nhập <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-400 uppercase mb-2">Mật Khẩu <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50">
                    </div>

                    <div>
                        <label for="role" class="block text-xs font-bold text-gray-400 uppercase mb-2">Vai Trò / Chức Vụ <span class="text-red-500">*</span></label>
                        <select name="role" id="role" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50 appearance-none">
                            <?php if($_SESSION['user_role'] == 'admin'): ?>
                            <option value="manager">Quản lý (Personnel)</option>
                            <?php endif; ?>
                            <option value="doctor">Bác sĩ Thú y</option>
                            <option value="staff" selected>Nhân viên thường (Hỗ trợ)</option>
                            <option value="cashier">Thu ngân (Cashier)</option>
                        </select>
                    </div>
                </div>

                <!-- Thông tin cá nhân -->
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-2">Thông tin cá nhân</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="employee_code" class="block text-xs font-bold text-gray-400 uppercase mb-2">Mã Nhân Viên <span class="text-red-500">*</span></label>
                            <input type="text" name="employee_code" id="employee_code" required placeholder="NV001"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50">
                        </div>
                        <div>
                            <label for="fullname" class="block text-xs font-bold text-gray-400 uppercase mb-2">Họ Và Tên <span class="text-red-500">*</span></label>
                            <input type="text" name="fullname" id="fullname" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50">
                        </div>
                    </div>

                    <div>
                        <label for="cccd" class="block text-xs font-bold text-gray-400 uppercase mb-2">Số CCCD / CMND</label>
                        <input type="text" name="cccd" id="cccd"
                               class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50">
                    </div>

                    <div>
                        <label for="address" class="block text-xs font-bold text-gray-400 uppercase mb-2">Địa Chỉ Thường Trú</label>
                        <input type="text" name="address" id="address"
                               class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50">
                    </div>

                    <div>
                        <label for="image" class="block text-xs font-bold text-gray-400 uppercase mb-2">Ảnh Đại Diện</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full px-4 py-2 rounded-xl border border-dashed border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-primary hover:file:bg-indigo-100">
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end gap-4 border-t border-gray-50 pt-8">
                <button type="reset" class="px-8 py-3 rounded-2xl text-sm font-bold text-gray-400 hover:text-gray-600 transition">Xóa biểu mẫu</button>
                <button type="submit" class="px-12 py-3 bg-primary text-white rounded-2xl font-black shadow-lg shadow-primary/20 hover:bg-indigo-700 transition">
                    Lưu Nhân Viên
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
