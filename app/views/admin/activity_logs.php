<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6 max-w-7xl mx-auto">
    <!-- Header Title & Stats Card -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gradient-to-r from-darker via-dark to-slate-900 p-6 rounded-2xl shadow-xl border border-white/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-primary/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div>
            <h1 class="text-2xl font-black text-white flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-primary"></i> Nhật Ký Hành Vi Hệ Thống
            </h1>
            <p class="text-gray-400 text-xs mt-1">Giám sát hoạt động thời gian thực của Quản trị viên, Quản lý và Thu ngân.</p>
        </div>
        
        <!-- Search and Filter Form -->
        <form action="<?php echo URLROOT; ?>/admin/activity_logs" method="GET" class="w-full md:w-auto flex gap-2">
            <div class="relative flex-1 md:w-72">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="<?php echo htmlspecialchars($data['search']); ?>" 
                       class="w-full pl-9 pr-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs text-white placeholder-gray-400 focus:outline-none focus:border-primary transition"
                       placeholder="Tìm theo tài khoản, hành động...">
            </div>
            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/95 text-white text-xs font-bold rounded-xl transition shadow-md shadow-primary/20">
                Lọc
            </button>
            <?php if(!empty($data['search'])): ?>
                <a href="<?php echo URLROOT; ?>/admin/activity_logs" class="px-4 py-2 bg-white/10 hover:bg-white/15 text-white text-xs font-bold rounded-xl transition flex items-center">
                    Reset
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Logs Table Container -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-150 text-[10px] font-black uppercase text-gray-400 tracking-wider">
                        <th class="px-6 py-4">Thời gian</th>
                        <th class="px-6 py-4">Tài khoản</th>
                        <th class="px-6 py-4">Vai trò</th>
                        <th class="px-6 py-4">Hành động</th>
                        <th class="px-6 py-4">Chi tiết chi tiết hoạt động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                    <?php if(empty($data['logs'])): ?>
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-400 font-medium">
                                <i class="fa-regular fa-folder-open text-3xl mb-2 block"></i>
                                Không tìm thấy hoạt động nào phù hợp.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['logs'] as $log): ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-400 font-medium">
                                    <?php echo date('d/m/Y H:i:s', strtotime($log->created_at)); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-800">
                                    <?php echo htmlspecialchars($log->username); ?>
                                    <span class="block text-[10px] text-gray-400 font-normal">ID: #<?php echo $log->user_id ?? 'N/A'; ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                        $role_classes = [
                                            'admin' => 'bg-red-50 text-red-600 border-red-100',
                                            'manager' => 'bg-purple-50 text-purple-600 border-purple-100',
                                            'cashier' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'doctor' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'staff' => 'bg-orange-50 text-orange-600 border-orange-100'
                                        ];
                                        $role_label = [
                                            'admin' => 'Administrator',
                                            'manager' => 'Quản lý',
                                            'cashier' => 'Thu ngân',
                                            'doctor' => 'Bác sĩ',
                                            'staff' => 'Nhân viên'
                                        ];
                                        $role = strtolower($log->role ?? '');
                                        $class = $role_classes[$role] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                        $label = $role_label[$role] ?? 'Khách';
                                    ?>
                                    <span class="inline-flex px-2 py-0.5 border rounded-full text-[10px] font-black uppercase <?php echo $class; ?>">
                                        <?php echo $label; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                        $action_classes = [
                                            'Đăng nhập' => 'bg-emerald-500 text-white',
                                            'Đăng xuất' => 'bg-slate-400 text-white',
                                            'Thanh toán POS' => 'bg-primary text-white shadow-md shadow-primary/20',
                                            'Đặt lịch' => 'bg-amber-500 text-white',
                                            'Tạo mới' => 'bg-blue-500 text-white',
                                            'Cập nhật' => 'bg-indigo-500 text-white',
                                            'Xóa bỏ' => 'bg-red-500 text-white'
                                        ];
                                        $action = $log->action;
                                        $btn_class = 'bg-gray-100 text-gray-700';
                                        foreach($action_classes as $key => $val) {
                                            if(strpos($action, $key) !== false) {
                                                $btn_class = $val;
                                                break;
                                            }
                                        }
                                    ?>
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?php echo $btn_class; ?>">
                                        <?php echo htmlspecialchars($action); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 max-w-md font-medium text-gray-600 leading-relaxed break-words whitespace-pre-wrap"><?php echo htmlspecialchars($log->details); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
