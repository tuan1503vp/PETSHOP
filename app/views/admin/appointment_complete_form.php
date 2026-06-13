<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6 max-w-2xl mx-auto">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?php echo URLROOT; ?>/admin/services" class="hover:text-primary font-medium">
            <i class="fa-solid fa-calendar-check mr-1"></i>Lịch dịch vụ
        </a>
        <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
        <span class="font-bold text-gray-700">Xác nhận hoàn thành ca #<?php echo str_pad($data['appointment']->id, 5, '0', STR_PAD_LEFT); ?></span>
    </div>

    <?php flash('service_success'); ?>
    <?php flash('admin_error'); ?>

    <!-- Info card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-stethoscope text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-indigo-200 font-bold uppercase tracking-widest">Ca khám</p>
                <h1 class="text-lg font-black">#<?php echo str_pad($data['appointment']->id, 5, '0', STR_PAD_LEFT); ?> — <?php echo htmlspecialchars($data['appointment']->service_name); ?></h1>
            </div>
        </div>
        <div class="p-6 grid grid-cols-2 gap-4">
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
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Thời gian</p>
                <p class="text-sm font-bold text-indigo-600"><?php echo date('H:i', strtotime($data['appointment']->appointment_time)); ?></p>
                <p class="text-xs text-gray-500"><?php echo date('d/m/Y', strtotime($data['appointment']->appointment_date)); ?></p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Giá dịch vụ</p>
                <p class="text-sm font-bold text-gray-800"><?php echo number_format($data['appointment']->service_price ?? 0, 0, ',', '.'); ?>đ</p>
                <p class="text-xs text-gray-400 italic">(Giá niêm yết — có thể điều chỉnh)</p>
            </div>
        </div>
    </div>

    <!-- Completion form -->
    <form action="<?php echo URLROOT; ?>/admin/appointment_set_price" method="POST" class="space-y-6">
        <input type="hidden" name="appointment_id" value="<?php echo $data['appointment']->id; ?>">

        <!-- Giá thanh toán -->
        <div class="bg-white rounded-2xl border border-orange-100 p-5 shadow-sm">
            <label class="block text-xs font-black text-orange-500 uppercase tracking-widest mb-3">
                <i class="fa-solid fa-tag mr-1"></i> Thành tiền (Báo giá)
            </label>
            <div class="flex items-center gap-3">
                <input type="number"
                       name="final_price"
                       step="any"
                       min="0"
                       value="<?php echo floatval($data['appointment']->service_price ?? 0); ?>"
                       required
                       class="flex-1 px-4 py-3 rounded-xl border border-orange-200 text-base font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-300 bg-orange-50/30"
                       placeholder="0">
                <span class="text-lg font-black text-orange-400">đ</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-2 italic">Nhập 0 nếu miễn phí. Thu ngân sẽ thực hiện thu tiền theo số này.</p>
        </div>        <?php 
        $appointment = $data['appointment'];
        $categoryNameLower = !empty($appointment->category_name) ? mb_strtolower($appointment->category_name, 'UTF-8') : '';
        $serviceNameLower = mb_strtolower($appointment->service_name, 'UTF-8');

        $isVaccine = (
            strpos($categoryNameLower, 'tiêm') !== false ||
            strpos($categoryNameLower, 'vắc') !== false ||
            strpos($categoryNameLower, 'vaccin') !== false ||
            strpos($serviceNameLower, 'tiêm') !== false ||
            strpos($serviceNameLower, 'vắc xin') !== false ||
            strpos($serviceNameLower, 'vaccin') !== false ||
            strpos($serviceNameLower, 'chủng') !== false ||
            strpos($serviceNameLower, 'tẩy giun') !== false
        );
        ?>

        <?php if ($isVaccine): ?>
        <!-- Phiếu tiêm phòng & xét nghiệm -->
        <div class="bg-white rounded-2xl border border-emerald-100 p-5 shadow-sm space-y-5">
            <p class="text-xs font-black text-emerald-600 uppercase tracking-widest flex items-center gap-2 border-b border-emerald-50 pb-3">
                <i class="fa-solid fa-syringe"></i> Hồ sơ Tiêm phòng & Khám Sàng lọc
                <?php if (!empty($data['appointment']->pet_name)): ?>
                <span class="text-indigo-500 font-bold normal-case text-[10px] ml-auto">
                    <i class="fa-solid fa-paw"></i> <?php echo htmlspecialchars($data['appointment']->pet_name); ?>
                </span>
                <?php endif; ?>
            </p>

            <?php if (!empty($data['appointment']->selected_test)): ?>
            <div class="bg-indigo-50/70 border border-indigo-100 p-4 rounded-xl">
                <span class="text-[10px] font-black uppercase text-indigo-700 tracking-wider mb-1 block">Yêu cầu xét nghiệm từ khách hàng:</span>
                <p class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($data['appointment']->selected_test); ?></p>
            </div>
            <?php endif; ?>

            <!-- 1. Sinh hiệu & Sàng lọc -->
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-heart-pulse text-red-400"></i> Khám lâm sàng & Sàng lọc
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Cân nặng (kg)</label>
                        <input type="number" step="0.1" name="weight" placeholder="Ví dụ: 3.5"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Thân nhiệt (°C)</label>
                        <input type="number" step="0.1" name="temperature" placeholder="Ví dụ: 38.5"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Kết quả lâm sàng / Xét nghiệm <span class="text-red-500">*</span></label>
                    <textarea name="test_result" rows="2" required
                              class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                              placeholder="Ghi nhận tình trạng sức khỏe bé trước khi tiêm..."></textarea>
                </div>
            </div>

            <!-- 2. Thông tin Vắc-xin -->
            <div class="space-y-3 pt-3 border-t border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-vial text-emerald-500"></i> Thông tin Vắc-xin
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 mb-1">Tên Vắc-xin / Mũi tiêm (Chọn từ kho) <span class="text-red-500">*</span></label>
                        <select name="product_id" id="vaccine_select" onchange="handleVaccineSelect()"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm font-bold text-emerald-700 bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 mb-2">
                            <option value="">-- Chọn Vắc-xin (Tự động trừ tồn kho) --</option>
                        </select>
                        <input type="text" name="vaccine_name" id="vaccine_name" required
                               value="<?php echo htmlspecialchars($data['appointment']->service_name); ?>"
                               placeholder="Tên vắc-xin thực tế (Tự động điền theo lựa chọn ở trên)"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Số Lô (Batch No.)</label>
                        <input type="text" name="batch_number" id="batch_number" placeholder="Nhập số lô trên nhãn vắc-xin"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Ngày tiêm <span class="text-red-500">*</span></label>
                        <input type="date" name="vaccinated_date" value="<?php echo date('Y-m-d'); ?>" required
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <!-- 3. Theo dõi & Tái chủng -->
            <div class="space-y-3 pt-3 border-t border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-plus text-indigo-400"></i> Theo dõi sau tiêm & Lịch hẹn
                </h3>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Dặn dò / Theo dõi phản ứng sau tiêm</label>
                    <textarea name="reaction_notes" rows="2"
                              class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                              placeholder="VD: Kiêng tắm 1 tuần, có thể sốt nhẹ..."></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Hẹn tái chủng (Lịch nhắc tiêm tiếp theo)</label>
                        <input type="date" name="next_due_date"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-indigo-50/30">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Ghi chú thêm</label>
                        <input type="text" name="notes" placeholder="Các ghi chú khác..."
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Bệnh án & Kê đơn (cho dịch vụ khám chữa bệnh thông thường) -->
        <div class="bg-white rounded-2xl border border-indigo-100 p-5 shadow-sm space-y-4">
            <p class="text-xs font-black text-primary uppercase tracking-widest flex items-center gap-2">
                <i class="fa-solid fa-file-medical"></i> Ghi nhận bệnh án
                <?php if (!empty($data['appointment']->pet_name)): ?>
                <span class="text-green-500 font-bold normal-case text-[10px]">
                    <i class="fa-solid fa-paw"></i> <?php echo htmlspecialchars($data['appointment']->pet_name); ?>
                </span>
                <?php endif; ?>
            </p>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Chẩn đoán lâm sàng</label>
                <textarea name="diagnosis" rows="3"
                          class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                          placeholder="Mô tả triệu chứng, chẩn đoán bệnh..."></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Chỉ định điều trị &amp; Đơn thuốc</label>
                <textarea name="treatment" rows="3"
                          class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                          placeholder="Tên thuốc, liều lượng, số ngày dùng..."></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Ghi chú &amp; Hẹn tái khám</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                          placeholder="Ví dụ: Tái khám sau 3 ngày..."></textarea>
            </div>

            <!-- Kê đơn thuốc từ kho -->
            <div class="pt-4 border-t border-indigo-100">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-xs font-black text-indigo-700 uppercase tracking-wider">
                        <i class="fa-solid fa-pills mr-1"></i> Kê đơn thuốc từ kho hàng
                    </label>
                    <button type="button" id="btn-add-rx"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-lg text-xs font-bold transition inline-flex items-center gap-1">
                        <i class="fa-solid fa-plus text-[9px]"></i> Thêm thuốc
                    </button>
                </div>
                <div id="rx-list" class="space-y-2"></div>
                <p id="rx-empty" class="text-xs text-indigo-400 italic text-center py-3">Chưa kê đơn thuốc nào từ kho.</p>
            </div>
        </div>
        <?php endif; ?>
        <!-- Submit buttons -->
        <div class="flex gap-3">
            <a href="<?php echo URLROOT; ?>/admin/services"
               class="flex-none px-5 py-3 rounded-xl text-sm font-bold text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại
            </a>
            <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl text-sm font-black transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/25">
                <i class="fa-solid fa-circle-check"></i>
                Xác nhận Hoàn thành &amp; Lưu bệnh án
            </button>
        </div>
    </form>
