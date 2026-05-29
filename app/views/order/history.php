<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-12" x-data="{ 
    showDetails: false, 
    detailData: null,
    loading: false,
    fetchDetails(id, type) {
        this.showDetails = true;
        this.loading = true;
        this.detailData = null;
        fetch('<?php echo URLROOT; ?>/order/details/' + id + '/' + type)
            .then(res => res.json())
            .then(res => {
                if(res.success) this.detailData = res;
                this.loading = false;
            });
    },

    showRefundModal: false,
    refundOrderId: null,
    openRefundModal(id) {
        this.refundOrderId = id;
        this.showRefundModal = true;
    }
}">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Lịch Sử Hoạt Động</h1>
                <p class="text-gray-500 mt-2">Xem lại các đơn hàng và dịch vụ bạn đã sử dụng tại PetShop</p>
            </div>
            <div class="flex gap-3">
                <a href="<?php echo URLROOT; ?>/product" class="inline-flex items-center px-6 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                    <i class="fa-solid fa-cart-shopping mr-2 text-primary"></i> Mua sắm
                </a>
                <a href="<?php echo URLROOT; ?>/service" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-2xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-primary/25">
                    <i class="fa-solid fa-calendar-check mr-2"></i> Đặt lịch mới
                </a>
            </div>
        </div>

        <?php if(empty($data['history'])): ?>
            <div class="bg-white rounded-3xl p-20 text-center shadow-sm border border-gray-100 reveal">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-history text-4xl text-gray-300"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Bạn chưa có hoạt động nào</h2>
                <p class="text-gray-500 max-w-xs mx-auto mb-8">Các đơn hàng và lịch hẹn dịch vụ của bạn sẽ xuất hiện tại đây.</p>
            </div>
        <?php else: ?>
            <div class="space-y-8">
                <?php foreach($data['history'] as $item): ?>
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-500 reveal">
                        <!-- Header -->
                        <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-6">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center <?php echo $item->type == 'order' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600'; ?>">
                                    <i class="fa-solid <?php echo $item->type == 'order' ? 'fa-box' : 'fa-calendar-check'; ?> text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">
                                        <?php echo $item->type == 'order' ? 'Mã đơn hàng' : 'Mã lịch hẹn'; ?>
                                    </p>
                                    <p class="font-black text-gray-900">#<?php echo $item->type == 'order' ? 'ORD' : 'APP'; ?>-<?php echo str_pad($item->id, 5, '0', STR_PAD_LEFT); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Ngày thực hiện</p>
                                    <p class="font-bold text-gray-700"><?php echo date('d/m/Y', strtotime($item->date)); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Trạng thái</p>
                                    <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-black uppercase rounded-full 
                                        <?php 
                                            echo $item->status == 'completed' ? 'bg-green-100 text-green-700' : 
                                                ($item->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'); 
                                        ?>">
                                        <?php 
                                            echo $item->status == 'completed' ? 'Hoàn thành' : 
                                                ($item->status == 'cancelled' ? 'Đã hủy' : ($item->status == 'confirmed' ? 'Đã xác nhận' : 'Chờ xử lý')); 
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tổng tiền</p>
                                <p class="text-xl font-black text-primary"><?php echo number_format($item->amount, 0, ',', '.'); ?> đ</p>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-8">
                            <?php if($item->type == 'order'): ?>
                                <ul class="divide-y divide-gray-100">
                                    <?php foreach($item->items as $product): ?>
                                        <li class="py-4 flex items-center">
                                            <div class="w-16 h-16 rounded-xl border border-gray-100 overflow-hidden flex-shrink-0">
                                                <img src="<?php echo !empty($product->image) ? URLROOT . '/public/images/' . $product->image : 'https://placehold.co/100x100?text=' . urlencode($product->name); ?>" class="w-full h-full object-cover">
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <h4 class="font-bold text-gray-800"><?php echo $product->name; ?></h4>
                                                <p class="text-xs text-gray-500">Số lượng: <?php echo $product->quantity; ?> x <?php echo number_format($product->unit_price, 0, ',', '.'); ?>đ</p>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="flex items-start gap-4">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-black text-gray-800 mb-2"><?php echo $item->details->service_name; ?></h4>
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div class="flex items-center text-gray-600">
                                                <i class="fa-solid fa-dog mr-2 text-gray-400"></i> Thú cưng: <b class="ml-1 text-gray-800"><?php echo $item->details->pet_name; ?></b>
                                            </div>
                                            <div class="flex items-center text-gray-600">
                                                <i class="fa-solid fa-user-doctor mr-2 text-gray-400"></i> Nhân viên: <b class="ml-1 text-gray-800"><?php echo $item->details->doctor_name ?? 'Đang chờ...'; ?></b>
                                            </div>
                                            <div class="flex items-center text-gray-600">
                                                <i class="fa-solid fa-clock mr-2 text-gray-400"></i> Giờ hẹn: <b class="ml-1 text-gray-800"><?php echo $item->details->appointment_time; ?></b>
                                            </div>
                                            <div class="flex items-center text-gray-600">
                                                <i class="fa-solid fa-tag mr-2 text-gray-400"></i> Danh mục: <b class="ml-1 text-gray-800"><?php echo $item->details->category_name; ?></b>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($item->status == 'pending'): ?>
                                        <button class="px-4 py-2 bg-red-50 text-red-600 text-xs font-bold rounded-xl hover:bg-red-600 hover:text-white transition">Hủy lịch</button>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Footer -->
                        <div class="px-8 py-4 bg-gray-50/30 flex justify-between items-center border-t border-gray-50">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                <i class="fa-solid fa-circle-info mr-1"></i> 
                                <?php echo $item->type == 'order' ? ($item->order_type == 'pos' ? 'Mua tại cửa hàng' : 'Mua trực tuyến') : 'Dịch vụ tại trung tâm'; ?>
                            </span>
                            <div class="flex gap-2 items-center">
                                <?php if($item->type == 'order' && $item->status == 'cancelled' && $item->payment_method == 'transfer'): ?>
                                    <?php if($item->refund_status == 'none'): ?>
                                        <button @click="openRefundModal(<?php echo $item->id; ?>)" class="px-4 py-2 bg-red-50 text-red-600 text-xs font-bold rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm border border-red-100">Yêu cầu hoàn tiền</button>
                                    <?php elseif($item->refund_status == 'pending'): ?>
                                        <span class="px-4 py-2 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-xl uppercase tracking-widest border border-amber-100"><i class="fa-solid fa-clock-rotate-left"></i> Chờ hoàn tiền</span>
                                    <?php elseif($item->refund_status == 'completed'): ?>
                                        <span class="px-4 py-2 bg-green-50 text-green-600 text-[10px] font-bold rounded-xl uppercase tracking-widest border border-green-100"><i class="fa-solid fa-check-double"></i> Đã hoàn tiền</span>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <button @click="fetchDetails(<?php echo $item->id; ?>, '<?php echo $item->type; ?>')" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-primary transition">Chi tiết</button>
                                <a href="<?php echo URLROOT; ?>/order/invoice/<?php echo $item->id; ?>/<?php echo $item->type; ?>" target="_blank" class="px-6 py-2 bg-dark text-white text-xs font-bold rounded-xl hover:bg-gray-900 transition flex items-center shadow-sm">
                                    <i class="fa-solid fa-file-invoice mr-2"></i> Hóa đơn
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Details Modal -->
    <div x-show="showDetails" x-cloak class="fixed inset-0 z-[200] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDetails" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDetails = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showDetails" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-8 pt-8 pb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-black text-gray-900" id="modal-title">Chi tiết hoạt động</h3>
                        <button @click="showDetails = false" class="text-gray-400 hover:text-gray-500 transition">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>

                    <div x-show="loading" class="py-12 text-center">
                        <i class="fa-solid fa-circle-notch fa-spin text-4xl text-primary"></i>
                        <p class="text-gray-500 mt-4 font-bold">Đang tải dữ liệu...</p>
                    </div>

                    <div x-show="!loading && detailData" class="space-y-6">
                        <template x-if="detailData && detailData.type === 'order'">
                            <div class="space-y-6">
                                <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Thông tin giao hàng</p>
                                    <p class="text-sm font-bold text-blue-900" x-text="detailData.order.customer_name"></p>
                                    <p class="text-xs text-blue-700" x-text="detailData.order.customer_phone"></p>
                                    <p class="text-xs text-blue-700 mt-1" x-text="detailData.order.customer_address"></p>
                                </div>
                                <template x-if="detailData.order.status === 'cancelled' && detailData.order.cancel_reason">
                                    <div class="bg-red-50 p-4 rounded-2xl border border-red-100">
                                        <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1">Lý do hủy đơn</p>
                                        <p class="text-sm font-medium text-red-900" x-text="detailData.order.cancel_reason"></p>
                                    </div>
                                </template>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Sản phẩm đã mua</p>
                                    <ul class="divide-y divide-gray-100">
                                        <template x-for="item in detailData.items" :key="item.id">
                                            <li class="py-3 flex justify-between items-center">
                                                <div>
                                                    <p class="text-sm font-bold text-gray-800" x-text="item.name"></p>
                                                    <p class="text-[10px] text-gray-400">Số lượng: <span x-text="item.quantity"></span></p>
                                                </div>
                                                <p class="text-sm font-black text-gray-900" x-text="new Intl.NumberFormat('vi-VN').format(item.unit_price * item.quantity) + 'đ'"></p>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </template>

                        <template x-if="detailData && detailData.type === 'appointment'">
                            <div class="space-y-6">
                                <div class="bg-pink-50 p-4 rounded-2xl border border-pink-100">
                                    <p class="text-[10px] font-black text-pink-400 uppercase tracking-widest mb-1">Ghi chú dịch vụ</p>
                                    <p class="text-sm text-pink-900 font-medium leading-relaxed" x-text="detailData.data.notes || 'Không có ghi chú'"></p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Cân nặng pet</p>
                                        <p class="text-sm font-black text-gray-800">5.5 kg</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Thời lượng</p>
                                        <p class="text-sm font-black text-gray-800" x-text="detailData.data.duration_value + ' ' + (detailData.data.duration_unit === 'day' ? 'ngày' : 'giờ')"></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="pt-6 border-t border-gray-100 flex justify-between items-center">
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Tổng cộng</p>
                            <p class="text-2xl font-black text-primary" x-text="new Intl.NumberFormat('vi-VN').format(detailData.type === 'order' ? detailData.order.total_amount : detailData.data.final_price) + 'đ'"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-8 py-4 flex flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-dark text-base font-bold text-white hover:bg-gray-900 transition sm:ml-3 sm:w-auto sm:text-sm" @click="showDetails = false">
                        Đóng
                    </button>
                    <template x-if="detailData">
                        <a :href="'<?php echo URLROOT; ?>/order/invoice/' + (detailData.type === 'order' ? detailData.order.id : detailData.data.id) + '/' + detailData.type" target="_blank" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 transition sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            <i class="fa-solid fa-print mr-2"></i> In hóa đơn
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Refund Modal -->
    <div x-show="showRefundModal" x-cloak class="fixed inset-0 z-[300] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showRefundModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showRefundModal = false"></div>

            <div x-show="showRefundModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md w-full">
                <div class="bg-white px-8 pt-8 pb-6">
                    <div class="h-16 w-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-3xl mb-6 mx-auto">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 text-center mb-2">Yêu Cầu Hoàn Tiền</h3>
                    <p class="text-sm text-gray-500 text-center mb-6 px-2">Vui lòng cung cấp chính xác thông tin tài khoản ngân hàng để PetShop hoàn lại tiền cho bạn.</p>

                    <form :action="'<?php echo URLROOT; ?>/order/request_refund/' + refundOrderId" method="POST">
                        <div class="mb-4">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Ngân hàng thụ hưởng</label>
                            <input type="text" name="refund_bank" required placeholder="VD: Vietcombank, Techcombank..." class="w-full px-5 py-3 rounded-xl border border-gray-100 bg-gray-50 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div class="mb-4">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Số tài khoản</label>
                            <input type="text" name="refund_account" required placeholder="Nhập số tài khoản" class="w-full px-5 py-3 rounded-xl border border-gray-100 bg-gray-50 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div class="mb-6">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tên chủ tài khoản</label>
                            <input type="text" name="refund_name" required placeholder="VIET HOA CHU KHONG DAU" class="w-full px-5 py-3 rounded-xl border border-gray-100 bg-gray-50 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/20 uppercase">
                        </div>

                        <div class="flex gap-3">
                            <button type="button" @click="showRefundModal = false" class="flex-1 px-6 py-4 rounded-2xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition-all">Hủy</button>
                            <button type="submit" class="flex-1 px-6 py-4 rounded-2xl text-sm font-black text-white bg-primary hover:bg-indigo-600 shadow-lg shadow-primary/20 transition-all">Gửi Yêu Cầu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
