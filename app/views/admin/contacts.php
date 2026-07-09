<?php require APPROOT . '/views/admin/header.php'; ?>

<style>
[x-cloak] { display: none !important; }
</style>

<div class="flex-1 overflow-auto bg-gray-50 p-8" x-data="contactManagement()">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Hộp thư / Liên hệ</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý phản hồi và yêu cầu hỗ trợ từ khách hàng</p>
        </div>
    </div>
    
    <?php flash('contact_message'); ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100 font-bold">
                    <tr>
                        <th class="px-6 py-4">Tên khách hàng</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 w-1/3">Nội dung</th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4">Ngày gửi</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-if="contacts.length === 0">
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">Chưa có liên hệ nào</td>
                        </tr>
                    </template>
                    <template x-for="contact in contacts" :key="contact.id">
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 font-bold text-gray-800" x-text="contact.name"></td>
                            <td class="px-6 py-4 text-gray-600">
                                <a :href="'mailto:' + contact.email" class="hover:text-primary text-sm font-medium flex items-center gap-1.5">
                                    <i class="fa-solid fa-envelope text-gray-400 text-xs"></i>
                                    <span x-text="contact.email"></span>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-500 italic max-w-[320px] truncate" :title="contact.message" x-text="'&quot;' + contact.message + '&quot;'"></p>
                            </td>
                            <td class="px-6 py-4">
                                <template x-if="contact.status === 'pending'">
                                    <span class="px-2.5 py-0.5 bg-amber-50 text-amber-700 rounded-lg text-xs font-bold border border-amber-200">Chờ xử lý</span>
                                </template>
                                <template x-if="contact.status !== 'pending'">
                                    <span class="px-2.5 py-0.5 bg-green-50 text-green-700 rounded-lg text-xs font-bold border border-green-200">
                                        <i class="fa-solid fa-check mr-1"></i> Đã phản hồi
                                    </span>
                                </template>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-xs font-medium" x-text="formatDate(contact.created_at)"></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <!-- Xem chi tiết -->
                                    <button type="button" @click="openViewModal(contact)" class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all text-xs" title="Xem chi tiết">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    
                                    <!-- Trả lời nhanh -->
                                    <template x-if="contact.status === 'pending'">
                                        <button type="button" @click="openReplyModal(contact)" class="w-7 h-7 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all text-xs" title="Phản hồi">
                                            <i class="fa-solid fa-reply"></i>
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===================== MODAL XEM CHI TIẾT & PHẢN HỒI ===================== -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-dark/60 transition-opacity backdrop-blur-sm" @click="showModal = false"></div>

            <!-- Modal panel -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-xl w-full">
                <template x-if="activeContact">
                    <div>
                        <!-- Header -->
                        <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-primary"></i> Chi tiết thư liên hệ
                            </h3>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="p-6 space-y-4 max-h-[calc(100vh-16rem)] overflow-y-auto">
                            <!-- Khách hàng info -->
                            <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100 text-xs">
                                <div>
                                    <span class="block text-gray-400 font-bold uppercase tracking-wider mb-1">Khách hàng</span>
                                    <span class="font-bold text-gray-800 text-sm" x-text="activeContact.name"></span>
                                </div>
                                <div>
                                    <span class="block text-gray-400 font-bold uppercase tracking-wider mb-1">Địa chỉ Email</span>
                                    <a :href="'mailto:' + activeContact.email" class="font-bold text-primary text-sm hover:underline" x-text="activeContact.email"></a>
                                </div>
                                <div class="mt-2">
                                    <span class="block text-gray-400 font-bold uppercase tracking-wider mb-1">Thời gian gửi</span>
                                    <span class="font-semibold text-gray-700" x-text="formatDate(activeContact.created_at)"></span>
                                </div>
                                <div class="mt-2">
                                    <span class="block text-gray-400 font-bold uppercase tracking-wider mb-1">Trạng thái</span>
                                    <template x-if="activeContact.status === 'pending'">
                                        <span class="inline-block px-2 py-0.5 bg-amber-50 text-amber-700 rounded-md font-bold border border-amber-200">Chờ xử lý</span>
                                    </template>
                                    <template x-if="activeContact.status !== 'pending'">
                                        <span class="inline-block px-2 py-0.5 bg-green-50 text-green-700 rounded-md font-bold border border-green-200">Đã phản hồi</span>
                                    </template>
                                </div>
                            </div>

                            <!-- Nội dung tin nhắn -->
                            <div>
                                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nội dung liên hệ</span>
                                <div class="p-4 bg-white border border-gray-200 text-gray-700 rounded-2xl italic text-sm shadow-inner leading-relaxed whitespace-pre-line" x-text="activeContact.message"></div>
                            </div>

                            <!-- Phần Lịch sử phản hồi nếu có -->
                            <template x-if="activeContact.status === 'replied'">
                                <div class="border-t border-dashed border-gray-200 pt-4 space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="block text-xs font-bold text-green-600 uppercase tracking-wider"><i class="fa-solid fa-reply-all mr-1"></i> Nội dung đã phản hồi</span>
                                        <span class="text-[10px] text-gray-400 font-bold" x-text="formatDate(activeContact.replied_at)"></span>
                                    </div>
                                    <div class="p-4 bg-green-50/50 border border-green-150 text-gray-700 rounded-2xl text-sm leading-relaxed whitespace-pre-line" x-text="activeContact.reply_message || 'Đã gửi phản hồi qua email.'"></div>
                                </div>
                            </template>

                            <!-- Form viết phản hồi nếu trạng thái là Pending -->
                            <template x-if="activeContact.status === 'pending'">
                                <div class="border-t border-dashed border-gray-200 pt-4" x-data="{ showForm: false }">
                                    <div x-show="!showForm" class="flex justify-center">
                                        <button type="button" @click="showForm = true" class="px-6 py-2 bg-primary text-white font-bold rounded-xl text-xs shadow-md shadow-primary/20 hover:bg-indigo-700 transition-all flex items-center gap-1.5">
                                            <i class="fa-solid fa-reply"></i> Viết phản hồi ngay
                                        </button>
                                    </div>

                                    <div x-show="showForm" x-transition>
                                        <form :action="'<?php echo URLROOT; ?>/admin/contact_update/' + activeContact.id" method="POST" class="space-y-4">
                                            <input type="hidden" name="status" value="replied">
                                            <input type="hidden" name="customer_email" :value="activeContact.email">
                                            <input type="hidden" name="customer_name" :value="activeContact.name">
                                            
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 mb-2">Nội dung phản hồi (Sẽ tự động gửi email tới khách hàng):</label>
                                                <textarea name="reply_message" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary/50 text-sm focus:border-primary outline-none transition-all" placeholder="Nhập nội dung phản hồi của bạn..." required></textarea>
                                            </div>

                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="showForm = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition">Hủy</button>
                                                <button type="submit" class="px-4 py-2 bg-primary hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md shadow-primary/30 transition flex items-center gap-1.5">
                                                    <i class="fa-solid fa-paper-plane"></i> Gửi & Hoàn tất
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
function contactManagement() {
    return {
        contacts: <?php echo json_encode($data['contacts']); ?>,
        showModal: false,
        activeContact: null,

        openViewModal(contact) {
            this.activeContact = contact;
            this.showModal = false; // reset
            this.$nextTick(() => {
                this.showModal = true;
            });
        },

        openReplyModal(contact) {
            this.activeContact = contact;
            this.showModal = true;
            // Delay check to open form inside modal if present
            this.$nextTick(() => {
                const btn = document.querySelector('[class*="Viết phản hồi ngay"]');
                if (btn) btn.click();
            });
        },

        formatDate(dateStr) {
            if (!dateStr) return '—';
            try {
                const date = new Date(dateStr);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${day}/${month}/${year} ${hours}:${minutes}`;
            } catch (e) {
                return dateStr;
            }
        }
    };
}
</script>

<?php require APPROOT . '/views/admin/footer.php'; ?>
