<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Quản lý Khách hàng</h1>
            <p class="text-sm text-gray-500">Danh sách các tài khoản khách hàng đã đăng ký trên hệ thống</p>
        </div>
        <div class="bg-indigo-50 text-primary px-4 py-2 rounded-xl text-sm font-bold border border-indigo-100">
            Tổng cộng: <?php echo count($data['customers']); ?> khách hàng
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Khách hàng</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Email</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Điện thoại</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Địa chỉ</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Tổng chi</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Hạng</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Ngày tham gia</th>
                    <th class="px-8 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php if(empty($data['customers'])): ?>
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-users-slash text-5xl text-gray-200 mb-4"></i>
                                <p class="text-gray-400 font-medium">Chưa có khách hàng nào đăng ký</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['customers'] as $customer): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 text-primary flex items-center justify-center font-black text-sm mr-3">
                                        <?php echo strtoupper(substr($customer->fullname, 0, 1)); ?>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900"><?php echo $customer->fullname; ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-sm text-gray-600"><?php echo $customer->email; ?></span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-sm text-gray-600"><?php echo !empty($customer->phone) ? $customer->phone : '---'; ?></span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm text-gray-600 line-clamp-1 max-w-xs" title="<?php echo $customer->address; ?>">
                                    <?php echo !empty($customer->address) ? $customer->address : 'Chưa cập nhật'; ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-sm font-black text-green-600 block">
                                    <?php echo number_format($customer->total_spent, 0, ',', '.'); ?> đ
                                </span>
                                <span class="text-[10px] text-gray-400">Năm nay: <?php echo number_format($customer->annual_spent, 0, ',', '.'); ?>đ</span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <?php 
                                    $level = $customer->membership_level ?? 'Đồng';
                                    $badgeClass = "bg-orange-100 text-orange-700"; // Đồng
                                    if ($level == 'Bạc') $badgeClass = "bg-slate-100 text-slate-700";
                                    if ($level == 'Vàng') $badgeClass = "bg-yellow-100 text-yellow-700";
                                    if ($level == 'Bạch kim') $badgeClass = "bg-blue-100 text-blue-700";
                                    if ($level == 'VIP') $badgeClass = "bg-purple-100 text-purple-700 animate-pulse border border-purple-200";
                                ?>
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?php echo $badgeClass; ?>">
                                    <?php echo $level; ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-xs font-bold text-gray-400">
                                    <?php echo date('d/m/Y', strtotime($customer->created_at)); ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="text-gray-400 hover:text-primary transition" title="Xem lịch sử mua hàng">
                                        <i class="fa-solid fa-receipt text-lg"></i>
                                    </button>
                                    <form action="<?php echo URLROOT; ?>/admin/customer_delete/<?php echo $customer->id; ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khách hàng này? Thao tác này không thể hoàn tác.')">
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Xóa tài khoản">
                                            <i class="fa-solid fa-trash-can text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
