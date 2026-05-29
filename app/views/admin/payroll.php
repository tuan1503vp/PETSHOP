<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Bảng Lương Nhân Viên</h1>
            <p class="text-sm text-gray-500">Quản lý lương thưởng tháng <?php echo $data['current_month']; ?>/<?php echo $data['current_year']; ?></p>
        </div>
        <div class="flex items-center gap-4">
            <form action="<?php echo URLROOT; ?>/admin/payroll" method="GET" class="flex items-center gap-2">
                <select name="month" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-primary/20">
                    <?php for($i=1; $i<=12; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($i == $data['current_month']) ? 'selected' : ''; ?>>Tháng <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
                <select name="year" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-primary/20">
                    <?php for($i=date('Y')-1; $i<=date('Y')+1; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($i == $data['current_year']) ? 'selected' : ''; ?>>Năm <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </form>
            <a href="<?php echo URLROOT; ?>/admin/payroll_export?month=<?php echo $data['current_month']; ?>&year=<?php echo $data['current_year']; ?>" 
               class="bg-green-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-green-700 transition shadow-lg shadow-green-500/20 flex items-center">
                <i class="fa-solid fa-file-excel mr-2"></i> Xuất File
            </a>
        </div>
    </div>

    <?php flash('payroll_success'); ?>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Nhân viên</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Lương cơ bản</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Thưởng chuyên cần</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Khấu trừ</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Thực lĩnh</th>
                    <?php if($_SESSION['user_role'] != 'admin'): ?>
                    <th class="px-8 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Thao tác</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php foreach($data['payrolls'] as $p): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-8 py-6 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-900"><?php echo $p->fullname; ?></span>
                            <span class="text-[10px] font-black uppercase text-gray-400"><?php echo $p->role; ?></span>
                        </div>
                    </td>
                    <?php $current_base = $p->base_salary ?? $p->last_base_salary ?? 0; ?>
                    <?php $total = ($current_base) + ($p->bonus ?? 0) - ($p->deductions ?? 0); ?>

                    <?php if($_SESSION['user_role'] == 'admin'): ?>
                        <!-- Admin: chỉ xem, không chỉnh sửa -->
                        <td class="px-8 py-6 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-700"><?php echo number_format($current_base, 0, ',', '.'); ?> đ</span>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <span class="text-sm font-bold text-green-600"><?php echo number_format($p->bonus ?? 0, 0, ',', '.'); ?> đ</span>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <span class="text-sm font-bold text-red-500"><?php echo number_format($p->deductions ?? 0, 0, ',', '.'); ?> đ</span>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <span class="text-sm font-black text-indigo-600"><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                        </td>
                    <?php else: ?>
                        <!-- Manager: chỉnh sửa được -->
                        <form action="<?php echo URLROOT; ?>/admin/payroll_save" method="POST" class="contents">
                            <input type="hidden" name="user_id" value="<?php echo $p->user_id; ?>">
                            <input type="hidden" name="month" value="<?php echo $data['current_month']; ?>">
                            <input type="hidden" name="year" value="<?php echo $data['current_year']; ?>">
                            <td class="px-8 py-6">
                                <input type="number" name="base_salary" value="<?php echo $current_base; ?>" 
                                       class="w-32 px-3 py-1 text-sm border border-gray-100 rounded-lg focus:border-primary outline-none font-bold text-gray-700">
                            </td>
                            <td class="px-8 py-6">
                                <input type="number" name="bonus" value="<?php echo $p->bonus ?? 0; ?>" 
                                       class="w-32 px-3 py-1 text-sm border border-gray-100 rounded-lg focus:border-primary outline-none text-green-600 font-bold">
                            </td>
                            <td class="px-8 py-6">
                                <input type="number" name="deductions" value="<?php echo $p->deductions ?? 0; ?>" 
                                       class="w-32 px-3 py-1 text-sm border border-gray-100 rounded-lg focus:border-primary outline-none text-red-500">
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-sm font-black text-indigo-600"><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <button type="submit" class="bg-indigo-50 text-primary px-4 py-2 rounded-xl text-xs font-bold hover:bg-primary hover:text-white transition">
                                    Cập nhật
                                </button>
                            </td>
                        </form>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
