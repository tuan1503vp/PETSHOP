<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="bg-gradient-to-b from-indigo-50 to-white min-h-[calc(100vh-64px)]">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <div class="mb-8">
            <a href="<?php echo URLROOT; ?>/service" class="inline-flex items-center text-sm text-gray-500 hover:text-primary transition font-medium">
                <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại danh sách dịch vụ
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            <!-- Left: Info Panel -->
            <div class="lg:col-span-2">
                <div class="bg-gradient-to-br from-primary to-indigo-700 rounded-3xl p-8 text-white sticky top-8">
                    <div class="h-16 w-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center mb-6">
                        <i class="fa-regular fa-calendar-check text-3xl"></i>
                    </div>
                    <h1 class="text-2xl font-black">Đặt Lịch Dịch Vụ</h1>
                    <p class="mt-3 text-indigo-200 text-sm leading-relaxed">Điền thông tin bên dưới để đặt lịch. Chúng tôi sẽ tự động phân công chuyên viên phù hợp nhất cho bạn.</p>
                    
                    <div class="mt-8 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-black">1</div>
                            <span class="text-sm font-medium text-indigo-100">Chọn dịch vụ & mô tả thú cưng</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-black">2</div>
                            <span class="text-sm font-medium text-indigo-100">Chọn ngày giờ phù hợp</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-black">3</div>
                            <span class="text-sm font-medium text-indigo-100">Xác nhận & chờ liên hệ</span>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/20">
                        <div class="flex items-center gap-2 text-indigo-200 text-xs">
                            <i class="fa-solid fa-shield-check"></i>
                            <span>Thông tin của bạn được bảo mật</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Form -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <form action="<?php echo URLROOT; ?>/service/book" method="POST" class="p-8 space-y-7">

                        <!-- Flash errors -->
                        <?php if(!empty($data['date_err']) || !empty($data['time_err'])): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm flex items-start gap-3">
                            <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-400"></i>
                            <div>
                                <?php if(!empty($data['date_err'])): ?>
                                    <p><?php echo $data['date_err']; ?></p>
                                <?php endif; ?>
                                <?php if(!empty($data['time_err'])): ?>
                                    <p><?php echo $data['time_err']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Dịch vụ -->
                        <div>
                            <label for="service_id" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                                <i class="fa-solid fa-concierge-bell mr-1 text-primary"></i> Tên Dịch Vụ <span class="text-red-500">*</span>
                            </label>
                            <select id="service_id" name="service_id" required onchange="toggleDurationFields()"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition appearance-none">
                                <option value="" disabled <?php echo !isset($data['selected_service']) ? 'selected' : ''; ?>>-- Chọn dịch vụ --</option>
                                <?php foreach($data['services'] as $service): ?>
                                    <option value="<?php echo $service->id; ?>" 
                                            data-category="<?php echo $service->category_name; ?>"
                                            <?php echo (isset($data['selected_service']) && $data['selected_service'] == $service->id) ? 'selected' : ''; ?>>
                                        <?php echo $service->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Duration Fields (Hidden by default, shown for Boarding) -->
                        <div id="duration_container" class="hidden space-y-4 bg-blue-50/50 p-6 rounded-2xl border border-blue-100">
                            <h4 class="text-xs font-black text-blue-500 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left"></i> Thời Gian Trông Giữ
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="duration_value" class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Thời gian</label>
                                    <input type="number" id="duration_value" name="duration_value" min="1" value="1"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-800 focus:ring-2 focus:ring-primary/20 outline-none transition">
                                </div>
                                <div>
                                    <label for="duration_unit" class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Đơn vị</label>
                                    <select id="duration_unit" name="duration_unit"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-medium text-gray-800 focus:ring-2 focus:ring-primary/20 outline-none transition appearance-none">
                                        <!-- Options will be populated by JS -->
                                    </select>
                                </div>
                            </div>
                            <p class="text-[11px] text-blue-600/70 italic" id="duration_hint">Hệ thống sẽ tính phí dựa trên thời gian thực tế khi bạn đón thú cưng.</p>
                        </div>

                        <script>
                            function toggleDurationFields() {
                                const select = document.getElementById('service_id');
                                const container = document.getElementById('duration_container');
                                if (!select || select.selectedIndex < 0) return;
                                
                                const selectedOption = select.options[select.selectedIndex];
                                if (!selectedOption || selectedOption.value === "") {
                                    container.classList.add('hidden');
                                    return;
                                }

                                const category = selectedOption.getAttribute('data-category') || '';
                                const serviceName = selectedOption.text.toLowerCase();
                                
                                if (category.toLowerCase().includes('trông giữ')) {
                                    container.classList.remove('hidden');
                                    const unitSelect = document.getElementById('duration_unit');
                                    const hint = document.getElementById('duration_hint');
                                    
                                    // Reset options
                                    unitSelect.innerHTML = '';
                                    
                                    if (serviceName.includes('ngắn hạn')) {
                                        const opt = document.createElement('option');
                                        opt.value = 'hour';
                                        opt.text = 'Tiếng (Giờ)';
                                        unitSelect.add(opt);
                                        unitSelect.value = 'hour';
                                        hint.innerText = "Giá niêm yết: 20.000đ / tiếng.";
                                    } else if (serviceName.includes('dài hạn')) {
                                        const opt1 = document.createElement('option');
                                        opt1.value = 'day';
                                        opt1.text = 'Ngày';
                                        unitSelect.add(opt1);
                                        
                                        const opt2 = document.createElement('option');
                                        opt2.value = 'month';
                                        opt2.text = 'Tháng (29 ngày)';
                                        unitSelect.add(opt2);
                                        
                                        unitSelect.value = 'day';
                                        hint.innerText = "Giá niêm yết: 50.000đ / ngày. 1 tháng tính là 29 ngày.";
                                    } else {
                                        const opt = document.createElement('option');
                                        opt.value = 'day';
                                        opt.text = 'Ngày';
                                        unitSelect.add(opt);
                                    }
                                } else {
                                    container.classList.add('hidden');
                                }
                            }
                            // Run on load
                            document.addEventListener('DOMContentLoaded', toggleDurationFields);
                        </script>

                        <!-- Thú cưng (text input) -->
                        <div>
                            <label for="pet_info" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                                <i class="fa-solid fa-paw mr-1 text-pink-400"></i> Thú Cưng Của Bạn
                            </label>
                            <input type="text" id="pet_info" name="pet_info" 
                                   value="<?php echo $data['pet_info'] ?? ''; ?>"
                                   placeholder="VD: Chó Poodle, 3 tuổi, 5kg / Mèo Ba Tư trắng..."
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition placeholder:text-gray-300">
                            <p class="mt-1.5 text-xs text-gray-400">Mô tả loài, giống, tuổi, cân nặng để chúng tôi chuẩn bị tốt hơn</p>
                        </div>

                        <!-- Ngày & Giờ -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="appointment_date" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                                    <i class="fa-solid fa-calendar-day mr-1 text-blue-400"></i> Ngày Hẹn <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="appointment_date" name="appointment_date" required
                                       value="<?php echo $data['appointment_date']; ?>" 
                                       min="<?php echo date('Y-m-d'); ?>"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition <?php echo !empty($data['date_err']) ? 'border-red-400 bg-red-50/30' : ''; ?>">
                            </div>
                            <div>
                                <label for="appointment_time" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                                    <i class="fa-solid fa-clock mr-1 text-green-400"></i> Giờ Hẹn <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="appointment_time" name="appointment_time" required
                                       value="<?php echo $data['appointment_time']; ?>"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition <?php echo !empty($data['time_err']) ? 'border-red-400 bg-red-50/30' : ''; ?>">
                                <p id="time_hint" class="mt-1.5 text-[10px] text-gray-400 font-bold leading-normal">
                                    <i class="fa-solid fa-circle-question"></i> Lưu ý: Nếu hẹn hôm nay, vui lòng chọn giờ cách thời điểm hiện tại ít nhất 30 phút.
                                </p>
                                <p id="time_error_msg" class="mt-1.5 text-[11px] text-red-500 font-bold hidden flex items-center gap-1.5 leading-normal">
                                    <i class="fa-solid fa-triangle-exclamation"></i> <span>Thời gian hẹn không hợp lệ</span>
                                </p>
                            </div>
                        </div>

                        <!-- Giờ hoạt động hint -->
                        <div class="bg-indigo-50/50 rounded-xl p-4 flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-primary mt-0.5"></i>
                            <div class="text-xs text-gray-600 leading-relaxed">
                                <strong class="text-gray-800">Giờ hoạt động:</strong> Thứ 2 – Thứ 7, từ 8:00 – 18:00. Chủ nhật từ 8:00 – 12:00.
                                <br>Chúng tôi sẽ liên hệ xác nhận nếu khung giờ bạn chọn nằm ngoài lịch trình.
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div>
                            <label for="notes" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                                <i class="fa-solid fa-pen-to-square mr-1 text-orange-400"></i> Ghi Chú Thêm
                            </label>
                            <textarea id="notes" name="notes" rows="3" 
                                      placeholder="Triệu chứng, yêu cầu đặc biệt, dị ứng..."
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition resize-none placeholder:text-gray-300"><?php echo $data['notes']; ?></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <a href="<?php echo URLROOT; ?>/service" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition flex items-center gap-1">
                                <i class="fa-solid fa-xmark"></i> Hủy bỏ
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-8 py-3 bg-primary text-white text-sm font-black rounded-2xl shadow-lg shadow-primary/25 hover:bg-indigo-700 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                <i class="fa-solid fa-paper-plane"></i>
                                Xác Nhận Đặt Lịch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('appointment_date');
    const timeInput = document.getElementById('appointment_time');
    const timeError = document.getElementById('time_error_msg');
    const timeHint = document.getElementById('time_hint');
    const form = dateInput.closest('form');

    function getTodayString() {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    }

    function getMinTimeStr() {
        const today = new Date();
        const minTimeDate = new Date(today.getTime() + 30 * 60 * 1000);
        const minHours = String(minTimeDate.getHours()).padStart(2, '0');
        const minMinutes = String(minTimeDate.getMinutes()).padStart(2, '0');
        return `${minHours}:${minMinutes}`;
    }

    function validateTime(autoFix = false) {
        const todayStr = getTodayString();
        
        if (dateInput.value === todayStr) {
            const minTimeStr = getMinTimeStr();
            timeInput.min = minTimeStr;

            if (autoFix) {
                if (!timeInput.value || timeInput.value < minTimeStr) {
                    timeInput.value = minTimeStr;
                }
            }

            if (timeInput.value && timeInput.value < minTimeStr) {
                // Hiển thị thông báo lỗi tức thì
                timeError.querySelector('span').textContent = `Giờ hẹn không hợp lệ (phải từ ${minTimeStr} trở đi).`;
                timeError.classList.remove('hidden');
                timeHint.classList.add('hidden');
                timeInput.classList.add('border-red-400', 'bg-red-50/30');
                return false;
            }
        } else {
            timeInput.removeAttribute('min');
        }
        
        // Nếu hợp lệ
        timeError.classList.add('hidden');
        timeHint.classList.remove('hidden');
        timeInput.classList.remove('border-red-400', 'bg-red-50/30');
        return true;
    }

    // Lắng nghe sự kiện thay đổi ngày/giờ tức thì
    dateInput.addEventListener('change', function() {
        validateTime(true);
    });
    timeInput.addEventListener('change', function() {
        validateTime(false);
    });
    timeInput.addEventListener('input', function() {
        validateTime(false);
    });
    timeInput.addEventListener('focus', function() {
        validateTime(true);
    });
    timeInput.addEventListener('click', function() {
        validateTime(true);
    });

    // Chạy kiểm tra ngay khi tải trang (không tự động sửa ngay để tránh điền sẵn khi chưa cần thiết, chỉ kiểm tra)
    validateTime(false);

    // Định kỳ cập nhật lại minTime sau mỗi 30 giây nếu chọn ngày hôm nay
    setInterval(function() {
        if (dateInput.value === getTodayString()) {
            validateTime(false);
        }
    }, 30000);

    // Kiểm tra khi submit
    form.addEventListener('submit', function(e) {
        if (!validateTime(true)) {
            e.preventDefault();
            const minTimeStr = getMinTimeStr();
            alert(`Giờ hẹn không hợp lệ. Vui lòng chọn giờ hẹn từ ${minTimeStr} trở đi.`);
            timeInput.value = minTimeStr;
            validateTime(true);
            timeInput.focus();
        }
    });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
