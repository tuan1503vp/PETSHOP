<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6 lg:p-8 space-y-6" x-data="{ showQuickRecord: false }">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-paw text-primary"></i> Quản lý Thú cưng hệ thống
            </h1>
            <p class="text-xs text-gray-500 mt-1">Danh sách toàn bộ thú cưng của khách hàng đăng ký trên hệ thống để theo dõi y tế.</p>
        </div>
        <div>
            <button @click="showQuickRecord = true" 
                    class="w-full sm:w-auto px-5 py-3 bg-gradient-to-r from-primary to-indigo-600 hover:from-indigo-600 hover:to-primary text-white text-sm font-bold rounded-2xl shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="fa-solid fa-file-medical"></i> Ghi y bạ nhanh bằng mã số
            </button>
        </div>
    </div>

    <!-- Alert Message -->
    <?php flash('admin_pet_error'); ?>

    <!-- Search Card -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <form action="<?php echo URLROOT; ?>/admin/pets" method="GET" class="flex flex-col sm:flex-row gap-4 items-center">
            <div class="relative flex-1 w-full">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" name="q" value="<?php echo htmlspecialchars($data['search']); ?>" 
                       placeholder="Tìm theo mã số, tên bé, tên khách hàng hoặc số điện thoại chủ nuôi..." 
                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
            </div>
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-3 bg-primary text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition shadow-sm hover:shadow-primary/20 shrink-0">
                Tìm kiếm
            </button>
            <?php if (!empty($data['search'])): ?>
                <a href="<?php echo URLROOT; ?>/admin/pets" 
                   class="w-full sm:w-auto px-6 py-3 border border-gray-200 text-gray-500 text-sm font-bold rounded-2xl text-center hover:bg-slate-50 transition shrink-0">
                    Xóa lọc
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <?php if (empty($data['pets'])): ?>
            <div class="p-12 text-center text-gray-500">
                <i class="fa-solid fa-folder-open text-gray-300 text-4xl mb-4 block"></i>
                <p class="text-sm font-bold">Không tìm thấy thú cưng nào phù hợp</p>
                <p class="text-xs text-gray-400 mt-1">Hãy thử tìm kiếm với từ khóa khác.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50 text-[10px] text-gray-400 font-black uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-left">Mã Số</th>
                            <th class="px-6 py-4 text-left">Thú Cưng</th>
                            <th class="px-6 py-4 text-left">Loài / Giống</th>
                            <th class="px-6 py-4 text-left">Thông Tin Sinh Học</th>
                            <th class="px-6 py-4 text-left">Chủ Sở Hữu</th>
                            <th class="px-6 py-4 text-left">Ngày Đăng Ký</th>
                            <th class="px-6 py-4 text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                        <?php foreach ($data['pets'] as $pet): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- Pet Code -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 bg-indigo-50 text-primary border border-indigo-100 text-xs font-black rounded-lg tracking-wider">
                                        <?php echo $pet->pet_code; ?>
                                    </span>
                                </td>
                                
                                <!-- Pet Name + Image -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-slate-100 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                                            <?php if (!empty($pet->image)): ?>
                                                <img src="<?php echo URLROOT . '/public/images/' . $pet->image; ?>" 
                                                     class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                    <i class="fa-solid fa-paw text-lg"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <span class="text-sm font-bold text-gray-900 block"><?php echo htmlspecialchars($pet->name); ?></span>
                                            <span class="text-[10px] text-gray-400"><?php echo !empty($pet->color) ? htmlspecialchars($pet->color) : 'Không rõ màu'; ?></span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Species / Breed -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 block"><?php echo htmlspecialchars($pet->species); ?></span>
                                    <span class="text-xs text-gray-400"><?php echo !empty($pet->breed) ? htmlspecialchars($pet->breed) : '-'; ?></span>
                                </td>

                                <!-- Age / Gender / Weight -->
                                <td class="px-6 py-4 whitespace-nowrap text-xs space-y-1">
                                    <div><i class="fa-solid fa-cake-candles text-primary/70 mr-1"></i><?php echo $pet->age; ?> tháng</div>
                                    <div class="flex items-center gap-3">
                                        <span>
                                            <i class="fa-solid fa-venus-mars text-secondary/70 mr-1"></i>
                                            <?php 
                                                if ($pet->gender == 'male') echo 'Đực';
                                                elseif ($pet->gender == 'female') echo 'Cái';
                                                else echo 'Chưa rõ';
                                            ?>
                                        </span>
                                        <?php if (!empty($pet->weight)): ?>
                                            <span>
                                                <i class="fa-solid fa-weight-scale text-emerald-500/70 mr-1"></i>
                                                <?php echo floatval($pet->weight); ?> kg
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Owner Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 font-bold block"><?php echo htmlspecialchars($pet->owner_name); ?></span>
                                    <span class="text-xs text-gray-400 block"><i class="fa-solid fa-phone text-[10px] mr-1"></i><?php echo htmlspecialchars($pet->owner_phone); ?></span>
                                </td>

                                <!-- Registered At -->
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400">
                                    <?php echo date('d/m/Y H:i', strtotime($pet->created_at)); ?>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="<?php echo URLROOT; ?>/admin/pet_detail/<?php echo $pet->id; ?>" 
                                       class="px-4 py-2 bg-slate-100 hover:bg-primary hover:text-white rounded-xl text-xs font-bold text-gray-700 transition">
                                        <i class="fa-solid fa-circle-info mr-1"></i> Chi tiết & Y bạ
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal Ghi y bạ nhanh -->
    <div x-show="showQuickRecord" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-cloak>
        
        <div x-show="showQuickRecord"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white rounded-3xl w-full max-w-xl overflow-hidden shadow-2xl border border-gray-100"
             @click.away="showQuickRecord = false">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary to-indigo-600 px-6 py-4 text-white flex justify-between items-center">
                <h3 class="font-black text-base flex items-center gap-2">
                    <i class="fa-solid fa-file-medical"></i> Ghi y bạ khám chữa bệnh nhanh
                </h3>
                <button @click="showQuickRecord = false" class="text-white/80 hover:text-white transition focus:outline-none">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="<?php echo URLROOT; ?>/admin/pet_health_record_add_by_code" method="POST" class="p-6 space-y-4 text-sm">
                <!-- Mã thú cưng + Ngày khám -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="modal_pet_code" class="block text-xs font-bold text-gray-600 mb-1">Mã thú cưng <span class="text-red-500">*</span></label>
                        <input type="text" id="modal_pet_code" name="pet_code" placeholder="Ví dụ: PET-123456" required
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition uppercase">
                    </div>
                    <div>
                        <label for="modal_visit_date" class="block text-xs font-bold text-gray-600 mb-1">Ngày khám <span class="text-red-500">*</span></label>
                        <input type="date" id="modal_visit_date" name="visit_date" value="<?php echo date('Y-m-d'); ?>" required
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    </div>
                </div>

                <!-- Chẩn đoán lâm sàng -->
                <div>
                    <label for="modal_diagnosis" class="block text-xs font-bold text-gray-600 mb-1">Chẩn đoán lâm sàng <span class="text-red-500">*</span></label>
                    <textarea id="modal_diagnosis" name="diagnosis" rows="2" required placeholder="Nhập triệu chứng, chẩn đoán của bác sĩ..."
                              class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition"></textarea>
                </div>

                <!-- Kế hoạch điều trị & Đơn thuốc -->
                <div>
                    <label for="modal_treatment" class="block text-xs font-bold text-gray-600 mb-1">Chỉ định điều trị & Đơn thuốc</label>
                    <textarea id="modal_treatment" name="treatment" rows="3" placeholder="Nhập đơn thuốc, liều lượng, cách sử dụng..."
                              class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition"></textarea>
                </div>

                <!-- Ghi chú thêm & Hẹn tái khám -->
                <div>
                    <label for="modal_notes" class="block text-xs font-bold text-gray-600 mb-1">Ghi chú & Hẹn tái khám</label>
                    <textarea id="modal_notes" name="notes" rows="2" placeholder="Ví dụ: Tái khám sau 3 ngày, kiêng ăn đồ tanh..."
                              class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                    <button type="button" @click="showQuickRecord = false" 
                            class="px-5 py-2.5 bg-gray-100 text-gray-500 rounded-xl font-bold hover:bg-gray-200 transition">Hủy</button>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-primary hover:bg-indigo-700 text-white rounded-xl font-bold shadow-md shadow-primary/20 transition">Lưu vào sổ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
