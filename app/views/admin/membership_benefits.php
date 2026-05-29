<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-8">
    <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Cấu hình Ưu đãi Hội viên</h1>
            <p class="text-gray-500 mt-2">Thiết lập quyền lợi và giảm giá cho từng cấp độ hội viên</p>
        </div>
    </div>

    <?php flash('benefit_message'); ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <?php foreach($data['benefits'] as $benefit): ?>
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-500 group">
                <form action="<?php echo URLROOT; ?>/admin/membership_benefits" method="POST">
                    <input type="hidden" name="id" value="<?php echo $benefit->id; ?>">
                    
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl font-black 
                                <?php 
                                    $l = $benefit->membership_level;
                                    echo $l == 'VIP' ? 'bg-purple-100 text-purple-600' : 
                                        ($l == 'Bạch kim' ? 'bg-blue-100 text-blue-600' : 
                                        ($l == 'Vàng' ? 'bg-yellow-100 text-yellow-600' : 
                                        ($l == 'Bạc' ? 'bg-slate-100 text-slate-600' : 'bg-orange-100 text-orange-600')));
                                ?>">
                                <?php echo substr($l, 0, 1); ?>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900">Hạng <?php echo $benefit->membership_level; ?></h3>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Thiết lập đặc quyền</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Giảm giá sản phẩm</p>
                            <div class="flex items-center justify-end gap-2">
                                <input type="number" name="discount_percent" value="<?php echo $benefit->discount_percent; ?>" 
                                       class="w-16 px-2 py-1 bg-gray-50 border border-gray-200 rounded-lg text-sm font-black text-primary focus:ring-2 focus:ring-primary/20 outline-none text-center">
                                <span class="font-black text-gray-400">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2">Nội dung ưu đãi (Hiển thị cho khách)</label>
                        <textarea name="benefit_text" rows="3" 
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary/20 outline-none transition leading-relaxed"><?php echo $benefit->benefit_text; ?></textarea>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                        <label class="flex items-center cursor-pointer group/toggle">
                            <div class="relative">
                                <input type="checkbox" name="free_service" class="sr-only peer" <?php echo $benefit->free_service ? 'checked' : ''; ?>>
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </div>
                            <span class="ml-3 text-xs font-black text-gray-500 peer-checked:text-primary transition">Miễn phí dịch vụ</span>
                        </label>
                        <button type="submit" class="px-8 py-2.5 bg-dark text-white text-xs font-black rounded-xl hover:bg-primary transition shadow-lg shadow-dark/10 group-hover:shadow-primary/25">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
