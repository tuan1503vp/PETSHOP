<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6 lg:p-8" x-data="{ tab: 'pos' }">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Lịch Sử Thanh Toán</h1>
            <p class="text-sm text-gray-500">Tra cứu các khoản thu từ Đơn hàng và Dịch vụ</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-gray-200 mb-6">
        <button @click="tab = 'pos'" :class="tab === 'pos' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-bold text-sm transition-colors">
            <i class="fa-solid fa-cash-register mr-2"></i> Đơn Hàng POS / Online
        </button>
        <button @click="tab = 'service'" :class="tab === 'service' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-bold text-sm transition-colors">
            <i class="fa-solid fa-notes-medical mr-2"></i> Dịch Vụ
        </button>
    </div>

    <!-- Tab POS -->
    <div x-show="tab === 'pos'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Mã Đơn</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Khách hàng</th>
                    <?php if($_SESSION['user_role'] == 'manager'): ?>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Sản phẩm</th>
                    <?php endif; ?>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Tổng tiền</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Phương thức</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Kênh</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Thời gian</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php if(empty($data['orders'])): ?>
                <tr><td colspan="<?php echo $_SESSION['user_role'] == 'manager' ? '7' : '6'; ?>" class="px-6 py-12 text-center text-gray-400 font-bold">Không có đơn hàng nào</td></tr>
                <?php else: ?>
                    <?php foreach($data['orders'] as $order): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4"><span class="text-xs font-black text-gray-400">#<?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT); ?></span></td>
                        <td class="px-6 py-4"><span class="text-sm font-bold text-gray-700"><?php echo $order->customer_name ?? 'Khách lẻ'; ?></span></td>
                        
                        <?php if($_SESSION['user_role'] == 'manager'): ?>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <?php if(!empty($order->items)): ?>
                                    <?php foreach($order->items as $item): ?>
                                        <div class="text-[11px] text-gray-600 flex justify-between gap-4">
                                            <span class="font-medium"><?php echo $item->name; ?></span>
                                            <span class="font-black text-gray-400 text-[10px]">x<?php echo $item->quantity; ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-[10px] text-gray-400 italic">Dịch vụ lẻ</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>

                        <td class="px-6 py-4"><span class="text-sm font-black text-green-600"><?php echo number_format($order->total_amount, 0, ',', '.'); ?> đ</span></td>
                        <td class="px-6 py-4">
                            <?php 
                                $pm = $order->payment_method ?? 'cash';
                                $pmLabel = 'Tiền mặt';
                                if($pm == 'card') $pmLabel = 'Thẻ';
                                if($pm == 'transfer') $pmLabel = 'Chuyển khoản';
                                if($pm == 'vnpay') $pmLabel = 'VNPay';
                                if($pm == 'cod') $pmLabel = 'COD';
                            ?>
                            <span class="text-sm text-gray-600 font-bold"><?php echo $pmLabel; ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase <?php echo ($order->order_type == 'pos') ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'; ?>">
                                <?php echo $order->order_type == 'pos' ? 'Tại quầy' : 'Online'; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right"><span class="text-xs text-gray-500 font-bold"><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Tab Service -->
    <div x-show="tab === 'service'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Mã Lịch</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Khách hàng</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Dịch vụ</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Người thực hiện</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Thành tiền</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Thời gian lịch hẹn</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php if(empty($data['appointments'])): ?>
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 font-bold">Không có thanh toán dịch vụ nào</td></tr>
                <?php else: ?>
                    <?php foreach($data['appointments'] as $app): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4"><span class="text-xs font-black text-gray-400">#<?php echo str_pad($app->id, 5, '0', STR_PAD_LEFT); ?></span></td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-700"><?php echo $app->customer_name ?? 'Khách lẻ'; ?></span><br>
                            <span class="text-xs text-gray-500"><i class="fa-solid fa-paw mr-1"></i> <?php echo $app->pet_name ?? 'N/A'; ?></span>
                        </td>
                        <td class="px-6 py-4"><span class="text-sm font-bold text-indigo-600"><?php echo $app->service_name; ?></span></td>
                        <td class="px-6 py-4"><span class="text-sm font-medium text-gray-600"><?php echo $app->doctor_name ?? 'N/A'; ?></span></td>
                        <td class="px-6 py-4">
                            <?php 
                                $price_display = 0;
                                if ($app->final_price !== null && $app->final_price !== '') {
                                    $price_display = $app->final_price;
                                } elseif (!empty($app->service_price)) {
                                    $is_boarding = strpos(mb_strtolower($app->category_name ?? ''), 'trông giữ') !== false;
                                    $price_display = ($is_boarding) ? $app->service_price * ($app->duration_value ?: 1) : $app->service_price;
                                }
                            ?>
                            <span class="text-sm font-black text-green-600"><?php echo $price_display > 0 ? number_format($price_display, 0, ',', '.') . ' đ' : 'Miễn phí'; ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
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
