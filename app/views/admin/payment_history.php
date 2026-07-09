<?php require APPROOT . '/views/admin/header.php'; ?>
<?php
$filter_type = 'all';
$filter_val = '';
if (!empty($data['filter_date'])) {
    $len = strlen($data['filter_date']);
    if ($len == 10) {
        $filter_type = 'day';
        $filter_val = $data['filter_date'];
    } elseif ($len == 7) {
        $filter_type = 'month';
        $filter_val = $data['filter_date'];
    } elseif ($len == 4) {
        $filter_type = 'year';
        $filter_val = $data['filter_date'];
    }
}

$pos_filter_type = 'all';
$pos_filter_val = '';
if (!empty($data['pos_filter_date'])) {
    $len = strlen($data['pos_filter_date']);
    if ($len == 10) {
        $pos_filter_type = 'day';
        $pos_filter_val = $data['pos_filter_date'];
    } elseif ($len == 7) {
        $pos_filter_type = 'month';
        $pos_filter_val = $data['pos_filter_date'];
    } elseif ($len == 4) {
        $pos_filter_type = 'year';
        $pos_filter_val = $data['pos_filter_date'];
    }
}
?>

<div class="p-6 lg:p-8" x-data="{ tab: '<?php echo $data['tab'] ?? 'pos'; ?>' }">
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
        <!-- Bộ lọc cho POS/Online -->
        <div class="bg-gray-50/50 p-6 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4" x-data="{ posFilterType: '<?php echo $pos_filter_type; ?>', posFilterValue: '<?php echo $pos_filter_val; ?>' }">
            <form action="" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <input type="hidden" name="tab" value="pos">
                
                <div class="flex items-center gap-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-wider">Kênh đơn hàng:</label>
                    <select name="order_type" class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="all" <?php echo $data['order_type'] == 'all' ? 'selected' : ''; ?>>Tất cả kênh</option>
                        <option value="pos" <?php echo $data['order_type'] == 'pos' ? 'selected' : ''; ?>>Tại quầy (POS)</option>
                        <option value="online" <?php echo $data['order_type'] == 'online' ? 'selected' : ''; ?>>Trực tuyến (Online)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-wider">Thời gian:</label>
                    <select x-model="posFilterType" class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="all">Xem tất cả</option>
                        <option value="day">Theo ngày</option>
                        <option value="month">Theo tháng</option>
                        <option value="year">Theo năm</option>
                    </select>
                </div>
                
                <div class="flex items-center">
                    <!-- Ngày -->
                    <template x-if="posFilterType === 'day'">
                        <input type="date" name="pos_filter_date" x-model="posFilterValue" required
                               class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-primary">
                    </template>
                    
                    <!-- Tháng -->
                    <template x-if="posFilterType === 'month'">
                        <input type="month" name="pos_filter_date" x-model="posFilterValue" required
                               class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-primary">
                    </template>
                    
                    <!-- Năm -->
                    <template x-if="posFilterType === 'year'">
                        <input type="number" name="pos_filter_date" x-model="posFilterValue" min="2020" max="2035" required placeholder="VD: 2026"
                               class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-primary w-24">
                    </template>

                    <!-- Ẩn khi xem tất cả -->
                    <template x-if="posFilterType === 'all'">
                        <input type="hidden" name="pos_filter_date" value="">
                    </template>
                </div>
                
                <button type="submit" class="px-5 py-1.5 bg-primary text-white font-bold rounded-xl text-xs hover:bg-indigo-700 transition shadow-sm">
                    <i class="fa-solid fa-filter mr-1"></i> Lọc
                </button>
                <?php if ($data['order_type'] !== 'all' || !empty($data['pos_filter_date'])): ?>
                    <a href="?tab=pos" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl text-xs transition flex items-center">
                        <i class="fa-solid fa-rotate-left mr-1"></i> Thiết lập lại
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Mã Đơn</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Khách hàng</th>
                    <?php if($_SESSION['user_role'] == 'manager' || $_SESSION['user_role'] == 'admin'): ?>
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
                <tr><td colspan="<?php echo ($_SESSION['user_role'] == 'manager' || $_SESSION['user_role'] == 'admin') ? '7' : '6'; ?>" class="px-6 py-12 text-center text-gray-400 font-bold">Không có đơn hàng nào</td></tr>
                <?php else: ?>
                    <?php foreach($data['orders'] as $order): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4"><span class="text-xs font-black text-gray-400">#<?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT); ?></span></td>
                        <td class="px-6 py-4"><span class="text-sm font-bold text-gray-700"><?php echo $order->customer_name ?? 'Khách lẻ'; ?></span></td>
                        
                        <?php if($_SESSION['user_role'] == 'manager' || $_SESSION['user_role'] == 'admin'): ?>
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

        <!-- Phân trang POS -->
        <?php if(isset($data['total_pos_pages']) && $data['total_pos_pages'] > 1): ?>
        <div class="flex items-center justify-between px-6 py-4 bg-gray-50/30 border-t border-gray-100">
            <div class="text-xs text-gray-500 font-bold">
                Hiển thị trang <span class="font-extrabold text-primary"><?php echo $data['pos_page']; ?></span> / <span class="font-extrabold text-gray-700"><?php echo $data['total_pos_pages']; ?></span> (Tổng số <span class="font-extrabold text-gray-700"><?php echo $data['total_pos']; ?></span> đơn hàng)
            </div>
            <div class="flex gap-1.5">
                <?php if($data['pos_page'] > 1): ?>
                    <a href="?tab=pos&order_type=<?php echo $data['order_type']; ?>&pos_filter_date=<?php echo urlencode($data['pos_filter_date'] ?? ''); ?>&pos_page=<?php echo $data['pos_page'] - 1; ?>" class="px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 transition flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-chevron-left text-[9px]"></i> Trước
                    </a>
                <?php else: ?>
                    <span class="px-3 py-1.5 bg-gray-50 border border-gray-150 rounded-xl text-xs font-bold text-gray-300 cursor-not-allowed flex items-center gap-1">
                        <i class="fa-solid fa-chevron-left text-[9px]"></i> Trước
                    </span>
                <?php endif; ?>

                <?php
                $start_page = max(1, $data['pos_page'] - 2);
                $end_page = min($data['total_pos_pages'], $data['pos_page'] + 2);
                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <a href="?tab=pos&order_type=<?php echo $data['order_type']; ?>&pos_filter_date=<?php echo urlencode($data['pos_filter_date'] ?? ''); ?>&pos_page=<?php echo $i; ?>" class="px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-sm <?php echo $i == $data['pos_page'] ? 'bg-primary text-white shadow-primary/20 border border-primary' : 'bg-white hover:bg-gray-50 text-gray-600 border border-gray-200'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if($data['pos_page'] < $data['total_pos_pages']): ?>
                    <a href="?tab=pos&order_type=<?php echo $data['order_type']; ?>&pos_filter_date=<?php echo urlencode($data['pos_filter_date'] ?? ''); ?>&pos_page=<?php echo $data['pos_page'] + 1; ?>" class="px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 transition flex items-center gap-1 shadow-sm">
                        Sau <i class="fa-solid fa-chevron-right text-[9px]"></i>
                    </a>
                <?php else: ?>
                    <span class="px-3 py-1.5 bg-gray-50 border border-gray-150 rounded-xl text-xs font-bold text-gray-300 cursor-not-allowed flex items-center gap-1">
                        Sau <i class="fa-solid fa-chevron-right text-[9px]"></i>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Tab Service -->
    <div x-show="tab === 'service'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Bộ lọc cho Service -->
        <div class="bg-gray-50/50 p-6 border-b border-gray-100" x-data="{ filterType: '<?php echo $filter_type; ?>', filterValue: '<?php echo $filter_val; ?>' }">
            <form action="" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <input type="hidden" name="tab" value="service">
                
                <div class="flex items-center gap-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-wider">Lọc theo:</label>
                    <select x-model="filterType" class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="all">Xem tất cả</option>
                        <option value="day">Theo ngày</option>
                        <option value="month">Theo tháng</option>
                        <option value="year">Theo năm</option>
                    </select>
                </div>
                
                <div class="flex items-center">
                    <!-- Ngày -->
                    <template x-if="filterType === 'day'">
                        <input type="date" name="filter_date" x-model="filterValue" required
                               class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-primary">
                    </template>
                    
                    <!-- Tháng -->
                    <template x-if="filterType === 'month'">
                        <input type="month" name="filter_date" x-model="filterValue" required
                               class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-primary">
                    </template>
                    
                    <!-- Năm -->
                    <template x-if="filterType === 'year'">
                        <input type="number" name="filter_date" x-model="filterValue" min="2020" max="2035" required placeholder="VD: 2026"
                               class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-primary w-24">
                    </template>

                    <!-- Ẩn khi xem tất cả -->
                    <template x-if="filterType === 'all'">
                        <input type="hidden" name="filter_date" value="">
                    </template>
                </div>
                
                <button type="submit" class="px-5 py-1.5 bg-primary text-white font-bold rounded-xl text-xs hover:bg-indigo-700 transition shadow-sm">
                    <i class="fa-solid fa-filter mr-1"></i> Lọc
                </button>
                <?php if (!empty($data['filter_date'])): ?>
                    <a href="?tab=service" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl text-xs transition flex items-center">
                        <i class="fa-solid fa-rotate-left mr-1"></i> Thiết lập lại
                    </a>
                <?php endif; ?>
            </form>
        </div>

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

        <!-- Phân trang Dịch vụ -->
        <?php if(isset($data['total_services_pages']) && $data['total_services_pages'] > 1): ?>
        <div class="flex items-center justify-between px-6 py-4 bg-gray-50/30 border-t border-gray-100">
            <div class="text-xs text-gray-500 font-bold">
                Hiển thị trang <span class="font-extrabold text-primary"><?php echo $data['service_page']; ?></span> / <span class="font-extrabold text-gray-700"><?php echo $data['total_services_pages']; ?></span> (Tổng số <span class="font-extrabold text-gray-700"><?php echo $data['total_services']; ?></span> dịch vụ)
            </div>
            <div class="flex gap-1.5">
                <?php if($data['service_page'] > 1): ?>
                    <a href="?tab=service&filter_date=<?php echo urlencode($data['filter_date']); ?>&service_page=<?php echo $data['service_page'] - 1; ?>" class="px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 transition flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-chevron-left text-[9px]"></i> Trước
                    </a>
                <?php else: ?>
                    <span class="px-3 py-1.5 bg-gray-50 border border-gray-150 rounded-xl text-xs font-bold text-gray-300 cursor-not-allowed flex items-center gap-1">
                        <i class="fa-solid fa-chevron-left text-[9px]"></i> Trước
                    </span>
                <?php endif; ?>

                <?php
                $start_page = max(1, $data['service_page'] - 2);
                $end_page = min($data['total_services_pages'], $data['service_page'] + 2);
                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <a href="?tab=service&filter_date=<?php echo urlencode($data['filter_date']); ?>&service_page=<?php echo $i; ?>" class="px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-sm <?php echo $i == $data['service_page'] ? 'bg-primary text-white shadow-primary/20 border border-primary' : 'bg-white hover:bg-gray-50 text-gray-600 border border-gray-200'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if($data['service_page'] < $data['total_services_pages']): ?>
                    <a href="?tab=service&filter_date=<?php echo urlencode($data['filter_date']); ?>&service_page=<?php echo $data['service_page'] + 1; ?>" class="px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 transition flex items-center gap-1 shadow-sm">
                        Sau <i class="fa-solid fa-chevron-right text-[9px]"></i>
                    </a>
                <?php else: ?>
                    <span class="px-3 py-1.5 bg-gray-50 border border-gray-150 rounded-xl text-xs font-bold text-gray-300 cursor-not-allowed flex items-center gap-1">
                        Sau <i class="fa-solid fa-chevron-right text-[9px]"></i>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
