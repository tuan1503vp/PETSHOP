<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50" x-data="voucherManager()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Quản lý Voucher</h1>
                <p class="text-sm text-gray-500 mt-1">Quản lý các mẫu mã giảm giá và giá quy đổi xu</p>
            </div>
            <button @click="openAddModal()" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm shadow-emerald-200">
                <i class="fa-solid fa-plus"></i>
                <span>Thêm Voucher</span>
            </button>
        </div>

        <?php flash('admin_msg'); ?>

        <!-- Danh sách voucher -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID / Mã code</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tên Voucher</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mức Giảm</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Điều Kiện</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lượt dùng</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php if(empty($data['vouchers'])): ?>
                            <tr><td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">Chưa có voucher nào.</td></tr>
                        <?php else: ?>
                            <?php foreach($data['vouchers'] as $v): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-700">#<?php echo $v->id; ?></div>
                                    <?php if($v->code): ?>
                                        <div class="text-xs font-black text-emerald-600 mt-1"><?php echo htmlspecialchars($v->code); ?></div>
                                    <?php else: ?>
                                        <div class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest border border-gray-200 rounded px-1 inline-block">Nội bộ</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($v->title); ?></div>
                                    <div class="text-xs text-gray-500 mt-1"><?php echo htmlspecialchars($v->description); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($v->discount_type == 'percent'): ?>
                                        <div class="text-sm font-bold text-emerald-600"><?php echo $v->discount_amount; ?>%</div>
                                        <?php if($v->max_discount > 0): ?>
                                            <div class="text-xs text-gray-500 mt-1">Tối đa <?php echo number_format($v->max_discount, 0, ',', '.'); ?>đ</div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="text-sm font-bold text-emerald-600"><?php echo number_format($v->discount_amount, 0, ',', '.'); ?> đ</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-600">Đơn tối thiểu: <b><?php echo number_format($v->min_order_value, 0, ',', '.'); ?>đ</b></div>
                                    <?php if($v->category_id): ?>
                                        <div class="text-[10px] text-pink-600 mt-1 font-bold">Chỉ áp dụng DM #<?php echo $v->category_id; ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($v->code): ?>
                                        <div class="text-sm font-bold text-gray-800"><?php echo $v->used_count; ?> / <?php echo $v->usage_limit ?: '∞'; ?></div>
                                    <?php else: ?>
                                        <div class="text-sm font-bold text-amber-500 flex items-center gap-1">
                                            <i class="fa-solid fa-coins"></i> <?php echo number_format($v->cost_coins, 0, ',', '.'); ?> xu
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($v->is_active): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Đã ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="openEditModal(<?php echo htmlspecialchars(json_encode($v)); ?>)" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors mr-2">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button @click="deleteVoucher(<?php echo $v->id; ?>)" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Thêm/Sửa -->
    <div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="closeModal()"></div>

            <div class="relative inline-block w-full max-w-2xl p-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-gray-900" x-text="isEditing ? 'Sửa Voucher' : 'Thêm Voucher Mới'"></h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form :action="formAction" method="POST" class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Tên Voucher</label>
                            <input type="text" name="title" x-model="form.title" required 
                                   placeholder="Ví dụ: Giảm 50.000đ"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Mã Code (Để trống nếu dùng xu)</label>
                            <input type="text" name="code" x-model="form.code" 
                                   placeholder="SUMMER2026..."
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Mô tả chi tiết</label>
                        <textarea name="description" x-model="form.description" rows="2"
                                  placeholder="Điều kiện áp dụng..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Loại giảm</label>
                            <select name="discount_type" x-model="form.discount_type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                                <option value="fixed">Số tiền (VNĐ)</option>
                                <option value="percent">Phần trăm (%)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Mức giảm</label>
                            <input type="number" name="discount_amount" x-model="form.discount_amount" required min="0" step="1"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        </div>
                        <div x-show="form.discount_type === 'percent'">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Giảm tối đa (VNĐ)</label>
                            <input type="number" name="max_discount" x-model="form.max_discount" min="0" step="1000"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Đơn tối thiểu (VNĐ)</label>
                            <input type="number" name="min_order_value" x-model="form.min_order_value" min="0" step="1000"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Áp dụng danh mục (Tùy chọn)</label>
                            <select name="category_id" x-model="form.category_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                                <option value="">Tất cả sản phẩm</option>
                                <?php foreach($data['categories'] as $cat): ?>
                                    <option value="<?php echo $cat->id; ?>"><?php echo htmlspecialchars($cat->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div x-show="!form.code">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Giá quy đổi (Xu)</label>
                            <input type="number" name="cost_coins" x-model="form.cost_coins" min="0"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                        </div>
                        <div x-show="form.code">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Lượt dùng tối đa (Để trống: ∞)</label>
                            <input type="number" name="usage_limit" x-model="form.usage_limit" min="1"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        </div>
                        <div x-show="form.code">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Lượt dùng mỗi người</label>
                            <input type="number" name="usage_per_user" x-model="form.usage_per_user" min="1"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4" x-show="form.code">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Ngày bắt đầu</label>
                            <input type="datetime-local" name="start_date" x-model="form.start_date"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Ngày kết thúc</label>
                            <input type="datetime-local" name="end_date" x-model="form.end_date"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <input type="checkbox" name="is_combinable" id="is_combinable" x-model="form.is_combinable" class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <label for="is_combinable" class="text-sm font-medium text-gray-700">Được dùng chung với chiết khấu hạng thẻ</label>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <input type="checkbox" name="is_active" id="is_active" x-model="form.is_active" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Kích hoạt voucher này</label>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-5 py-2.5 rounded-xl font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm shadow-emerald-200">
                            Lưu thông tin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('voucherManager', () => ({
        showModal: false,
        isEditing: false,
        formAction: '',
        form: {
            id: null,
            title: '',
            code: '',
            description: '',
            discount_type: 'fixed',
            discount_amount: 0,
            max_discount: null,
            min_order_value: 0,
            category_id: '',
            cost_coins: 0,
            usage_limit: null,
            usage_per_user: 1,
            start_date: '',
            end_date: '',
            is_active: true
        },
        
        openAddModal() {
            this.isEditing = false;
            this.formAction = '<?php echo URLROOT; ?>/admin/voucher_add';
            this.form = {
                id: null,
                title: '',
                code: '',
                description: '',
                discount_type: 'fixed',
                discount_amount: 0,
                max_discount: null,
                min_order_value: 0,
                category_id: '',
                cost_coins: 0,
                usage_limit: null,
                usage_per_user: 1,
                start_date: '',
                end_date: '',
                is_combinable: false,
                is_active: true
            };
            this.showModal = true;
        },
        
        openEditModal(voucher) {
            this.isEditing = true;
            this.formAction = '<?php echo URLROOT; ?>/admin/voucher_edit/' + voucher.id;
            this.form = {
                id: voucher.id,
                title: voucher.title,
                code: voucher.code || '',
                description: voucher.description,
                discount_type: voucher.discount_type || 'fixed',
                discount_amount: voucher.discount_amount,
                max_discount: voucher.max_discount,
                min_order_value: voucher.min_order_value,
                category_id: voucher.category_id || '',
                cost_coins: voucher.cost_coins,
                usage_limit: voucher.usage_limit,
                usage_per_user: voucher.usage_per_user || 1,
                start_date: voucher.start_date || '',
                end_date: voucher.end_date || '',
                is_combinable: voucher.is_combinable == 1,
                is_active: voucher.is_active == 1
            };
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
        },
        
        deleteVoucher(id) {
            if(confirm('Bạn có chắc chắn muốn xóa/ẩn voucher này?')) {
                window.location.href = '<?php echo URLROOT; ?>/admin/voucher_delete/' + id;
            }
        }
    }));
});
</script>

<?php require APPROOT . '/views/admin/footer.php'; ?>
