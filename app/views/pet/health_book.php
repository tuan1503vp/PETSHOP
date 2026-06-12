<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'daily_logs', showAddLogModal: false }">
    <!-- Breadcrumb -->
    <nav class="flex text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?php echo URLROOT; ?>" class="hover:text-primary"><i class="fa-solid fa-house mr-2"></i>Trang chủ</a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-xs mx-2"></i>
                    <a href="<?php echo URLROOT; ?>/pet" class="hover:text-primary">Thú cưng của tôi</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-xs mx-2"></i>
                    <span class="text-gray-900 font-semibold">Sổ sức khỏe</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Pet Summary Header Card -->
    <?php $pet = $data['pet']; ?>
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 sm:p-8 mb-8 flex flex-col md:flex-row gap-8 items-center md:items-start">
        <div class="w-32 h-32 rounded-2xl overflow-hidden bg-slate-100 shrink-0 border border-gray-100 shadow-inner">
            <?php if (!empty($pet->image)): ?>
                <img src="<?php echo URLROOT . '/public/images/' . $pet->image; ?>" 
                     alt="<?php echo htmlspecialchars($pet->name); ?>" 
                     class="w-full h-full object-cover">
            <?php else: ?>
                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                    <i class="fa-solid fa-paw text-4xl"></i>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex-1 text-center md:text-left">
            <div class="flex flex-col md:flex-row md:items-center gap-3 justify-center md:justify-start">
                <h1 class="text-3xl font-black text-gray-900"><?php echo htmlspecialchars($pet->name); ?></h1>
                <span class="inline-block px-3 py-1 rounded-xl bg-indigo-50 text-primary border border-indigo-100 text-xs font-black tracking-wider self-center">
                    Mã số: <?php echo $pet->pet_code; ?>
                </span>
            </div>
            <p class="text-sm text-gray-500 font-semibold mt-1">
                <?php echo htmlspecialchars($pet->species); ?> 
                <?php echo !empty($pet->breed) ? '• ' . htmlspecialchars($pet->breed) : ''; ?>
            </p>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 text-xs text-gray-600 font-medium max-w-2xl bg-slate-50 p-4 rounded-2xl border border-slate-100/50">
                <div class="space-y-1">
                    <span class="text-gray-400 block text-[10px] uppercase font-black tracking-wider">Tuổi</span>
                    <span class="text-gray-800 font-bold"><?php echo $pet->age; ?> tháng tuổi</span>
                </div>
                <div class="space-y-1">
                    <span class="text-gray-400 block text-[10px] uppercase font-black tracking-wider">Giới tính</span>
                    <span class="text-gray-800 font-bold">
                        <?php 
                            if ($pet->gender == 'male') echo 'Đực';
                            elseif ($pet->gender == 'female') echo 'Cái';
                            else echo 'Chưa rõ';
                        ?>
                    </span>
                </div>
                <div class="space-y-1">
                    <span class="text-gray-400 block text-[10px] uppercase font-black tracking-wider">Màu sắc</span>
                    <span class="text-gray-800 font-bold"><?php echo !empty($pet->color) ? htmlspecialchars($pet->color) : 'Chưa rõ'; ?></span>
                </div>
                <div class="space-y-1">
                    <span class="text-gray-400 block text-[10px] uppercase font-black tracking-wider">Cân nặng</span>
                    <span class="text-gray-800 font-bold"><?php echo !empty($pet->weight) ? floatval($pet->weight) . ' kg' : 'Chưa rõ'; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert / Message -->
    <?php flash('health_log_message'); ?>
    <?php flash('record_message'); ?>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'daily_logs'" 
                    :class="activeTab === 'daily_logs' ? 'border-primary text-primary border-b-2 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 text-sm font-semibold border-b-2 transition duration-300 focus:outline-none">
                <i class="fa-solid fa-notes-medical mr-2"></i> Nhật ký sức khỏe (Chủ nuôi)
            </button>
            <button @click="activeTab = 'clinic_records'" 
                    :class="activeTab === 'clinic_records' ? 'border-primary text-primary border-b-2 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 text-sm font-semibold border-b-2 transition duration-300 focus:outline-none">
                <i class="fa-solid fa-stethoscope mr-2"></i> Lịch sử khám bệnh (PETSHOP)
            </button>
        </nav>
    </div>

    <!-- TAB 1: Daily Health Logs -->
    <div x-show="activeTab === 'daily_logs'" x-transition class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Theo dõi sức khỏe hàng ngày</h3>
                <p class="text-xs text-gray-500 mt-0.5">Thường xuyên cập nhật cân nặng, nhiệt độ để theo dõi trạng thái bé cưng.</p>
            </div>
            <button @click="showAddLogModal = true" 
                    class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-primary rounded-xl hover:bg-indigo-700 transition shadow-sm hover:shadow-primary/20">
                <i class="fa-solid fa-plus mr-1.5"></i> Thêm nhật ký
            </button>
        </div>

        <?php if (empty($data['logs'])): ?>
            <div class="bg-white rounded-[2rem] border border-gray-100 p-12 text-center text-gray-500 shadow-sm">
                <i class="fa-regular fa-clipboard text-gray-300 text-4xl mb-4 block"></i>
                <p class="text-sm font-medium">Bé chưa có nhật ký sức khỏe nào.</p>
                <button @click="showAddLogModal = true" class="text-xs font-bold text-primary hover:underline mt-2">Thêm nhật ký ngay</button>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50 text-[10px] text-gray-400 font-black uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 text-left">Ngày ghi</th>
                                <th class="px-6 py-4 text-left">Cân nặng</th>
                                <th class="px-6 py-4 text-left">Nhiệt độ</th>
                                <th class="px-6 py-4 text-left">Trạng thái</th>
                                <th class="px-6 py-4 text-left">Triệu chứng & Ghi chú</th>
                                <th class="px-6 py-4 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                            <?php foreach ($data['logs'] as $log): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-bold">
                                        <?php echo date('d/m/Y', strtotime($log->log_date)); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo !empty($log->weight) ? floatval($log->weight) . ' kg' : '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo !empty($log->temperature) ? floatval($log->temperature) . ' °C' : '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $badgeClass = "bg-gray-100 text-gray-700";
                                            if ($log->status === 'Rất tốt') $badgeClass = "bg-green-50 text-green-700 border border-green-100";
                                            elseif ($log->status === 'Bình thường') $badgeClass = "bg-blue-50 text-blue-700 border border-blue-100";
                                            elseif ($log->status === 'Mệt mỏi') $badgeClass = "bg-amber-50 text-amber-700 border border-amber-100";
                                            elseif ($log->status === 'Ốm yếu') $badgeClass = "bg-red-50 text-red-700 border border-red-100";
                                        ?>
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wider <?php echo $badgeClass; ?>">
                                            <?php echo htmlspecialchars($log->status); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if (!empty($log->symptoms)): ?>
                                            <div class="text-xs text-red-500 font-semibold mb-0.5">Triệu chứng: <?php echo htmlspecialchars($log->symptoms); ?></div>
                                        <?php endif; ?>
                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($log->notes); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="<?php echo URLROOT; ?>/pet/delete_health_log/<?php echo $log->id; ?>" 
                                           onclick="return confirm('Bạn muốn xóa nhật ký sức khỏe ngày <?php echo date('d/m/Y', strtotime($log->log_date)); ?>?');"
                                           class="text-red-500 hover:text-red-700 font-bold text-xs">
                                            <i class="fa-solid fa-trash-can"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- TAB 2: Clinic Records -->
    <div x-show="activeTab === 'clinic_records'" x-transition class="space-y-6">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Lịch sử y bạ khám bệnh</h3>
            <p class="text-xs text-gray-500 mt-0.5">Hồ sơ bệnh án được ghi lại bởi các bác sĩ thú y của PETSHOP.</p>
        </div>

        <?php if (empty($data['records'])): ?>
            <div class="bg-white rounded-[2rem] border border-gray-100 p-12 text-center text-gray-500 shadow-sm">
                <i class="fa-solid fa-stethoscope text-gray-300 text-4xl mb-4 block"></i>
                <p class="text-sm font-medium">Bé chưa có lịch sử khám bệnh nào tại PETSHOP.</p>
                <p class="text-xs text-gray-400 mt-1">Khi bạn đặt lịch hẹn chăm sóc hoặc khám bệnh, hồ sơ khám chữa bệnh sẽ hiển thị ở đây.</p>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($data['records'] as $record): ?>
                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 sm:p-8 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-gray-100 pb-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-primary flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-file-medical text-primary/80"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Hồ sơ y tế ngày <?php echo date('d/m/Y', strtotime($record->visit_date)); ?></h4>
                                    <p class="text-xs text-gray-400">Khám bởi: <span class="font-bold text-gray-600"><?php echo htmlspecialchars($record->doctor_name); ?></span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Record Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <div class="bg-red-50/30 border border-red-100/50 p-4 rounded-2xl">
                                <h5 class="text-xs font-black uppercase text-red-700 tracking-wider mb-2 flex items-center gap-1.5">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Chẩn đoán từ bác sĩ
                                </h5>
                                <p class="text-gray-800 leading-relaxed font-semibold"><?php echo nl2br(htmlspecialchars($record->diagnosis)); ?></p>
                            </div>

                            <div class="bg-emerald-50/30 border border-emerald-100/50 p-4 rounded-2xl">
                                <h5 class="text-xs font-black uppercase text-emerald-700 tracking-wider mb-2 flex items-center gap-1.5">
                                    <i class="fa-solid fa-pills"></i> Đơn thuốc & Hướng điều trị
                                </h5>
                                <p class="text-gray-800 leading-relaxed"><?php echo !empty($record->treatment) ? nl2br(htmlspecialchars($record->treatment)) : 'Chưa có chỉ định điều trị.'; ?></p>
                            </div>
                        </div>

                        <?php if (!empty($record->notes)): ?>
                            <div class="mt-4 pt-4 border-t border-gray-50 text-xs text-gray-500 leading-relaxed">
                                <span class="font-bold text-gray-700 block mb-1">Ghi chú thêm:</span>
                                <?php echo nl2br(htmlspecialchars($record->notes)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- MODAL: Add Daily Log -->
    <div x-show="showAddLogModal" 
         x-cloak
         class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-[2rem] max-w-lg w-full overflow-hidden shadow-2xl border border-gray-100" 
             @click.away="showAddLogModal = false">
            <div class="p-6 border-b border-gray-50 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-lg font-black text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-primary"></i> Ghi nhật ký sức khỏe
                </h3>
                <button @click="showAddLogModal = false" class="text-gray-400 hover:text-gray-600 transition focus:outline-none">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="<?php echo URLROOT; ?>/pet/add_health_log/<?php echo $pet->id; ?>" method="POST" class="p-6 space-y-4 text-sm text-gray-700">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="log_date" class="block text-xs font-bold text-gray-600 mb-1.5">Ngày ghi <span class="text-red-500">*</span></label>
                        <input type="date" id="log_date" name="log_date" value="<?php echo date('Y-m-d'); ?>" required
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-600 mb-1.5">Trạng thái sức khỏe</label>
                        <select id="status" name="status" 
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="Bình thường">Bình thường</option>
                            <option value="Rất tốt">Rất tốt</option>
                            <option value="Mệt mỏi">Mệt mỏi</option>
                            <option value="Ốm yếu">Ốm yếu</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="weight" class="block text-xs font-bold text-gray-600 mb-1.5">Cân nặng (kg)</label>
                        <input type="number" id="weight" name="weight" step="0.01" min="0.01" max="150" placeholder="Ví dụ: 5.2"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                    <div>
                        <label for="temperature" class="block text-xs font-bold text-gray-600 mb-1.5">Thân nhiệt (°C)</label>
                        <input type="number" id="temperature" name="temperature" step="0.1" min="30" max="45" placeholder="Ví dụ: 38.5"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                <div>
                    <label for="symptoms" class="block text-xs font-bold text-gray-600 mb-1.5">Triệu chứng bất thường (nếu có)</label>
                    <input type="text" id="symptoms" name="symptoms" placeholder="Ví dụ: Ho, bỏ ăn, ngứa tai..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>

                <div>
                    <label for="notes" class="block text-xs font-bold text-gray-600 mb-1.5">Chi tiết & Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Nhập mô tả hoạt động hoặc ăn uống của bé..."
                              class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary"></textarea>
                </div>

                <div class="pt-4 border-t border-gray-50 flex justify-end gap-2">
                    <button type="button" @click="showAddLogModal = false" 
                            class="px-4 py-2 border border-gray-200 text-gray-500 rounded-xl font-bold hover:bg-slate-50">Hủy</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary hover:bg-indigo-700 text-white rounded-xl font-bold shadow-md shadow-primary/20">Lưu nhật ký</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
