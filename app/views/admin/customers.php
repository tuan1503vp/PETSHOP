<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6" x-data="{ 
    showModal: false, 
    loading: false,
    activeTab: 'pets',
    customer: {},
    pets: [],
    orders: [],
    appointments: [],
    
    async viewDetails(id) {
        this.loading = true;
        this.showModal = true;
        this.activeTab = 'pets';
        try {
            const res = await fetch('<?php echo URLROOT; ?>/admin/customer_details/' + id);
            const data = await res.json();
            if (data.success) {
                this.customer = data.customer;
                this.pets = data.pets;
                this.orders = data.orders;
                this.appointments = data.appointments;
            }
        } catch (e) {
            console.error(e);
        } finally {
            this.loading = false;
        }
    }
}">
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
                                    <button @click="viewDetails(<?php echo $customer->id; ?>)" class="text-gray-400 hover:text-primary transition animate-pulse" title="Xem chi tiết khách hàng">
                                        <i class="fa-solid fa-circle-info text-lg"></i>
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

    <!-- Modal Chi Tiết Khách Hàng -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showModal = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Content -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">
                <!-- Close Button -->
                <div class="absolute top-6 right-6 z-10">
                    <button @click="showModal = false" class="w-10 h-10 bg-gray-50 hover:bg-gray-100 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 transition focus:outline-none">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8">
                    <!-- Loading state -->
                    <div x-show="loading" class="flex flex-col items-center justify-center py-20">
                        <div class="w-12 h-12 border-4 border-indigo-200 border-t-primary rounded-full animate-spin mb-4"></div>
                        <p class="text-sm font-bold text-gray-500">Đang tải thông tin khách hàng...</p>
                    </div>

                    <!-- Main Data -->
                    <div x-show="!loading">
                        <!-- Header / User Card -->
                        <div class="flex flex-col md:flex-row items-start md:items-center gap-6 pb-6 mb-6 border-b border-gray-100">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-primary to-indigo-600 flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-primary/20 shrink-0">
                                <span x-text="customer.fullname ? customer.fullname.charAt(0).toUpperCase() : ''"></span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-black text-gray-900 leading-tight" x-text="customer.fullname"></h3>
                                <p class="text-xs text-gray-500 font-bold mt-1 flex items-center gap-2">
                                    <span class="inline-block w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                    <span>Hạng Hội viên: <strong class="text-primary" x-text="customer.membership_level || 'Đồng'"></strong></span>
                                </p>
                            </div>
                            <div class="flex gap-4 shrink-0 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <div class="text-center px-4 border-r border-slate-200">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tổng tích lũy</p>
                                    <p class="text-base font-black text-green-600 mt-0.5" x-text="new Intl.NumberFormat('vi-VN').format(customer.total_spent || 0) + ' đ'"></p>
                                </div>
                                <div class="text-center px-4">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tích lũy năm nay</p>
                                    <p class="text-base font-black text-primary mt-0.5" x-text="new Intl.NumberFormat('vi-VN').format(customer.annual_spent || 0) + ' đ'"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Grid Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-slate-50/50 p-5 rounded-2xl border border-slate-100/30">
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email liên hệ</span>
                                <span class="text-sm font-bold text-gray-700 mt-1 block" x-text="customer.email"></span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Số điện thoại</span>
                                <span class="text-sm font-bold text-gray-700 mt-1 block" x-text="customer.phone || 'Chưa cập nhật'"></span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Địa chỉ thường trú</span>
                                <span class="text-sm font-bold text-gray-700 mt-1 block" x-text="customer.address || 'Chưa cập nhật'"></span>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div class="flex border-b border-gray-100 mb-6">
                            <button @click="activeTab = 'pets'" :class="activeTab === 'pets' ? 'border-primary text-primary font-black' : 'border-transparent text-gray-400 hover:text-gray-600 font-bold'" class="px-6 py-3 border-b-2 text-sm transition">
                                🐾 Thú cưng (<span x-text="pets.length"></span>)
                            </button>
                            <button @click="activeTab = 'orders'" :class="activeTab === 'orders' ? 'border-primary text-primary font-black' : 'border-transparent text-gray-400 hover:text-gray-600 font-bold'" class="px-6 py-3 border-b-2 text-sm transition">
                                🛍️ Lịch sử mua hàng (<span x-text="orders.length"></span>)
                            </button>
                            <button @click="activeTab = 'appointments'" :class="activeTab === 'appointments' ? 'border-primary text-primary font-black' : 'border-transparent text-gray-400 hover:text-gray-600 font-bold'" class="px-6 py-3 border-b-2 text-sm transition">
                                🗓️ Lịch hẹn Spa/Bác sĩ (<span x-text="appointments.length"></span>)
                            </button>
                        </div>

                        <!-- Tab Contents -->
                        <div>
                            <!-- TAB: Pets -->
                            <div x-show="activeTab === 'pets'">
                                <template x-if="pets.length === 0">
                                    <div class="text-center py-8">
                                        <i class="fa-solid fa-paw text-3xl text-gray-200 mb-2"></i>
                                        <p class="text-xs text-gray-400 font-medium">Khách hàng chưa đăng ký thú cưng nào</p>
                                    </div>
                                </template>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="pets.length > 0">
                                    <template x-for="pet in pets" :key="pet.id">
                                        <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-2xl bg-white shadow-sm hover:shadow transition">
                                            <div class="w-12 h-12 rounded-full overflow-hidden bg-slate-50 border border-gray-100 shrink-0 flex items-center justify-center text-gray-300">
                                                <template x-if="pet.image">
                                                    <img :src="'<?php echo URLROOT; ?>/public/images/' + pet.image" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!pet.image">
                                                    <i class="fa-solid fa-paw text-xl"></i>
                                                </template>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-black text-gray-800" x-text="pet.name"></h4>
                                                <p class="text-xs text-gray-500 font-medium mt-0.5">
                                                    <span x-text="pet.species"></span> • <span x-text="pet.breed || 'Chưa rõ giống'"></span>
                                                </p>
                                                <p class="text-[10px] text-gray-400 mt-1">
                                                    <span x-text="pet.age"></span> tháng tuổi • <span x-text="pet.weight ? pet.weight + 'kg' : 'Chưa cân'"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- TAB: Orders -->
                            <div x-show="activeTab === 'orders'">
                                <template x-if="orders.length === 0">
                                    <div class="text-center py-8">
                                        <i class="fa-solid fa-box-open text-3xl text-gray-200 mb-2"></i>
                                        <p class="text-xs text-gray-400 font-medium">Chưa có đơn hàng nào được ghi nhận</p>
                                    </div>
                                </template>
                                <div class="space-y-4" x-show="orders.length > 0">
                                    <template x-for="order in orders" :key="order.id">
                                        <div class="p-4 border border-gray-100 rounded-2xl bg-white shadow-sm">
                                            <div class="flex justify-between items-center mb-3">
                                                <div>
                                                    <span class="text-xs font-black text-gray-800" x-text="'#ORD-' + String(order.id).padStart(5, '0')"></span>
                                                    <span class="text-[10px] text-gray-400 ml-2" x-text="order.formatted_date"></span>
                                                </div>
                                                <span class="text-xs font-black" :class="{
                                                    'text-orange-500': order.status === 'pending',
                                                    'text-blue-500': order.status === 'confirmed',
                                                    'text-indigo-500': order.status === 'shipping',
                                                    'text-green-600': order.status === 'completed',
                                                    'text-red-500': order.status === 'cancelled'
                                                }" x-text="order.status.toUpperCase()"></span>
                                            </div>
                                            <!-- Items list -->
                                            <div class="text-xs text-gray-600 space-y-1 bg-slate-50/50 p-2.5 rounded-xl border border-slate-100/30">
                                                <template x-for="item in order.items">
                                                    <div class="flex justify-between">
                                                        <span x-text="item.product_name"></span>
                                                        <span class="font-bold text-gray-800" x-text="'x' + item.quantity"></span>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-50">
                                                <span class="text-[10px] text-gray-400" x-text="'Thanh toán: ' + order.payment_method.toUpperCase()"></span>
                                                <span class="text-sm font-black text-green-600" x-text="new Intl.NumberFormat('vi-VN').format(order.total_amount) + ' đ'"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- TAB: Appointments -->
                            <div x-show="activeTab === 'appointments'">
                                <template x-if="appointments.length === 0">
                                    <div class="text-center py-8">
                                        <i class="fa-solid fa-calendar-xmark text-3xl text-gray-200 mb-2"></i>
                                        <p class="text-xs text-gray-400 font-medium">Chưa có lịch hẹn nào được ghi nhận</p>
                                    </div>
                                </template>
                                <div class="space-y-4" x-show="appointments.length > 0">
                                    <template x-for="appt in appointments" :key="appt.id">
                                        <div class="p-4 border border-gray-100 rounded-2xl bg-white shadow-sm flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-black text-gray-800" x-text="appt.service_name"></h4>
                                                <p class="text-xs text-gray-400 font-bold mt-1 flex items-center gap-1.5">
                                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                                    <span x-text="appt.formatted_date"></span>
                                                </p>
                                                <p class="text-[10px] text-gray-500 font-medium mt-1" x-show="appt.notes" x-text="'Ghi chú: ' + appt.notes"></p>
                                            </div>
                                            <div class="text-right">
                                                <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider inline-block mb-2" :class="{
                                                    'bg-orange-100 text-orange-700': appt.status === 'pending',
                                                    'bg-blue-100 text-blue-700': appt.status === 'confirmed',
                                                    'bg-green-100 text-green-700': appt.status === 'completed',
                                                    'bg-red-100 text-red-700': appt.status === 'cancelled'
                                                }" x-text="appt.status"></span>
                                                <p class="text-xs font-black text-indigo-600" x-text="new Intl.NumberFormat('vi-VN').format(appt.final_price || appt.price || 0) + ' đ'"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
