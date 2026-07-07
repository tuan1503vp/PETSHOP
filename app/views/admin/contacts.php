<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="flex-1 overflow-auto bg-gray-50 p-8">
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
                    <?php if(empty($data['contacts'])): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">Chưa có liên hệ nào</td>
                    </tr>
                    <?php else: foreach($data['contacts'] as $contact): ?>
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 font-bold text-gray-800">
                            <?php echo $contact->name; ?>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <a href="mailto:<?php echo $contact->email; ?>" class="hover:text-primary text-sm font-medium"><i class="fa-solid fa-envelope mr-1 text-gray-400"></i> <?php echo $contact->email; ?></a>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-600 line-clamp-2 italic" title="<?php echo htmlspecialchars($contact->message); ?>">
                                "<?php echo htmlspecialchars($contact->message); ?>"
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($contact->status == 'pending'): ?>
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold border border-amber-200">Chờ xử lý</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold border border-green-200"><i class="fa-solid fa-check mr-1"></i> Đã phản hồi</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs font-medium">
                            <?php echo date('d/m/Y H:i', strtotime($contact->created_at)); ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="<?php echo URLROOT; ?>/admin/contact_update/<?php echo $contact->id; ?>" method="POST" class="inline-block" x-data="{ openReply: false }">
                                <?php if($contact->status == 'pending'): ?>
                                    <button type="button" @click="openReply = true" class="text-xs font-bold bg-primary/10 text-primary hover:bg-primary hover:text-white px-3 py-1.5 rounded-lg transition">Phản hồi</button>
                                    
                                    <!-- Modal -->
                                    <div x-show="openReply" class="fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center backdrop-blur-sm" x-cloak>
                                        <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden shadow-2xl text-left" @click.away="openReply = false">
                                            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                                <h3 class="font-bold text-gray-800 text-base">Phản hồi: <?php echo $contact->name; ?></h3>
                                                <button type="button" @click="openReply = false" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark text-lg"></i></button>
                                            </div>
                                            <div class="p-5">
                                                <input type="hidden" name="status" value="replied">
                                                <input type="hidden" name="customer_email" value="<?php echo $contact->email; ?>">
                                                <input type="hidden" name="customer_name" value="<?php echo $contact->name; ?>">
                                                
                                                <div class="mb-4">
                                                    <label class="block text-xs font-bold text-gray-500 mb-2">Nội dung yêu cầu:</label>
                                                    <div class="p-3 bg-gray-50 text-gray-600 rounded-lg text-sm border border-gray-100 italic">"<?php echo htmlspecialchars($contact->message); ?>"</div>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block text-xs font-bold text-gray-500 mb-2">Gửi email phản hồi (Tới: <span class="text-primary"><?php echo $contact->email; ?></span>)</label>
                                                    <textarea name="reply_message" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 text-sm" placeholder="Nhập nội dung phản hồi. Hệ thống sẽ tự động gửi email..." required></textarea>
                                                </div>
                                                
                                                <div class="flex justify-end gap-2 mt-2">
                                                    <button type="button" @click="openReply = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Hủy</button>
                                                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-indigo-700 text-white font-bold rounded-xl text-sm shadow-md shadow-primary/30 transition flex items-center"><i class="fa-solid fa-paper-plane mr-2"></i> Gửi & Đánh dấu XL</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 font-bold"><i class="fa-solid fa-check-double text-green-500 mr-1"></i> Đã xong</span>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
