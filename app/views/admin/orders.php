<?php require APPROOT . '/views/admin/header.php'; ?>

<style>[x-cloak] { display: none !important; }</style>

<div class="p-6" x-data="{
    showModal: false,
    selectedOrder: null,
    openModal(order) { this.selectedOrder = order; this.showModal = true; },
    
    // Cancellation Modal State
    showCancelModal: false,
    cancelOrderId: null,
    cancelReason: '',
    cancelIsVNPay: false,
    openCancelModal(data) {
        this.cancelOrderId = data.id;
        this.cancelIsVNPay = (data.payment_method === 'vnpay' && (data.status === 'shipping' || data.status === 'pending'));
        this.showCancelModal = true;
        this.cancelReason = '';
    },
    
    // Receipt Modal State
    showReceiptModal: false,
    receiptImageUrl: '',
    openReceiptModal(url) {
        this.receiptImageUrl = url;
        this.showReceiptModal = true;
    },

    // Approval Modal State
    showApproveModal: false,
    approveOrderId: null,
    approveTotal: 0,
    approveNote: '',
    approveAmount: 0,
    openApproveModal(order) {
        this.approveOrderId = order.id;
        this.approveTotal = order.total_amount;
        this.approveAmount = order.total_amount;
        this.approveNote = '';
        this.showApproveModal = true;
    }
}" @open-cancel-modal.window="openCancelModal($event.detail)" @open-approve-modal.window="openApproveModal($event.detail)">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Quản lý Đơn hàng</h1>
            <p class="text-sm text-gray-500">Báo cáo và theo dõi trạng thái đơn hàng</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/export_orders" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-50 transition flex items-center">
            <i class="fa-solid fa-download mr-2 text-primary"></i> Xuất CSV
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-primary flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Tổng đơn hàng</p>
                <p class="text-2xl font-black text-gray-800"><?php echo $data['total_orders']; ?></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Doanh thu</p>
                <p class="text-2xl font-black text-green-600">
                    <?php echo number_format($data['total_revenue'] ?? 0, 0, ',', '.'); ?> đ
                </p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-filter"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Đang lọc</p>
                <p class="text-sm font-bold text-gray-600">
                    <?php
                        $f = $data['filters'];
                        $parts = [];
                        if ($f['type'] != 'all') $parts[] = $f['type'] == 'online' ? 'Hàng đặt' : 'POS';
                        if ($f['status'] != 'all') $parts[] = $f['status'];
                        if (!empty($f['date'])) $parts[] = 'Ngày ' . date('d/m/Y', strtotime($f['date']));
                        elseif (!empty($f['month']) && !empty($f['year'])) $parts[] = 'T' . $f['month'] . '/' . $f['year'];
                        elseif (!empty($f['year'])) $parts[] = 'Năm ' . $f['year'];
                        echo $parts ? implode(', ', $parts) : 'Tất cả';
                    ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <form method="GET" action="<?php echo URLROOT; ?>/admin/orders" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 mb-6">
        <div class="flex flex-wrap items-end gap-4">
            <!-- Loại đơn hàng -->
            <div class="flex-1 min-w-[140px]">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Loại</label>
                <select name="type" class="w-full px-4 py-2 rounded-xl border border-gray-100 bg-gray-50 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="all"    <?php echo $data['filters']['type'] == 'all'    ? 'selected' : ''; ?>>Tất cả</option>
                    <option value="online" <?php echo $data['filters']['type'] == 'online' ? 'selected' : ''; ?>>Hàng đặt (Online)</option>
                    <option value="pos"    <?php echo $data['filters']['type'] == 'pos'    ? 'selected' : ''; ?>>POS (Tại quầy)</option>
                </select>
            </div>

            <!-- Trạng thái -->
            <div class="flex-1 min-w-[140px]">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Trạng thái</label>
                <select name="status" class="w-full px-4 py-2 rounded-xl border border-gray-100 bg-gray-50 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="all"              <?php echo $data['filters']['status'] == 'all'              ? 'selected' : ''; ?>>Tất cả</option>
                    <option value="pending"          <?php echo $data['filters']['status'] == 'pending'          ? 'selected' : ''; ?>>Đang xử lý</option>
                    <option value="shipping"         <?php echo $data['filters']['status'] == 'shipping'         ? 'selected' : ''; ?>>Đang giao</option>
                    <option value="completed"        <?php echo $data['filters']['status'] == 'completed'        ? 'selected' : ''; ?>>Hoàn thành</option>
                    <option value="cancelled"        <?php echo $data['filters']['status'] == 'cancelled'        ? 'selected' : ''; ?>>Đã hủy</option>
                    <optgroup label="Yêu cầu Hoàn tiền">
                        <option value="refund_pending"   <?php echo $data['filters']['status'] == 'refund_pending'   ? 'selected' : ''; ?>>Chờ hoàn tiền</option>
                        <option value="refund_completed" <?php echo $data['filters']['status'] == 'refund_completed' ? 'selected' : ''; ?>>Đã hoàn tiền</option>
                    </optgroup>
                </select>
            </div>

            <!-- Ngày cụ thể -->
            <div class="flex-1 min-w-[140px]">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Ngày cụ thể</label>
                <input type="date" name="date" value="<?php echo $data['filters']['date']; ?>"
                       class="w-full px-4 py-2 rounded-xl border border-gray-100 bg-gray-50 text-sm outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <!-- Tháng -->
            <div class="min-w-[110px]">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tháng</label>
                <select name="month" class="w-full px-4 py-2 rounded-xl border border-gray-100 bg-gray-50 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">Tất cả</option>
                    <?php for($m = 1; $m <= 12; $m++): ?>
                    <option value="<?php echo $m; ?>" <?php echo $data['filters']['month'] == $m ? 'selected' : ''; ?>>
                        Tháng <?php echo $m; ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Năm -->
            <div class="min-w-[110px]">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Năm</label>
                <select name="year" class="w-full px-4 py-2 rounded-xl border border-gray-100 bg-gray-50 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">Tất cả</option>
                    <?php for($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                    <option value="<?php echo $y; ?>" <?php echo $data['filters']['year'] == $y ? 'selected' : ''; ?>>
                        <?php echo $y; ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-xl text-sm font-black hover:bg-indigo-700 transition shadow-md shadow-primary/20">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Lọc
                </button>
                <a href="<?php echo URLROOT; ?>/admin/orders" class="bg-gray-100 text-gray-500 px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-200 transition">
                    Xóa lọc
                </a>
            </div>
        </div>
    </form>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Đơn hàng</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Khách hàng</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Loại & Sản phẩm</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Tổng tiền</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Trạng thái</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if(empty($data['orders'])): ?>
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-receipt text-5xl text-gray-200 mb-4"></i>
                                    <p class="text-gray-400 font-medium">Không có đơn hàng nào phù hợp</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['orders'] as $order):
                            $isPendingTransfer = ($order->payment_method === 'transfer' && $order->status === 'pending');
                            $isVNPayPaid       = ($order->payment_method === 'vnpay'    && in_array($order->status, ['shipping','completed']));
                            $isVNPayPending    = ($order->payment_method === 'vnpay'    && $order->status === 'pending');
                            $rowClass = '';
                            if ($isPendingTransfer) $rowClass = 'bg-blue-50/40 border-l-4 border-l-blue-400';
                            elseif ($isVNPayPaid)   $rowClass = 'bg-indigo-50/30 border-l-4 border-l-indigo-400';
                            elseif ($isVNPayPending) $rowClass = 'bg-amber-50/30 border-l-4 border-l-amber-400';
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors <?php echo $rowClass; ?>">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-black text-gray-900">#ORD-<?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT); ?></span>
                                    <p class="text-[10px] font-bold text-gray-400 mt-1"><i class="fa-regular fa-clock"></i> <?php echo date('d/m/y H:i', strtotime($order->created_at)); ?></p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-xl bg-indigo-50 text-primary flex items-center justify-center font-bold text-xs mr-3 shadow-sm border border-indigo-100">
                                            <?php echo strtoupper(substr($order->customer_name ?? 'K', 0, 1)); ?>
                                        </div>
                                        <div>
                                            <span class="text-sm font-bold text-gray-700 block"><?php echo $order->customer_name ?? 'Khách lẻ'; ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider <?php echo $order->order_type == 'online' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-orange-50 text-orange-600 border border-orange-100'; ?>">
                                            <?php echo $order->order_type == 'online' ? 'Online' : 'POS'; ?>
                                        </span>
                                        <?php if($order->payment_method === 'transfer'): ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider <?php echo $isPendingTransfer ? 'bg-amber-100 text-amber-700 animate-pulse' : 'bg-green-100 text-green-700'; ?>">
                                                <i class="fa-solid fa-building-columns"></i>
                                                <?php echo $isPendingTransfer ? 'Chờ CK' : 'Đã CK'; ?>
                                            </span>
                                            <?php if($order->refund_status === 'pending'): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-red-100 text-red-700 border border-red-200 animate-pulse shadow-sm">
                                                    Y/C Hoàn tiền
                                                </span>
                                            <?php elseif($order->refund_status === 'completed'): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-gray-100 text-gray-600">
                                                    Đã hoàn tiền
                                                </span>
                                            <?php endif; ?>
                                        <?php elseif($order->payment_method === 'vnpay'): ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider
                                                <?php echo $isVNPayPaid ? 'bg-blue-100 text-blue-700 border border-blue-200' : ($isVNPayPending ? 'bg-amber-100 text-amber-700 animate-pulse' : 'bg-gray-100 text-gray-500'); ?>">
                                                <i class="fa-solid fa-credit-card"></i>
                                                <?php
                                                    if ($isVNPayPaid)    echo 'VNPay ✓';
                                                    elseif ($isVNPayPending) echo 'VNPay...';
                                                    else echo 'VNPay';
                                                ?>
                                            </span>
                                            <?php if($order->refund_status === 'pending'): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-red-100 text-red-700 border border-red-200 animate-pulse">
                                                    <i class="fa-solid fa-rotate-left"></i> Hoàn tiền VNPay
                                                </span>
                                            <?php elseif($order->refund_status === 'completed'): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-gray-100 text-gray-500">
                                                    Đã hoàn tiền
                                                </span>
                                            <?php endif; ?>
                                        <?php elseif($order->payment_method === 'cod'): ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black bg-gray-100 text-gray-500">
                                                <i class="fa-solid fa-money-bill-wave"></i> COD
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex -space-x-2">
                                            <?php foreach($order->items as $idx => $item): ?>
                                                <?php if($idx < 3): ?>
                                                    <img class="h-6 w-6 rounded-full ring-2 ring-white object-cover border border-gray-100"
                                                         src="<?php echo !empty($item->image) ? URLROOT . '/public/images/' . $item->image : 'https://placehold.co/100x100?text=' . urlencode($item->name); ?>"
                                                         alt="" title="<?php echo $item->name; ?>">
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-400"><?php echo count($order->items); ?> SP</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-black text-primary"><?php echo number_format($order->total_amount, 0, ',', '.'); ?> đ</span>
                                </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="<?php echo URLROOT; ?>/admin/order_status/<?php echo $order->id; ?>" method="POST" id="statusForm<?php echo $order->id; ?>">
                                        <select name="status" 
                                                <?php echo in_array($order->status, ['completed', 'cancelled']) ? 'disabled' : ''; ?>
                                                onchange="if(this.value === 'cancelled') {
                                                    var orderData = <?php echo htmlspecialchars(json_encode(['id'=>$order->id,'payment_method'=>$order->payment_method,'status'=>$order->status,'total_amount'=>$order->total_amount])); ?>;
                                                    window.dispatchEvent(new CustomEvent('open-cancel-modal', {detail: orderData}));
                                                    this.value = '<?php echo $order->status; ?>';
                                                } else {
                                                    this.form.submit();
                                                }"
                                                class="text-xs font-bold rounded-xl px-3 py-1.5 border border-transparent shadow-sm focus:ring-0 cursor-pointer transition-all hover:border-gray-200
                                                <?php
                                                    if ($order->status == 'completed') echo 'bg-green-100 text-green-700 opacity-70 cursor-not-allowed';
                                                    elseif ($order->status == 'cancelled') echo 'bg-red-100 text-red-700 opacity-70 cursor-not-allowed';
                                                    elseif ($order->status == 'shipping' && $isVNPayPaid) echo 'bg-blue-100 text-blue-700';
                                                    elseif ($order->status == 'shipping') echo 'bg-indigo-100 text-indigo-700';
                                                    else echo 'bg-amber-100 text-amber-700';
                                                ?>">
                                            <option value="pending"   <?php echo $order->status == 'pending'   ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                            <option value="shipping"  <?php echo $order->status == 'shipping'  ? 'selected' : ''; ?>><?php echo $isVNPayPaid ? '✓ Đã TT – Đang giao' : 'Đang giao'; ?></option>
                                            <option value="completed" <?php echo $order->status == 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                                            <option value="cancelled" <?php echo $order->status == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <?php if(!empty($order->receipt_image)): ?>
                                    <button type="button" @click="openReceiptModal('<?php echo URLROOT; ?>/public/uploads/receipts/<?php echo $order->receipt_image; ?>')"
                                            class="text-green-500 hover:text-green-700 transition" title="Xem biên lai">
                                        <i class="fa-solid fa-file-invoice text-lg"></i>
                                    </button>
                                    <?php endif; ?>
                                    
                                    <?php if($isPendingTransfer): ?>
                                    <button type="button" @click="openApproveModal(<?php echo htmlspecialchars(json_encode($order)); ?>)"
                                            class="flex items-center gap-1.5 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-black rounded-xl shadow-sm shadow-green-200 transition-all hover:shadow-green-300">
                                        <i class="fa-solid fa-circle-check"></i> Duyệt CK
                                    </button>
                                    <?php endif; ?>
                                    <button type="button"
                                            @click="openModal(<?php echo htmlspecialchars(json_encode($order)); ?>)"
                                            class="text-gray-400 hover:text-primary transition" title="Xem chi tiết">
                                        <i class="fa-solid fa-eye text-lg"></i>
                                    </button>
                                </div>
                            </td>
                                </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl" @click.away="showModal = false">
            <!-- Modal Header -->
            <div class="px-8 py-6 flex justify-between items-center" 
                 :class="selectedOrder?.order_type === 'pos' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white' : 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white'">
                <div>
                    <h2 class="text-xl font-black">Chi Tiết Đơn <span x-text="'#ORD-' + selectedOrder?.id?.toString().padStart(5,'0')"></span></h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs uppercase tracking-widest font-bold opacity-80" x-text="selectedOrder?.order_type === 'pos' ? '🏪 Giao dịch tại quầy (POS)' : '🌐 Đơn hàng trực tuyến'"></span>
                    </div>
                </div>
                <button @click="showModal = false" class="text-white/70 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <div class="p-8 max-h-[70vh] overflow-y-auto">

                <!-- Thông tin khách hàng (chỉ hiện cho đơn hàng Online) -->
                <template x-if="selectedOrder?.order_type === 'online'">
                    <div class="mb-8 p-5 rounded-2xl bg-blue-50/50 border border-blue-100">
                        <h4 class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-4 flex items-center gap-1">
                            <i class="fa-solid fa-user-circle"></i> Thông tin người đặt hàng
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Họ tên</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="selectedOrder?.customer_name || 'N/A'"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Email</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="selectedOrder?.customer_email || 'N/A'"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Số điện thoại</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="selectedOrder?.customer_phone || 'Chưa cập nhật'"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Địa chỉ giao hàng</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="selectedOrder?.shipping_address || selectedOrder?.customer_address || 'Chưa cập nhật'"></p>
                                </div>
                            </div>
                        </div>
                        <!-- Thông tin người nhận (từ shipping fields) -->
                        <template x-if="selectedOrder?.shipping_name">
                            <div class="mt-3 pt-3 border-t border-blue-100 grid grid-cols-2 gap-3">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-truck text-blue-400 w-4"></i>
                                    <div>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase">Người nhận</p>
                                        <p class="text-xs font-bold text-gray-700" x-text="selectedOrder?.shipping_name"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-phone text-blue-400 w-4"></i>
                                    <div>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase">SĐT giao hàng</p>
                                        <p class="text-xs font-bold text-gray-700" x-text="selectedOrder?.shipping_phone || 'N/A'"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                </template>

                <!-- Thông tin giao dịch -->
                <div class="mb-6 pb-6 border-b border-gray-100">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-1">
                        <i class="fa-solid fa-receipt"></i> Thông tin giao dịch
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Mã đơn</p>
                            <p class="text-sm font-black text-gray-800" x-text="'#' + selectedOrder?.id?.toString().padStart(5,'0')"></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Thời gian</p>
                            <p class="text-xs font-bold text-gray-800" x-text="new Date(selectedOrder?.created_at).toLocaleString('vi-VN')"></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Thanh toán</p>
                            <p class="text-sm font-black text-gray-800"
                               x-text="selectedOrder?.payment_method === 'transfer' ? '🏦 Chuyển khoản' : (selectedOrder?.payment_method === 'vnpay' ? '💳 VNPay' : (selectedOrder?.payment_method === 'cod' ? '💵 COD' : (selectedOrder?.payment_method === 'cash' ? '💵 Tiền mặt' : selectedOrder?.payment_method)))"></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Đã thanh toán</p>
                            <span class="text-xs font-black px-2 py-0.5 rounded-full"
                                  :class="(selectedOrder?.payment_method === 'vnpay' && ['shipping','completed'].includes(selectedOrder?.status)) || (selectedOrder?.payment_method === 'transfer' && selectedOrder?.status !== 'pending') || selectedOrder?.payment_method === 'cash' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'"
                                  x-text="(selectedOrder?.payment_method === 'vnpay' && ['shipping','completed'].includes(selectedOrder?.status)) || (selectedOrder?.payment_method === 'transfer' && selectedOrder?.status !== 'pending') || selectedOrder?.payment_method === 'cash' ? '✓ Đã thanh toán' : '⏳ Chưa thanh toán'"></span>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Trạng thái</p>
                            <span class="text-xs font-black uppercase px-2 py-0.5 rounded-full"
                                  :class="selectedOrder?.status === 'completed' ? 'bg-green-100 text-green-600' : (selectedOrder?.status === 'cancelled' ? 'bg-red-100 text-red-600' : (selectedOrder?.status === 'shipping' ? 'bg-indigo-100 text-indigo-600' : 'bg-blue-100 text-blue-600'))"
                                  x-text="selectedOrder?.status === 'completed' ? 'Hoàn thành' : (selectedOrder?.status === 'cancelled' ? 'Đã hủy' : (selectedOrder?.status === 'shipping' ? 'Đang giao' : 'Đang xử lý'))"></span>
                        </div>
                    </div>
                    <!-- Lý do hủy (nếu có) -->
                    <template x-if="selectedOrder?.status === 'cancelled' && selectedOrder?.cancel_reason">
                        <div class="mt-4 p-4 rounded-xl bg-red-50 border border-red-100">
                            <p class="text-[10px] text-red-400 font-bold uppercase mb-1">Lý do hủy đơn</p>
                            <p class="text-sm font-medium text-red-700" x-text="selectedOrder?.cancel_reason"></p>
                        </div>
                    </template>
                </div>

                <!-- POS: hiển thị khách lẻ nhỏ gọn -->
                <template x-if="selectedOrder?.order_type === 'pos'">
                    <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
                        <i class="fa-solid fa-store text-orange-400"></i>
                        <span>Khách mua tại quầy · <strong x-text="selectedOrder?.customer_name || 'Khách lẻ'"></strong></span>
                    </div>
                </template>

                <!-- Refund Request details (if any) -->
                <template x-if="selectedOrder?.refund_status === 'pending'">
                    <div class="mb-6 p-5 rounded-2xl bg-red-50 border border-red-200">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-[10px] font-black text-red-600 uppercase tracking-widest flex items-center gap-1">
                                <i class="fa-solid fa-money-bill-transfer text-lg"></i> Khách hàng yêu cầu hoàn tiền
                            </h4>
                            <form :action="'<?php echo URLROOT; ?>/admin/complete_refund/' + selectedOrder?.id" method="POST" onsubmit="return confirm('Bạn đã chuyển khoản hoàn tiền xong cho khách hàng này?');">
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs font-black rounded-xl hover:bg-red-700 shadow-sm">Xác nhận đã hoàn tiền</button>
                            </form>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-white p-3 rounded-xl border border-red-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Ngân hàng</p>
                                <p class="text-sm font-black text-gray-800" x-text="selectedOrder?.refund_bank"></p>
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-red-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Số tài khoản</p>
                                <p class="text-sm font-black text-gray-800" x-text="selectedOrder?.refund_account"></p>
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-red-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Chủ tài khoản</p>
                                <p class="text-sm font-black text-gray-800" x-text="selectedOrder?.refund_name"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Danh sách sản phẩm -->
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-1">
                    <i class="fa-solid fa-cart-shopping"></i> Sản phẩm đã mua
                </h4>

                <!-- Bảng sản phẩm -->
                <div class="rounded-2xl border border-gray-100 overflow-hidden mb-6">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-[10px] font-black text-gray-400 uppercase">Sản phẩm</th>
                                <th class="px-4 py-3 text-center text-[10px] font-black text-gray-400 uppercase">SL</th>
                                <th class="px-4 py-3 text-right text-[10px] font-black text-gray-400 uppercase">Đơn giá</th>
                                <th class="px-4 py-3 text-right text-[10px] font-black text-gray-400 uppercase">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <template x-for="item in selectedOrder?.items" :key="item.id">
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-lg bg-white border border-gray-100 overflow-hidden flex-shrink-0">
                                                <img :src="item.image ? '<?php echo URLROOT; ?>/public/images/' + item.image : 'https://placehold.co/100x100?text=' + item.name" class="w-full h-full object-cover" loading="lazy">
                                            </div>
                                            <span class="font-bold text-gray-800 truncate max-w-[200px]" x-text="item.name"></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="bg-indigo-50 text-primary font-black text-xs px-2 py-0.5 rounded-full" x-text="item.quantity"></span>
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-600" x-text="new Intl.NumberFormat('vi-VN').format(item.unit_price) + ' đ'"></td>
                                    <td class="px-4 py-3 text-right font-black text-gray-800" x-text="new Intl.NumberFormat('vi-VN').format(item.quantity * item.unit_price) + ' đ'"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Tổng kết -->
                <div class="flex justify-between items-end pt-6 border-t border-gray-100">
                    <div class="text-sm text-gray-500 italic flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                        <span x-text="selectedOrder?.order_type === 'pos' ? 'Đã thanh toán tại quầy' : 'Đơn hàng đã được xác nhận'"></span>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Tổng thanh toán</p>
                        <p class="text-3xl font-black text-primary" x-text="new Intl.NumberFormat('vi-VN').format(selectedOrder?.total_amount) + ' đ'"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-8 py-5 bg-gray-50 flex justify-end">
                <button @click="showModal = false" class="px-6 py-2 rounded-xl text-sm font-bold text-gray-500 hover:text-gray-800 transition">Đóng</button>
            </div>
        </div>
    </div>
    <!-- Cancellation Reason Modal -->
    <div x-show="showCancelModal" x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl" @click.away="showCancelModal = false">
            <div class="p-8">
                <!-- Icon -->
                <div class="h-16 w-16 rounded-2xl flex items-center justify-center text-3xl mb-6 mx-auto"
                     :class="cancelIsVNPay ? 'bg-blue-50 text-blue-500' : 'bg-red-50 text-red-500'">
                    <i :class="cancelIsVNPay ? 'fa-solid fa-rotate-left' : 'fa-solid fa-ban'"></i>
                </div>
                <h3 class="text-xl font-black text-gray-800 text-center mb-2" x-text="cancelIsVNPay ? 'Hủy & Hoàn tiền VNPay' : 'Hủy đơn hàng'"></h3>

                <!-- Cảnh báo VNPay đã thanh toán -->
                <div x-show="cancelIsVNPay" class="mb-5 bg-blue-50 border border-blue-200 rounded-2xl px-5 py-4 flex gap-3 items-start">
                    <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 flex-shrink-0"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-black mb-1">Đơn hàng này đã được thanh toán qua VNPay!</p>
                        <p class="text-xs text-blue-600">Sau khi hủy, khách hàng sẽ gửi yêu cầu hoàn tiền thông qua hệ thống. Admin cần liên hệ VNPay để thực hiện hoàn tiền.</p>
                    </div>
                </div>

                <p class="text-sm text-gray-500 text-center mb-6 px-4" x-show="!cancelIsVNPay">Vui lòng cung cấp lý do hủy đơn hàng này để thông báo cho khách hàng.</p>
                
                <form :action="'<?php echo URLROOT; ?>/admin/order_status/' + cancelOrderId" method="POST">
                    <input type="hidden" name="status" value="cancelled">

                    <div class="mb-4">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Lý do hủy đơn *</label>
                        <textarea name="cancel_reason" x-model="cancelReason" required
                                  class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50 text-sm font-medium outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500/30 transition-all resize-none h-24"
                                  placeholder="Ví dụ: Sản phẩm tạm hết hàng, khách hàng yêu cầu hủy..."></textarea>
                    </div>

                    <!-- Thông tin hoàn tiền VNPay (chỉ hiện khi là đơn VNPay đã thanh toán) -->
                    <div x-show="cancelIsVNPay" class="space-y-3 mb-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Thông tin tài khoản hoàn tiền (khách cung cấp)</p>
                        <div class="grid grid-cols-1 gap-3">
                            <input type="text" name="refund_bank" placeholder="Ngân hàng (VD: VCB, TCB, MBBank...)"
                                   :required="cancelIsVNPay"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                            <input type="text" name="refund_account" placeholder="Số tài khoản"
                                   :required="cancelIsVNPay"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                            <input type="text" name="refund_name" placeholder="Tên chủ tài khoản"
                                   :required="cancelIsVNPay"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                        </div>
                        <p class="text-[10px] text-gray-400 italic">* Nếu chưa có thông tin, có thể nhập sau khi liên hệ khách hàng.</p>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" @click="showCancelModal = false"
                                class="flex-1 px-6 py-4 rounded-2xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition-all">
                            Quay lại
                        </button>
                        <button type="submit" :disabled="!cancelReason.trim()"
                                :class="cancelIsVNPay ? 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/20' : 'bg-red-500 hover:bg-red-600 shadow-red-500/20'"
                                class="flex-1 px-6 py-4 rounded-2xl text-sm font-black text-white shadow-lg transition-all disabled:opacity-50 disabled:shadow-none">
                            <span x-text="cancelIsVNPay ? 'Hủy & Ghi nhận hoàn tiền' : 'Xác nhận hủy'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Approve Modal with Partial Payment support -->
    <div x-show="showApproveModal" x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl" @click.away="showApproveModal = false">
            <div class="p-8">
                <div class="h-16 w-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center text-3xl mb-6 mx-auto">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <h3 class="text-xl font-black text-gray-800 text-center mb-2">Duyệt Đơn Chuyển Khoản</h3>
                <p class="text-sm text-gray-500 text-center mb-6 px-4">Xác nhận số tiền thực nhận. Nếu thiếu, hãy ghi chú lại.</p>
                
                <form :action="'<?php echo URLROOT; ?>/admin/order_status/' + approveOrderId" method="POST">
                    <input type="hidden" name="status" value="shipping">
                    
                    <div class="bg-gray-50 p-4 rounded-2xl mb-4 border border-gray-100 text-center">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Cần thanh toán</p>
                        <p class="text-xl font-black text-primary" x-text="new Intl.NumberFormat('vi-VN').format(approveTotal) + ' đ'"></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Số tiền thực nhận (đ)</label>
                        <input type="number" name="paid_amount" x-model="approveAmount" required
                               class="w-full px-5 py-3 rounded-xl border border-gray-100 bg-gray-50 text-lg font-black text-green-600 outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all text-center">
                    </div>

                    <div class="mb-6" x-show="approveAmount < approveTotal">
                        <label class="block text-[10px] font-black text-orange-400 uppercase tracking-widest mb-2 px-1">
                            <i class="fa-solid fa-triangle-exclamation"></i> Ghi chú (Thiếu tiền)
                        </label>
                        <textarea name="admin_note" x-model="approveNote"
                                  class="w-full px-5 py-3 rounded-xl border border-orange-100 bg-orange-50/50 text-sm font-medium outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all resize-none h-24"
                                  placeholder="Ví dụ: Khách chuyển thiếu 50k, thu thêm tiền mặt khi giao hàng"></textarea>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" @click="showApproveModal = false"
                                class="flex-1 px-6 py-4 rounded-2xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition-all">
                            Hủy
                        </button>
                        <button type="submit"
                                class="flex-1 px-6 py-4 rounded-2xl text-sm font-black text-white bg-green-500 hover:bg-green-600 shadow-lg shadow-green-500/20 transition-all">
                            Duyệt Đơn
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Receipt Image Modal -->
    <div x-show="showReceiptModal" x-cloak
         class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="relative w-full max-w-xl mx-auto" @click.away="showReceiptModal = false">
            <button @click="showReceiptModal = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition text-3xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <img :src="receiptImageUrl" class="w-full rounded-2xl shadow-2xl object-contain max-h-[80vh]">
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