</div>

<!-- Danh sách sản phẩm để kê đơn -->
<script>
const products = <?php echo json_encode(array_map(function($p) {
    $expiry_text = '';
    $is_expired = false;
    if (!empty($p->expiry_date)) {
        $expiry_time = strtotime($p->expiry_date);
        $now = time();
        $diff_days = ($expiry_time - $now) / 86400;
        if ($expiry_time < $now) { $is_expired = true; $expiry_text = ' (HẾT HẠN)'; }
        elseif ($diff_days <= 30) { $expiry_text = ' (SẮP HẾT HẠN - ' . date('d/m/Y', $expiry_time) . ')'; }
        else { $expiry_text = ' (HSD: ' . date('d/m/Y', $expiry_time) . ')'; }
    }
    return [
        'id'       => (int)$p->id,
        'name'     => $p->name . $expiry_text,
        'raw_name' => $p->name,
        'price'    => (float)$p->price,
        'stock'    => (int)$p->stock_quantity,
        'batch'    => $p->batch_number ?? '',
        'disabled' => ($p->stock_quantity <= 0 || $is_expired)
    ];
}, $data['products'] ?? [])); ?>;

// Populate Vaccine Select if exists
const vaccineSelect = document.getElementById('vaccine_select');
if (vaccineSelect) {
    products.forEach(p => {
        const option = document.createElement('option');
        option.value = p.id;
        option.disabled = p.disabled;
        option.textContent = p.name + ` (Tồn: ${p.stock})`;
        vaccineSelect.appendChild(option);
    });
}

