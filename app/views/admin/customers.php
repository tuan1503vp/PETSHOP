<?php require APPROOT . '/views/admin/header.php'; ?>

<style>
/* Custom thin scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
[x-cloak] { display: none !important; }
</style>

<div class="p-6" x-data="customerManagement()">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Quản lý Khách hàng</h1>
            <p class="text-sm text-gray-500 mt-1">Danh sách các tài khoản khách hàng đã đăng ký trên hệ thống</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <!-- Search bar -->
            <div class="relative flex-1 sm:w-64">
                <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Tìm tên, email, sđt..." class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/50 text-sm outline-none transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400 text-xs"></i>
            </div>
            <div class="bg-indigo-50 text-primary px-4 py-2 rounded-xl text-sm font-bold border border-indigo-100 shrink-0 text-center">
                Tổng cộng: <span x-text="filteredCustomers.length"></span> / <span x-text="customers.length"></span> khách
            </div>
        </div>
    </div>

    <?php flash('customer_message'); ?>

    <!-- Main Table Container with Scrollbars -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto overflow-y-auto max-h-[600px] custom-scrollbar">
            <table class="w-full text-sm text-left table-auto">
                <thead class="bg-gray-50/50 border-b border-gray-100 font-bold sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 text-xs text-gray-400 uppercase tracking-widest">Khách hàng</th>
                        <th class="px-6 py-4 text-xs text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="px-6 py-4 text-xs text-gray-400 uppercase tracking-widest">Điện thoại</th>
                        <th class="px-6 py-4 text-xs text-gray-400 uppercase tracking-widest">Địa chỉ</th>
                        <th class="px-6 py-4 text-xs text-gray-400 uppercase tracking-widest">Tổng chi</th>
                        <th class="px-6 py-4 text-xs text-gray-400 uppercase tracking-widest">Hạng</th>
                        <th class="px-6 py-4 text-xs text-gray-400 uppercase tracking-widest">Trạng thái</th>
                        <th class="px-6 py-4 text-xs text-gray-400 uppercase tracking-widest">Ngày tham gia</th>
                        <th class="px-6 py-4 text-right text-xs text-gray-400 uppercase tracking-widest">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-if="paginatedCustomers.length === 0">
                        <tr>
                            <td colspan="9" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-users-slash text-5xl text-gray-200 mb-4"></i>
                                    <p class="text-gray-400 font-medium">Không tìm thấy khách hàng nào</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-for="cust in paginatedCustomers" :key="cust.id">
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-9 w-9 rounded-full bg-indigo-100 text-primary flex items-center justify-center font-black text-xs mr-3 shadow-inner" x-text="(cust.fullname || '').charAt(0).toUpperCase()"></div>
                                    <span class="text-sm font-bold text-gray-900" x-text="cust.fullname"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600" x-text="cust.email"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium" x-text="cust.phone || '---'"></td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500 line-clamp-1 max-w-[200px]" :title="cust.address" x-text="cust.address || 'Chưa cập nhật'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-black text-green-600 block" x-text="formatMoney(cust.total_spent)"></span>
                                <span class="text-[10px] text-gray-400" x-text="'Năm nay: ' + formatMoney(cust.annual_spent)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider" :class="levelClass(cust.membership_level)" x-text="cust.membership_level || 'Đồng'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider block mb-1" :class="cust.is_active == 1 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'" x-text="cust.is_active == 1 ? 'Hoạt động' : 'Vô hiệu hóa'"></span>
                                <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider block" :class="cust.is_verified == 1 ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700'" x-text="cust.is_verified == 1 ? 'Đã xác minh' : 'Chờ xác minh'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-400" x-text="formatDate(cust.created_at)"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="viewDetails(cust.id)" class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all text-xs" title="Xem chi tiết">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>
                                    <button @click="toggleStatus(cust.id)" class="w-7 h-7 flex items-center justify-center rounded-lg text-xs transition-all" 
                                            :class="cust.is_active == 1 ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-green-50 text-green-600 hover:bg-green-600 hover:text-white'" 
                                            :title="cust.is_active == 1 ? 'Vô hiệu hóa tài khoản' : 'Kích hoạt tài khoản'">
                                        <i :class="cust.is_active == 1 ? 'fa-solid fa-user-slash' : 'fa-solid fa-user-check'"></i>
                                    </button>
                                    <form :action="'<?php echo URLROOT; ?>/admin/customer_delete/' + cust.id" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khách hàng này? Thao tác này không thể hoàn tác.')" class="inline-block">
                                        <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-xs" title="Xóa tài khoản">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between text-sm" x-show="totalPages > 1">
            <div class="text-gray-500">
                Hiển thị trang <span class="font-bold text-gray-800" x-text="currentPage"></span> / <span x-text="totalPages"></span>
            </div>
            <div class="flex items-center gap-1">
                <button @click="prevPage()" :disabled="currentPage === 1" class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-angle-left"></i> Trước
                </button>
                <template x-for="p in totalPages" :key="p">
                    <button @click="setPage(p)" :class="currentPage === p ? 'bg-primary text-white border-primary' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'" class="w-8 h-8 flex items-center justify-center border rounded-lg transition-colors font-bold text-xs" x-text="p"></button>
                </template>
                <button @click="nextPage()" :disabled="currentPage === totalPages" class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Sau <i class="fa-solid fa-angle-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Chi Tiết Khách Hàng -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <!-- Modal Content -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">
                <!-- Close Button -->
                <div class="absolute top-6 right-6 z-10">
                    <button @click="showModal = false" class="w-9 h-9 bg-gray-50 hover:bg-gray-100 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 transition outline-none">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8">
                    <!-- Loading state -->
                    <div x-show="loading" class="flex flex-col items-center justify-center py-20">
                        <div class="w-10 h-10 border-4 border-indigo-200 border-t-primary rounded-full animate-spin mb-4"></div>
                        <p class="text-xs font-bold text-gray-500">Đang tải thông tin khách hàng...</p>
                    </div>

                    <!-- Main Data -->
                    <div x-show="!loading">
                        <!-- Header / User Card -->
                        <div class="flex flex-col md:flex-row items-start md:items-center gap-6 pb-6 mb-6 border-b border-gray-100">
                            <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-primary to-indigo-600 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-primary/20 shrink-0" x-text="customer.fullname ? customer.fullname.charAt(0).toUpperCase() : ''"></div>
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-gray-900 leading-tight" x-text="customer.fullname"></h3>
                                <p class="text-[11px] text-gray-500 font-bold mt-1 flex items-center gap-1.5">
                                    <span class="inline-block w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                    <span>Hạng Hội viên: <strong class="text-primary" x-text="customer.membership_level || 'Đồng'"></strong></span>
                                </p>
                            </div>
                            <div class="flex gap-4 shrink-0 bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs">
                                <div class="text-center px-3 border-r border-slate-200">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Tổng tích lũy</p>
                                    <p class="text-sm font-black text-green-600 mt-0.5" x-text="formatMoney(customer.total_spent)"></p>
                                </div>
                                <div class="text-center px-3">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Tích lũy năm nay</p>
                                    <p class="text-sm font-black text-primary mt-0.5" x-text="formatMoney(customer.annual_spent)"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Grid Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 bg-slate-50/50 p-4 rounded-xl border border-slate-100/30 text-xs">
                            <div>
                                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider">Email liên hệ</span>
                                <span class="font-bold text-gray-700 mt-0.5 block" x-text="customer.email"></span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider">Số điện thoại</span>
                                <span class="font-bold text-gray-700 mt-0.5 block" x-text="customer.phone || 'Chưa cập nhật'"></span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider">Địa chỉ thường trú</span>
                                <span class="font-bold text-gray-700 mt-0.5 block" x-text="customer.address || 'Chưa cập nhật'"></span>
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

                        <!-- Tab Contents with Inner Scrollbars -->
                        <div class="max-h-[350px] overflow-y-auto pr-2 custom-scrollbar">
                            <!-- TAB: Pets -->
                            <div x-show="activeTab === 'pets'">
                                <template x-if="pets.length === 0">
                                    <div class="text-center py-12">
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
                                    <div class="text-center py-12">
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
                                                    <span class="text-[10px] text-gray-400 ml-2" x-text="formatDate(order.created_at)"></span>
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
                                                <span class="text-[10px] text-gray-400" x-text="'Thanh toán: ' + (order.payment_method || 'mặt đất').toUpperCase()"></span>
                                                <span class="text-sm font-black text-green-600" x-text="formatMoney(order.total_amount)"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- TAB: Appointments -->
                            <div x-show="activeTab === 'appointments'">
                                <template x-if="appointments.length === 0">
                                    <div class="text-center py-12">
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
                                                    <span x-text="formatDate(appt.appointment_date) + ' ' + (appt.appointment_time || '')"></span>
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
                                                <p class="text-xs font-black text-indigo-600" x-text="formatMoney(appt.final_price || appt.price || 0)"></p>
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

<script>
function customerManagement() {
    return {
        customers: <?php echo json_encode($data['customers']); ?>,
        showModal: false, 
        loading: false,
        activeTab: 'pets',
        customer: {},
        pets: [],
        orders: [],
        appointments: [],
        
        // Phân trang & Tìm kiếm
        currentPage: 1,
        itemsPerPage: 10,
        searchQuery: '',

        get filteredCustomers() {
            const query = this.searchQuery.toLowerCase().trim();
            if (!query) return this.customers;
            return this.customers.filter(c => {
                const name = (c.fullname || '').toLowerCase();
                const email = (c.email || '').toLowerCase();
                const phone = (c.phone || '');
                return name.includes(query) || email.includes(query) || phone.includes(query);
            });
        },

        get totalPages() {
            return Math.ceil(this.filteredCustomers.length / this.itemsPerPage) || 1;
        },

        get paginatedCustomers() {
            // Đảm bảo trang hiện tại không vượt quá giới hạn tổng số trang khi lọc
            if (this.currentPage > this.totalPages) {
                this.currentPage = this.totalPages;
            }
            if (this.currentPage < 1) {
                this.currentPage = 1;
            }
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredCustomers.slice(start, start + this.itemsPerPage);
        },

        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },

        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },

        setPage(page) {
            this.currentPage = page;
        },

        levelClass(level) {
            if (level === 'Bạc') return 'bg-slate-100 text-slate-700';
            if (level === 'Vàng') return 'bg-yellow-100 text-yellow-700';
            if (level === 'Bạch kim') return 'bg-blue-100 text-blue-700';
            if (level === 'VIP') return 'bg-purple-100 text-purple-700 animate-pulse border border-purple-200';
            return 'bg-orange-100 text-orange-700'; // Đồng
        },

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
        },

        async toggleStatus(id) {
            if (!confirm('Bạn có chắc chắn muốn thay đổi trạng thái hoạt động của tài khoản này?')) return;
            try {
                const res = await fetch('<?php echo URLROOT; ?>/admin/toggle_user_status/' + id);
                const data = await res.json();
                if (data.success) {
                    const index = this.customers.findIndex(c => c.id === id);
                    if (index !== -1) {
                        this.customers[index].is_active = data.new_status;
                    }
                } else {
                    alert(data.message || 'Lỗi xảy ra');
                }
            } catch (e) {
                console.error(e);
                alert('Không thể kết nối đến máy chủ.');
            }
        },

        formatMoney(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount || 0) + ' đ';
        },

        formatDate(dateStr) {
            if (!dateStr) return '—';
            try {
                const date = new Date(dateStr);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            } catch (e) {
                return dateStr;
            }
        }
    };
}
</script>

<?php require APPROOT . '/views/admin/footer.php'; ?>
