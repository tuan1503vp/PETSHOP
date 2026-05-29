<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6 lg:p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Báo Cáo Năng Suất Cá Nhân</h1>
            <p class="text-sm text-gray-500">Thông tin chuyên cần, lương và dịch vụ đã làm trong tháng <?php echo $data['current_month']; ?>/<?php echo $data['current_year']; ?></p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
        <form action="<?php echo URLROOT; ?>/admin/personal_report" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-black text-gray-400 uppercase mb-2">Tháng</label>
                <select name="month" onchange="this.form.submit()" class="w-full px-4 py-2 rounded-xl border border-gray-100 outline-none focus:ring-2 focus:ring-primary/20 bg-gray-50/50">
                    <?php for($i=1; $i<=12; $i++): ?>
                        <option value="<?php echo sprintf('%02d', $i); ?>" <?php echo ($i == $data['current_month']) ? 'selected' : ''; ?>>Tháng <?php echo $i; ?></option>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 1. Bảng Lương -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full">
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-black text-gray-800 uppercase tracking-widest"><i class="fa-solid fa-money-check-dollar text-primary mr-2"></i> Thông Tin Lương</h2>
                </div>
                <div class="p-6">
                    <?php if($data['payroll']): ?>
                        <?php 
                            $base = $data['payroll']->base_salary ?? 0;
                            $bonus = $data['payroll']->bonus ?? 0;
                            $deductions = $data['payroll']->deductions ?? 0;
                            $total = $base + $bonus - $deductions;
                        ?>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                                <span class="text-sm font-bold text-gray-500">Lương cơ bản</span>
                                <span class="font-black text-gray-800"><?php echo number_format($base, 0, ',', '.'); ?>đ</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                                <span class="text-sm font-bold text-gray-500">Thưởng</span>
                                <span class="font-black text-green-500">+ <?php echo number_format($bonus, 0, ',', '.'); ?>đ</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                                <span class="text-sm font-bold text-gray-500">Khấu trừ (Phạt)</span>
                                <span class="font-black text-red-500">- <?php echo number_format($deductions, 0, ',', '.'); ?>đ</span>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-base font-black text-gray-800 uppercase">Thực Lãnh</span>
                                <span class="text-2xl font-black text-primary"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <i class="fa-solid fa-file-invoice-dollar text-4xl text-gray-200 mb-4 block"></i>
                            <span class="text-sm font-bold text-gray-400">Chưa có bảng lương tháng này</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 2. Chấm Công -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-sm font-black text-gray-800 uppercase tracking-widest"><i class="fa-solid fa-calendar-check text-green-500 mr-2"></i> Tổng Hợp Chấm Công</h2>
                    <div class="flex gap-4 text-xs font-bold text-gray-500">
                        <span class="text-green-500"><i class="fa-solid fa-circle text-[8px] mr-1"></i><?php echo $data['attendance_stats']['present'] ?? 0; ?> Có mặt</span>
                        <span class="text-orange-500"><i class="fa-solid fa-circle text-[8px] mr-1"></i><?php echo $data['attendance_stats']['late'] ?? 0; ?> Đi muộn</span>
                        <span class="text-red-500"><i class="fa-solid fa-circle text-[8px] mr-1"></i><?php echo $data['attendance_stats']['absent'] ?? 0; ?> Nghỉ không phép</span>
                    </div>
                </div>
                <div class="p-6 overflow-x-auto flex-1">
                    <div class="flex flex-wrap gap-2">
                        <?php foreach($data['dates'] as $date): ?>
                            <?php 
                                $status = $data['matrix'][$date] ?? null;
                                $color = 'bg-gray-100 text-gray-400';
                                if($status == 'present') $color = 'bg-green-100 text-green-700 border-green-200';
                                elseif($status == 'late') $color = 'bg-orange-100 text-orange-700 border-orange-200';
                                elseif($status == 'absent') $color = 'bg-red-100 text-red-700 border-red-200';
                                elseif($status == 'on_leave') $color = 'bg-blue-100 text-blue-700 border-blue-200';
                            ?>
                            <div class="flex flex-col items-center justify-center w-12 h-14 rounded-xl border <?php echo $color; ?> cursor-help transition-transform hover:scale-110" title="<?php echo ($status ? $status : 'Chưa chấm công'); ?>">
                                <span class="text-[10px] font-bold uppercase mb-1"><?php echo date('D', strtotime($date)); ?></span>
                                <span class="text-lg font-black leading-none"><?php echo date('d', strtotime($date)); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Dịch Vụ Đã Làm -->
    <div class="mt-8 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-sm font-black text-gray-800 uppercase tracking-widest"><i class="fa-solid fa-list-check text-indigo-500 mr-2"></i> Dịch Vụ Đã Hoàn Thành (<?php echo count($data['appointments']); ?>)</h2>
        </div>
        <div class="overflow-x-auto">
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
                    <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold">Không có dữ liệu dịch vụ trong tháng này</td></tr>
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

</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
