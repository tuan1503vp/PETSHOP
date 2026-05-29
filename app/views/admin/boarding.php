<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Bảng Quản Lý Trông Giữ</h1>
        <div class="flex space-x-2">
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold flex items-center">
                <i class="fa-solid fa-clock mr-1"></i> Ngắn hạn: 20k/giờ
            </span>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold flex items-center">
                <i class="fa-solid fa-calendar-day mr-1"></i> Dài hạn: 50k/ngày
            </span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng & Thú cưng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại dịch vụ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian đăng ký</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(empty($data['boarding_appts'])): ?>
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Chưa có dữ liệu trông giữ nào.</td>
                </tr>
                <?php else: ?>
                    <?php foreach($data['boarding_appts'] as $appt): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900"><?php echo $appt->customer_name; ?></div>
                            <div class="text-xs text-gray-500"><?php echo $appt->pet_name ?? 'Chưa xác định'; ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium"><?php echo $appt->service_name; ?></div>
                            <div class="text-xs text-blue-600">
                                <?php 
                                    if($appt->duration_unit == 'hour') echo 'Ngắn hạn (' . $appt->duration_value . ' giờ)';
                                    elseif($appt->duration_unit == 'day') echo 'Dài hạn (' . $appt->duration_value . ' ngày)';
                                    else echo 'Chưa rõ thời gian';
                                ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo date('d/m/Y', strtotime($appt->appointment_date)); ?></div>
                            <div class="text-xs text-gray-500"><?php echo $appt->appointment_time; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($appt->status == 'pending'): ?>
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">Chờ xác nhận</span>
                            <?php elseif($appt->status == 'confirmed'): ?>
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">Đang trông giữ</span>
                            <?php elseif($appt->status == 'completed'): ?>
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">Đã hoàn thành</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800"><?php echo $appt->status; ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                             <a href="<?php echo URLROOT; ?>/admin/service_detail/<?php echo $appt->id; ?>" class="inline-flex items-center text-primary hover:underline text-xs font-bold" title="Chi tiết">
                                <i class="fa-solid fa-eye mr-1"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
