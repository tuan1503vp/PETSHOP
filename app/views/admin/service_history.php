<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6 lg:p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Lịch Sử Công Việc / Dịch Vụ</h1>
            <p class="text-sm text-gray-500">Thống kê các dịch vụ mà bạn đã thực hiện hoàn tất</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
        <form action="<?php echo URLROOT; ?>/admin/service_history" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-black text-gray-400 uppercase mb-2">Tháng</label>
                <select name="month" onchange="this.form.submit()" class="w-full px-4 py-2 rounded-xl border border-gray-100 outline-none focus:ring-2 focus:ring-primary/20 bg-gray-50/50">
                    <?php for($i=1; $i<=12; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($i == $data['current_month']) ? 'selected' : ''; ?>>Tháng <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-black text-gray-400 uppercase mb-2">Năm</label>
                <select name="year" onchange="this.form.submit()" class="w-full px-4 py-2 rounded-xl border border-gray-100 outline-none focus:ring-2 focus:ring-primary/20 bg-gray-50/50">
                    <?php for($i=date('Y')-2; $i<=date('Y'); $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($i == $data['current_year']) ? 'selected' : ''; ?>>Năm <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-xl font-black hover:bg-indigo-700 transition shadow-lg shadow-primary/20 flex items-center">
                    <i class="fa-solid fa-filter mr-2"></i> Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Mã Y/C</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Khách hàng</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Dịch vụ</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Thời gian hoàn thành</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php if(empty($data['appointments'])): ?>
                <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold">Không có dữ liệu trong khoảng thời gian này</td></tr>
                <?php else: ?>
                    <?php foreach($data['appointments'] as $app): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4"><span class="text-xs font-black text-gray-400">#<?php echo str_pad($app->id, 5, '0', STR_PAD_LEFT); ?></span></td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-700"><?php echo $app->customer_name; ?></span><br>
                            <span class="text-xs text-gray-500"><i class="fa-solid fa-paw mr-1"></i> <?php echo $app->pet_name ?? 'N/A'; ?></span>
                        </td>
                        <td class="px-6 py-4"><span class="text-sm font-bold text-indigo-600"><?php echo $app->service_name; ?></span></td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-800"><?php echo date('H:i', strtotime($app->appointment_time)); ?></span><br>
                            <span class="text-xs text-gray-500 font-bold"><?php echo date('d/m/Y', strtotime($app->appointment_date)); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