function handleVaccineSelect() {
    const selectedId = parseInt(vaccineSelect.value);
    if (!selectedId) return;
    const prod = products.find(p => p.id === selectedId);
    if (prod) {
        document.getElementById('vaccine_name').value = prod.raw_name;
        if (prod.batch) {
            document.getElementById('batch_number').value = prod.batch;
        }
    }
}

let rxCount = 0;

function buildOptions() {
    return products.map(p =>
        `<option value="${p.id}" ${p.disabled ? 'disabled' : ''}>
            ${p.name} (Giá: ${p.price.toLocaleString('vi-VN')}đ — Tồn: ${p.stock})
        </option>`
    ).join('');
}

document.getElementById('btn-add-rx').addEventListener('click', function () {
    rxCount++;
    document.getElementById('rx-empty').style.display = 'none';
    const row = document.createElement('div');
    row.className = 'flex gap-2 items-start bg-indigo-50/60 p-2.5 rounded-xl border border-indigo-100';
    row.id = 'rx-row-' + rxCount;
    row.innerHTML = `
        <div class="flex-1">
            <select name="prescription_products[]" required
                    class="w-full px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs bg-white text-gray-800 font-medium focus:outline-none focus:ring-1 focus:ring-primary">
                <option value="">-- Chọn thuốc/sản phẩm --</option>
                ${buildOptions()}
            </select>
        </div>
        <div class="w-16">
            <input type="number" name="prescription_quantities[]" min="1" value="1" required
                   class="w-full px-2 py-1.5 rounded-lg border border-gray-200 text-xs text-center bg-white font-bold focus:outline-none focus:ring-1 focus:ring-primary">
        </div>
        <div class="flex-1">
            <input type="text" name="prescription_instructions[]" required
                   placeholder="Cách dùng: ngày 2 lần..."
                   class="w-full px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs bg-white font-medium focus:outline-none focus:ring-1 focus:ring-primary">
        </div>
        <button type="button" onclick="removeRx('rx-row-${rxCount}')"
                class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-400 rounded-lg flex items-center justify-center border border-red-100 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    document.getElementById('rx-list').appendChild(row);
});

function removeRx(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
    if (document.getElementById('rx-list').children.length === 0) {
        document.getElementById('rx-empty').style.display = '';
    }
}
</script>

<?php require APPROOT . '/views/admin/footer.php'; ?>
