<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6 lg:p-8 space-y-6" x-data="{ activeTab: 'clinic_records', showAddRecordForm: false, showAddVaccineForm: false }">
    <!-- Breadcrumb & Header -->
    <nav class="flex text-xs text-gray-500 mb-2" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li class="inline-flex items-center">
                <a href="<?php echo URLROOT; ?>/admin" class="hover:text-primary"><i class="fa-solid fa-chart-line mr-1"></i>Tổng quan</a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
                    <a href="<?php echo URLROOT; ?>/admin/pets" class="hover:text-primary">Quản lý Thú cưng</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
                    <span class="text-gray-900 font-semibold">Chi tiết thú cưng</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="flex justify-between items-center border-b border-gray-100 pb-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-notes-medical text-primary"></i> Sổ bệnh án & Sức khỏe
            </h1>
            <p class="text-xs text-gray-500 mt-1">Hồ sơ theo dõi sức khỏe tại nhà kết hợp y bạ điều trị của phòng khám.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/pets" 
           class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>

    <!-- Alert / Messages -->
    <?php flash('record_message'); ?>

    <!-- Pet & Owner Profile Cards -->
    <?php $pet = $data['pet']; ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pet Profile Card -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col sm:flex-row gap-6">
            <div class="w-32 h-32 bg-slate-100 rounded-2xl overflow-hidden shrink-0 border border-gray-100 shadow-inner mx-auto sm:mx-0">
                <?php if (!empty($pet->image)): ?>
                    <img src="<?php echo URLROOT . '/public/images/' . $pet->image; ?>" 
                         class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                        <i class="fa-solid fa-paw text-4xl"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="flex-1 text-center sm:text-left space-y-4">
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 justify-center sm:justify-start">
                        <h2 class="text-2xl font-black text-gray-900"><?php echo htmlspecialchars($pet->name); ?></h2>
                        <span class="inline-block px-2.5 py-0.5 bg-indigo-50 text-primary border border-indigo-100 text-[10px] font-black rounded-lg tracking-wider self-center">
                            <?php echo $pet->pet_code; ?>
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 font-bold uppercase mt-1">
                        Loài: <span class="text-gray-700"><?php echo htmlspecialchars($pet->species); ?></span> 
                        <?php echo !empty($pet->breed) ? ' | Giống: <span class="text-gray-700">' . htmlspecialchars($pet->breed) . '</span>' : ''; ?>
                    </p>
                </div>

                <!-- Attributes Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 text-xs">
                    <div>
                        <span class="text-gray-400 block text-[9px] uppercase font-black tracking-wider">Tuổi</span>
                        <span class="text-gray-800 font-bold"><?php echo $pet->age; ?> tháng tuổi</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[9px] uppercase font-black tracking-wider">Giới tính</span>
                        <span class="text-gray-800 font-bold">
                            <?php 
                                if ($pet->gender == 'male') echo 'Đực';
                                elseif ($pet->gender == 'female') echo 'Cái';
                                else echo 'Chưa rõ';
                            ?>
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[9px] uppercase font-black tracking-wider">Màu sắc</span>
                        <span class="text-gray-800 font-bold"><?php echo !empty($pet->color) ? htmlspecialchars($pet->color) : 'Chưa rõ'; ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[9px] uppercase font-black tracking-wider">Cân nặng</span>
                        <span class="text-gray-800 font-bold"><?php echo !empty($pet->weight) ? floatval($pet->weight) . ' kg' : 'Chưa rõ'; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Owner Contact Card -->
        <div class="bg-gradient-to-br from-slate-900 to-dark text-white rounded-3xl p-6 shadow-xl relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-12 -bottom-12 w-36 h-36 bg-white/5 rounded-full pointer-events-none"></div>
            <div>
                <h3 class="text-xs font-black uppercase text-primary-light tracking-widest mb-4 flex items-center gap-1">
                    <i class="fa-solid fa-user-shield"></i> Thông tin chủ nuôi
                </h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-[9px] text-gray-400 block uppercase font-bold tracking-wider">Họ và tên</span>
                        <span class="text-sm font-black text-white"><?php echo htmlspecialchars($pet->owner_name); ?></span>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-400 block uppercase font-bold tracking-wider">Số điện thoại</span>
                        <a href="tel:<?php echo $pet->owner_phone; ?>" class="text-sm font-bold text-indigo-300 hover:underline"><i class="fa-solid fa-phone text-xs mr-1"></i><?php echo htmlspecialchars($pet->owner_phone); ?></a>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-400 block uppercase font-bold tracking-wider">Địa chỉ Email</span>
                        <span class="text-xs font-medium text-gray-300"><?php echo htmlspecialchars($pet->owner_email); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'clinic_records'" 
                    :class="activeTab === 'clinic_records' ? 'border-primary text-primary border-b-2 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 text-sm font-semibold border-b-2 transition duration-300 focus:outline-none">
                <i class="fa-solid fa-prescription-bottle-medical mr-2"></i> Y bạ Lâm sàng (Khám chữa bệnh)
            </button>
            <button @click="activeTab = 'vaccinations'" 
                    :class="activeTab === 'vaccinations' ? 'border-primary text-primary border-b-2 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 text-sm font-semibold border-b-2 transition duration-300 focus:outline-none">
                <i class="fa-solid fa-syringe mr-2"></i> Lịch sử tiêm chủng
            </button>
        </nav>
    </div>

    <!-- TAB 1: Clinical Records -->
    <div x-show="activeTab === 'clinic_records'" x-transition class="space-y-6">
        <!-- Section actions -->
        <div class="flex justify-between items-center">
            <h3 class="text-base font-black text-gray-800 uppercase tracking-wider">Lịch sử điều trị phòng khám</h3>
            <button @click="showAddRecordForm = !showAddRecordForm" 
                    class="px-4 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition flex items-center gap-1.5 shadow-sm hover:shadow-primary/20">
                <i class="fa-solid" :class="showAddRecordForm ? 'fa-minus' : 'fa-plus'"></i>
                <span x-text="showAddRecordForm ? 'Đóng form nhập' : 'Thêm y bạ khám mới'"></span>
            </button>
        </div>

        <!-- Add Clinic Record Form -->
        <div x-show="showAddRecordForm" x-transition x-cloak 
             class="bg-white rounded-3xl p-6 border border-indigo-100 shadow-sm shadow-indigo-100/30">
            <h4 class="text-sm font-black text-gray-800 mb-4 flex items-center gap-1.5">
                <i class="fa-solid fa-file-medical text-indigo-600"></i> Ghi nhận ca khám mới
            </h4>
            <form action="<?php echo URLROOT; ?>/admin/pet_health_record_add/<?php echo $pet->id; ?>" method="POST" class="space-y-4 text-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="visit_date" class="block text-xs font-bold text-gray-600 mb-1">Ngày khám <span class="text-red-500">*</span></label>
                        <input type="date" id="visit_date" name="visit_date" value="<?php echo date('Y-m-d'); ?>" required
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                    <div>
                        <label for="appointment_id" class="block text-xs font-bold text-gray-600 mb-1">Mã lịch hẹn (nếu có)</label>
                        <input type="number" id="appointment_id" name="appointment_id" placeholder="Ví dụ: 12"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                <div>
                    <label for="diagnosis" class="block text-xs font-bold text-gray-600 mb-1">Chẩn đoán lâm sàng <span class="text-red-500">*</span></label>
                    <textarea id="diagnosis" name="diagnosis" rows="2" required placeholder="Nhập chẩn đoán triệu chứng, tên bệnh lý..."
                              class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary"></textarea>
                </div>

                <div>
                    <label for="treatment" class="block text-xs font-bold text-gray-600 mb-1">Chỉ định điều trị & Kê đơn thuốc</label>
                    <textarea id="treatment" name="treatment" rows="3" placeholder="Ghi rõ tên thuốc, liều lượng, số ngày uống, phương pháp xử lý..."
                              class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary"></textarea>
                </div>

                <div>
                    <label for="notes" class="block text-xs font-bold text-gray-600 mb-1">Ghi chú thêm & Hẹn tái khám</label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Ví dụ: Kiêng mỡ, vệ sinh tai hàng ngày, tái khám sau 7 ngày..."
                              class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showAddRecordForm = false" 
                            class="px-4 py-2 border border-gray-200 text-gray-500 rounded-xl font-bold hover:bg-slate-50">Hủy</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold shadow-md shadow-indigo-600/20">Lưu vào Y bạ</button>
                </div>
            </form>
        </div>

        <!-- Records List -->
        <?php if (empty($data['records'])): ?>
            <div class="bg-white rounded-3xl p-12 text-center text-gray-500 border border-gray-100 shadow-sm">
                <i class="fa-solid fa-clipboard-question text-gray-300 text-4xl mb-4 block"></i>
                <p class="text-sm font-bold">Chưa có lịch sử khám bệnh lâm sàng nào được ghi nhận</p>
                <p class="text-xs text-gray-400 mt-1">Sử dụng nút "Thêm y bạ khám mới" phía trên để lưu thông tin khám đầu tiên.</p>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($data['records'] as $record): ?>
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-4">
                        <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase block">Ngày khám</span>
                                <span class="text-sm text-gray-900 font-extrabold"><?php echo date('d/m/Y', strtotime($record->visit_date)); ?></span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-400 font-bold uppercase block">Bác sĩ phụ trách</span>
                                <span class="text-sm text-indigo-600 font-extrabold flex items-center gap-1 justify-end">
                                    <i class="fa-solid fa-user-md text-xs"></i> <?php echo htmlspecialchars($record->doctor_name); ?>
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <div class="bg-red-50/20 border border-red-100/50 p-4 rounded-2xl">
                                <span class="text-[10px] font-black uppercase text-red-700 tracking-wider mb-2 block flex items-center gap-1">
                                    <i class="fa-solid fa-circle-info"></i> Chẩn đoán lâm sàng
                                </span>
                                <p class="text-gray-800 leading-relaxed font-bold"><?php echo nl2br(htmlspecialchars($record->diagnosis)); ?></p>
                            </div>
                            <div class="bg-emerald-50/20 border border-emerald-100/50 p-4 rounded-2xl">
                                <span class="text-[10px] font-black uppercase text-emerald-700 tracking-wider mb-2 block flex items-center gap-1">
                                    <i class="fa-solid fa-pills"></i> Kế hoạch điều trị & Đơn thuốc
                                </span>
                                <p class="text-gray-800 leading-relaxed"><?php echo !empty($record->treatment) ? nl2br(htmlspecialchars($record->treatment)) : 'Chưa nhập chỉ định.'; ?></p>
                                
                                <?php if (!empty($record->prescriptions)): ?>
                                    <div class="mt-4 pt-3 border-t border-emerald-100/40 space-y-2">
                                        <p class="text-[10px] font-black uppercase text-emerald-600 tracking-widest flex items-center gap-1">
                                            <i class="fa-solid fa-receipt"></i> Chi tiết thuốc kê đơn:
                                        </p>
                                        <div class="space-y-2">
                                            <?php foreach ($record->prescriptions as $pres): ?>
                                                <div class="bg-white/80 p-2.5 rounded-xl border border-emerald-150 text-xs">
                                                    <div class="flex justify-between font-bold text-gray-800">
                                                        <span><?php echo htmlspecialchars($pres->product_name); ?></span>
                                                        <span class="text-primary">x<?php echo $pres->quantity; ?></span>
                                                    </div>
                                                    <?php if (!empty($pres->instruction)): ?>
                                                        <p class="text-[11px] text-gray-500 italic mt-1 pl-2 border-l border-emerald-500/30">
                                                            HDSD: <?php echo htmlspecialchars($pres->instruction); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($record->notes)): ?>
                            <div class="pt-3 border-t border-gray-50 text-xs text-gray-500 leading-relaxed">
                                <span class="font-bold text-gray-700 block mb-1">Ghi chú bổ sung:</span>
                                <?php echo nl2br(htmlspecialchars($record->notes)); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'manager' || $record->doctor_id == $_SESSION['user_id']): ?>
                            <div class="flex justify-end pt-2">
                                <a href="<?php echo URLROOT; ?>/admin/pet_health_record_delete/<?php echo $record->id; ?>" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa hồ sơ khám bệnh này?');"
                                   class="text-[10px] font-bold text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-trash-can mr-0.5"></i> Xóa bệnh án
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- TAB 2: Vaccinations -->
    <div x-show="activeTab === 'vaccinations'" x-transition class="space-y-6" x-cloak>
        <!-- Section actions -->
        <div class="flex justify-between items-center">
            <h3 class="text-base font-black text-gray-800 uppercase tracking-wider">Lịch sử tiêm phòng / chủng ngừa</h3>
            <button @click="showAddVaccineForm = !showAddVaccineForm" 
                    class="px-4 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition flex items-center gap-1.5 shadow-sm hover:shadow-primary/20">
                <i class="fa-solid" :class="showAddVaccineForm ? 'fa-minus' : 'fa-plus'"></i>
                <span x-text="showAddVaccineForm ? 'Đóng form nhập' : 'Thêm lịch sử tiêm phòng'"></span>
            </button>
        </div>

        <!-- Add Vaccine Form -->
        <div x-show="showAddVaccineForm" x-transition x-cloak 
             class="bg-white rounded-3xl p-6 border border-indigo-100 shadow-sm shadow-indigo-100/30">
            <h4 class="text-sm font-black text-gray-800 mb-4 flex items-center gap-1.5">
                <i class="fa-solid fa-syringe text-indigo-600"></i> Ghi nhận mũi tiêm phòng mới
            </h4>
            <form action="<?php echo URLROOT; ?>/admin/pet_vaccination_add/<?php echo $pet->id; ?>" method="POST" class="space-y-4 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="vaccine_name" class="block text-xs font-bold text-gray-600 mb-1">Tên Vắc xin <span class="text-red-500">*</span></label>
                        <input type="text" id="vaccine_name" name="vaccine_name" required placeholder="Ví dụ: Vắc xin dại, 5-in-1, 7-in-1..."
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                    <div>
                        <label for="vaccinated_date" class="block text-xs font-bold text-gray-600 mb-1">Ngày tiêm <span class="text-red-500">*</span></label>
                        <input type="date" id="vaccinated_date" name="vaccinated_date" value="<?php echo date('Y-m-d'); ?>" required
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                    <div>
                        <label for="next_due_date" class="block text-xs font-bold text-gray-600 mb-1">Ngày tiêm nhắc lại (nếu có)</label>
                        <input type="date" id="next_due_date" name="next_due_date"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-xs font-bold text-gray-600 mb-1">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Thông tin chi tiết về phản ứng sau tiêm, nhà sản xuất, số lô..."
                              class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showAddVaccineForm = false" 
                            class="px-4 py-2 border border-gray-200 text-gray-500 rounded-xl font-bold hover:bg-slate-50">Hủy</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold shadow-md shadow-indigo-600/20">Lưu thông tin</button>
                </div>
            </form>
        </div>

        <!-- Vaccinations List -->
        <?php if (empty($data['vaccinations'])): ?>
            <div class="bg-white rounded-3xl p-12 text-center text-gray-500 border border-gray-100 shadow-sm">
                <i class="fa-solid fa-syringe text-gray-300 text-4xl mb-4 block"></i>
                <p class="text-sm font-bold">Chưa có lịch sử tiêm chủng nào được ghi nhận</p>
                <p class="text-xs text-gray-400 mt-1">Sử dụng nút "Thêm lịch sử tiêm phòng" phía trên để lưu thông tin.</p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-3xl border border-gray-150 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                           <tr class="bg-slate-50 border-b border-gray-100 text-gray-600 text-xs font-bold uppercase tracking-wider">
                                <th class="py-4 px-6">Tên Vắc xin</th>
                                <th class="py-4 px-6">Ngày tiêm</th>
                                <th class="py-4 px-6">Ngày tái chủng dự kiến</th>
                                <th class="py-4 px-6">Ghi chú</th>
                                <th class="py-4 px-6 text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            <?php foreach ($data['vaccinations'] as $vaccine): ?>
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-4 px-6 font-extrabold text-gray-900">
                                        <span class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <?php echo htmlspecialchars($vaccine->vaccine_name); ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 font-semibold">
                                        <?php echo date('d/m/Y', strtotime($vaccine->vaccinated_date)); ?>
                                    </td>
                                    <td class="py-4 px-6">
                                        <?php if (!empty($vaccine->next_due_date)): ?>
                                            <?php 
                                                $isOverdue = strtotime($vaccine->next_due_date) < time();
                                                $badgeClass = $isOverdue 
                                                    ? 'bg-rose-50 text-rose-700 border-rose-100' 
                                                    : 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                            ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 border text-xs font-bold rounded-lg <?php echo $badgeClass; ?>">
                                                <i class="fa-solid <?php echo $isOverdue ? 'fa-triangle-exclamation' : 'fa-calendar-check'; ?> text-[10px]"></i>
                                                <?php echo date('d/m/Y', strtotime($vaccine->next_due_date)); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic text-xs">Không có lịch nhắc</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-6 text-gray-500 max-w-xs truncate" title="<?php echo htmlspecialchars($vaccine->notes ?? ''); ?>">
                                        <?php echo !empty($vaccine->notes) ? htmlspecialchars($vaccine->notes) : '<span class="text-gray-300">-</span>'; ?>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="<?php echo URLROOT; ?>/admin/pet_vaccination_delete/<?php echo $vaccine->id; ?>" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa lịch sử tiêm chủng này?');"
                                           class="inline-flex items-center gap-1 text-xs font-bold text-red-500 hover:text-red-700 transition">
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

</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
