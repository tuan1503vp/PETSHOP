<?php require APPROOT . '/views/admin/header.php'; ?>

<style>
@media print {
    body { background-color: #fff; margin: 0; padding: 0; }
    /* Ẩn sidebar, header, navbar và các nút điều hướng */
    .sidebar, .navbar, .header, nav, aside, .print-hidden, a.hover\:text-primary { display: none !important; }
    
    /* Layout A4 */
    @page { size: A4 portrait; margin: 20mm; }
    
    /* Reset main wrapper */
    main, .max-w-3xl, .p-6 { width: 100% !important; max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
    
    /* Tinh chỉnh thẻ hiển thị */
    .bg-white, .rounded-3xl, .border { border: none !important; border-radius: 0 !important; box-shadow: none !important; }
    .bg-gradient-to-r, .from-green-600, .to-emerald-500, .bg-emerald-50, .bg-gray-50, .bg-orange-50, .bg-red-50, .bg-blue-50 {
        background-color: transparent !important;
        background: transparent !important;
        border: 1px solid #e5e7eb !important;
        color: #000 !important;
    }
    
    /* Typography cho in ấn */
    * { color: #000 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .text-emerald-600, .text-indigo-600, .text-emerald-500, .text-white { color: #000 !important; }
    
    /* Thêm Header Chứng nhận (chỉ hiện khi in) */
    .print-header { display: block !important; text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 20px; }
    .print-header h1 { font-size: 24px; font-weight: bold; text-transform: uppercase; margin: 0 0 10px 0; }
    .print-header p { margin: 5px 0; font-size: 14px; }
}

.print-header { display: none; }
</style>

<div class="p-6 max-w-3xl mx-auto">
    <!-- Breadcrumb & Print Button -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 print-hidden">
            <a href="<?php echo URLROOT; ?>/admin/services" class="hover:text-primary font-medium">
                <i class="fa-solid fa-calendar-check mr-1"></i>Lịch dịch vụ
            </a>
            <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
            <span class="font-bold text-gray-700">Chi tiết ca #<?php echo str_pad($data['appointment']->id, 5, '0', STR_PAD_LEFT); ?></span>
        </div>
        <button onclick="window.print()" class="print-hidden bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition flex items-center gap-2">
            <i class="fa-solid fa-print"></i> In Chứng Nhận (PDF)
        </button>
    </div>
    
    <!-- Print Header Only -->
    <div class="print-header">
        <h1>CHỨNG NHẬN TIÊM CHỦNG THÚ CƯNG</h1>
        <p><strong>PETSHOP VETERINARY CLINIC</strong></p>
        <p>Mã ca khám: #<?php echo str_pad($data['appointment']->id, 5, '0', STR_PAD_LEFT); ?> | Ngày: <?php echo date('d/m/Y', strtotime($data['appointment']->appointment_date)); ?></p>
    </div>

    <!-- Info card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-500 text-white flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-emerald-100 font-bold uppercase tracking-widest">Dịch vụ đã hoàn thành</p>
                    <h1 class="text-lg font-black">#<?php echo str_pad($data['appointment']->id, 5, '0', STR_PAD_LEFT); ?> — <?php echo htmlspecialchars($data['appointment']->service_name); ?></h1>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-emerald-200 font-bold uppercase">Tổng thanh toán</p>
                <p class="text-lg font-black"><?php echo number_format($data['appointment']->final_price ?? $data['appointment']->service_price ?? 0, 0, ',', '.'); ?>đ</p>
            </div>
        </div>
        <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-4">
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Khách hàng</p>
                <p class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($data['appointment']->customer_name); ?></p>
                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($data['appointment']->customer_phone ?? '—'); ?></p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Thú cưng</p>
                <p class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($data['appointment']->pet_name ?? 'Chưa xác định'); ?></p>
                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($data['appointment']->pet_species ?? ''); ?></p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Thời gian hẹn</p>
                <p class="text-sm font-bold text-indigo-600"><?php echo date('H:i', strtotime($data['appointment']->appointment_time)); ?></p>
                <p class="text-xs text-gray-500"><?php echo date('d/m/Y', strtotime($data['appointment']->appointment_date)); ?></p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Bác sĩ phụ trách</p>
                <p class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($data['appointment']->doctor_name ?? 'Chưa phân công'); ?></p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Trạng thái lịch</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase bg-green-50 text-green-600 border border-green-200 mt-1">Đã hoàn thành</span>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Hoàn thành lúc</p>
                <p class="text-xs text-gray-500"><?php echo !empty($data['record']->created_at) ? date('d/m/Y H:i', strtotime($data['record']->created_at)) : '—'; ?></p>
            </div>
        </div>
    </div>

    <?php if (!empty($data['vaccineRecord'])): ?>
    <!-- Vaccine Record Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-6 mb-6 relative overflow-hidden">
        <!-- Decor watermark -->
        <i class="fa-solid fa-syringe absolute -right-6 -top-6 text-9xl text-emerald-50/50 -rotate-12 pointer-events-none"></i>
        
        <h2 class="text-sm font-black text-emerald-600 uppercase tracking-widest flex items-center gap-2 border-b border-gray-100 pb-3 relative z-10">
            <i class="fa-solid fa-shield-cat text-emerald-500"></i> Chứng nhận tiêm chủng & Sàng lọc lâm sàng
        </h2>
        
        <div class="relative z-10 space-y-6">
            <!-- 1. Vắc-xin & Ngày -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-emerald-50/30 rounded-2xl border border-emerald-100 flex flex-col justify-center">
                    <h4 class="text-[10px] font-bold text-emerald-600/70 uppercase tracking-wider mb-1">Tên Vắc-xin / Mũi tiêm</h4>
                    <p class="text-lg font-black text-emerald-700 flex items-center gap-2">
                        <i class="fa-solid fa-vial-circle-check"></i> <?php echo htmlspecialchars($data['vaccineRecord']->vaccine_name); ?>
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Ngày thực hiện</h4>
                        <p class="text-sm font-bold text-gray-800"><?php echo date('d/m/Y', strtotime($data['vaccineRecord']->vaccinated_date)); ?></p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Số lô (Batch No.)</h4>
                        <p class="text-sm font-bold text-gray-800"><?php echo !empty($data['vaccineRecord']->batch_number) ? htmlspecialchars($data['vaccineRecord']->batch_number) : '<span class="text-gray-400 italic">—</span>'; ?></p>
                    </div>
                </div>
            </div>

            <!-- 2. Sinh hiệu & Bác sĩ -->
            <div class="grid grid-cols-3 gap-4">
                <div class="p-4 bg-orange-50/30 rounded-2xl border border-orange-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-500">
                        <i class="fa-solid fa-weight-scale"></i>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-bold text-orange-600/70 uppercase tracking-wider mb-0.5">Cân nặng</h4>
                        <p class="text-sm font-bold text-gray-800"><?php echo !empty($data['vaccineRecord']->weight) ? $data['vaccineRecord']->weight . ' kg' : '—'; ?></p>
                    </div>
                </div>
                <div class="p-4 bg-red-50/30 rounded-2xl border border-red-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500">
                        <i class="fa-solid fa-temperature-half"></i>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-bold text-red-600/70 uppercase tracking-wider mb-0.5">Thân nhiệt</h4>
                        <p class="text-sm font-bold text-gray-800"><?php echo !empty($data['vaccineRecord']->temperature) ? $data['vaccineRecord']->temperature . ' °C' : '—'; ?></p>
                    </div>
                </div>
                <div class="p-4 bg-blue-50/30 rounded-2xl border border-blue-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-bold text-blue-600/70 uppercase tracking-wider mb-0.5">Bác sĩ phụ trách</h4>
                        <p class="text-sm font-bold text-gray-800 line-clamp-1"><?php echo htmlspecialchars($data['vaccineRecord']->veterinarian_name ?? $data['appointment']->doctor_name ?? '—'); ?></p>
                    </div>
                </div>
            </div>

            <!-- 3. Kết quả Sàng lọc & Dặn dò -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                        <i class="fa-solid fa-microscope text-indigo-400"></i> Khám lâm sàng & Xét nghiệm
                    </h4>
                    <div class="text-sm text-gray-700 leading-relaxed font-medium">
                        <?php 
                            if (!empty($data['appointment']->selected_test)) {
                                echo '<p class="mb-2"><strong class="text-indigo-600">Yêu cầu từ khách:</strong> ' . htmlspecialchars($data['appointment']->selected_test) . '</p>';
                            }
                            echo nl2br(htmlspecialchars($data['vaccineRecord']->test_result ?? 'Chưa ghi nhận kết quả sàng lọc.')); 
                        ?>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 h-full">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <i class="fa-solid fa-clipboard-list text-emerald-400"></i> Theo dõi & Ghi chú
                        </h4>
                        <div class="text-sm text-gray-700 leading-relaxed font-medium space-y-3">
                            <?php if (!empty($data['vaccineRecord']->reaction_notes)): ?>
                            <div>
                                <span class="text-emerald-700 font-bold block mb-1">Dặn dò sau tiêm:</span>
                                <?php echo nl2br(htmlspecialchars($data['vaccineRecord']->reaction_notes)); ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($data['vaccineRecord']->notes)): ?>
                            <div>
                                <span class="text-gray-500 font-bold block mb-1">Ghi chú khác:</span>
                                <?php echo nl2br(htmlspecialchars($data['vaccineRecord']->notes)); ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="pt-3 mt-3 border-t border-gray-200">
                                <span class="text-gray-500 font-bold block mb-1">Lịch hẹn tái chủng:</span>
                                <?php if (!empty($data['vaccineRecord']->next_due_date)): ?>
                                    <span class="text-indigo-600 font-black inline-flex items-center gap-1 bg-indigo-50 px-2 py-1 rounded">
                                        <i class="fa-regular fa-calendar-check"></i> <?php echo date('d/m/Y', strtotime($data['vaccineRecord']->next_due_date)); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">Không có</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Medical Record Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-6 mb-6">
        <h2 class="text-sm font-black text-gray-800 uppercase tracking-widest flex items-center gap-2 border-b border-gray-100 pb-3">
            <i class="fa-solid fa-file-medical text-primary"></i> Thông tin bệnh án lâm sàng
        </h2>
        
        <div class="grid grid-cols-1 gap-6">
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Chẩn đoán lâm sàng</h4>
                <div class="p-4 bg-gray-50 rounded-2xl text-sm text-gray-800 border border-gray-100 leading-relaxed font-medium">
                    <?php echo !empty($data['record']->diagnosis) ? nl2br(htmlspecialchars($data['record']->diagnosis)) : '<span class="text-gray-400 italic">Không có thông tin chẩn đoán.</span>'; ?>
                </div>
            </div>
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Chỉ định điều trị</h4>
                <div class="p-4 bg-gray-50 rounded-2xl text-sm text-gray-800 border border-gray-100 leading-relaxed font-medium">
                    <?php echo !empty($data['record']->treatment) ? nl2br(htmlspecialchars($data['record']->treatment)) : '<span class="text-gray-400 italic">Không có chỉ định điều trị.</span>'; ?>
                </div>
            </div>
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Ghi chú & Hẹn tái khám</h4>
                <div class="p-4 bg-gray-50 rounded-2xl text-sm text-gray-800 border border-gray-100 leading-relaxed font-medium">
                    <?php echo !empty($data['record']->notes) ? nl2br(htmlspecialchars($data['record']->notes)) : '<span class="text-gray-400 italic">Không có ghi chú thêm.</span>'; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescriptions Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-sm font-black text-gray-800 uppercase tracking-widest flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
            <i class="fa-solid fa-pills text-indigo-500"></i> Đơn thuốc kèm theo
        </h2>
        
        <?php if (!empty($data['prescriptions'])): ?>
        <div class="overflow-hidden border border-gray-100 rounded-2xl">
            <table class="min-w-full divide-y divide-gray-150">
                <thead class="bg-indigo-50/30">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tên thuốc/sản phẩm</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase w-20">Số lượng</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cách sử dụng</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php foreach ($data['prescriptions'] as $pres): ?>
                    <tr class="text-sm">
                        <td class="px-4 py-3 font-bold text-gray-800"><?php echo htmlspecialchars($pres->product_name); ?></td>
                        <td class="px-4 py-3 text-center font-black text-indigo-600"><?php echo $pres->quantity; ?></td>
                        <td class="px-4 py-3 text-gray-600 font-medium"><?php echo htmlspecialchars($pres->instruction ?? 'Theo chỉ định của bác sĩ'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-6 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
            <p class="text-xs text-gray-400 italic">Không có đơn thuốc nào được kê kèm theo.</p>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Footer Actions -->
    <div class="flex justify-between items-center bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <button onclick="window.close()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
            <i class="fa-solid fa-xmark"></i> Đóng tab này
        </button>
        <button onclick="window.print()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-indigo-600/10">
            <i class="fa-solid fa-print"></i> In bệnh án
        </button>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
