<?php require APPROOT . '/views/inc/header.php'; $pet = $data['pet']; ?>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<style>
.chat-bubble-content p {
    margin-bottom: 0.5rem;
}
.chat-bubble-content p:last-child {
    margin-bottom: 0;
}
.chat-bubble-content ul, .chat-bubble-content ol {
    margin-bottom: 0.5rem;
    padding-left: 1.25rem;
    list-style-type: disc;
}
.chat-bubble-content ol {
    list-style-type: decimal;
}
.chat-bubble-content li {
    margin-bottom: 0.25rem;
}
.chat-bubble-content strong {
    font-weight: 800;
    color: #111827;
}
</style>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8" x-data="{ 
    activeTab: '<?php echo isset($_GET['tab']) ? $_GET['tab'] : 'clinic_records'; ?>', 
    showAddVaccineModal: false, 
    petWeight: <?php echo !empty($pet->weight) ? floatval($pet->weight) : 0; ?>, 
    lifeStage: 'adult_neutered', 
    rer: 0, 
    der: 0, 
    foodRecommend: 0, 
    pateRecommend: 0, 
    aiMessages: [], 
    aiInput: '', 
    aiLoading: false,
    hasAnalyzed: false,
    cacheKey: 'pet_<?php echo $pet->id; ?>_age_<?php echo $pet->age; ?>_wt_<?php echo !empty($pet->weight) ? floatval($pet->weight) : 0; ?>_logs_<?php echo count($data['records'] ?? []); ?>',

    calculateNutrition() { 
        if (this.petWeight <= 0) return; 
        this.rer = Math.round(70 * Math.pow(this.petWeight, 0.75)); 
        let multiplier = 1.6; 
        if (this.lifeStage === 'puppy_kitten') multiplier = 3.0; 
        else if (this.lifeStage === 'adult_intact') multiplier = 1.8; 
        else if (this.lifeStage === 'adult_neutered') multiplier = 1.6; 
        else if (this.lifeStage === 'obese_weight_loss') multiplier = 1.2; 
        this.der = Math.round(this.rer * multiplier); 
        this.foodRecommend = Math.round(this.der * 0.7 / 3.5); 
        this.pateRecommend = Math.round(this.der * 0.3 / 0.9); 
    },

    checkNutritionCache() {
        const savedHistory = localStorage.getItem('pawsy_chat_history_pet_<?php echo $pet->id; ?>');
        const savedKey = localStorage.getItem('pawsy_nutrition_cache_key');
        
        // Nếu có lịch sử chat được lưu trữ và thông số pet chưa đổi -> khôi phục lại toàn bộ cuộc hội thoại cũ
        if (savedHistory && savedKey === this.cacheKey) {
            try {
                this.aiMessages = JSON.parse(savedHistory);
                this.hasAnalyzed = true;
                return;
            } catch (e) {
                console.error('Error parsing chat history:', e);
            }
        }
        
        // Trường hợp không có lịch sử nhưng có kết quả phân tích cũ hợp lệ
        const savedCache = localStorage.getItem('pawsy_nutrition_cache_data');
        if (savedCache && savedKey === this.cacheKey) {
            this.aiMessages = [
                { sender: 'ai', text: 'Chào Sen! Pawsy đã tìm thấy kết quả phân tích dinh dưỡng gần nhất của bé **<?php echo htmlspecialchars($pet->name); ?>**. Do các thông số sức khỏe của bé không đổi nên Pawsy xin hiển thị lại ngay nhé!' },
                { sender: 'ai', text: savedCache }
            ];
            this.hasAnalyzed = true;
            // Lưu ngay vào lịch sử chat
            localStorage.setItem('pawsy_chat_history_pet_<?php echo $pet->id; ?>', JSON.stringify(this.aiMessages));
        } else {
            // Hiển thị màn hình chào yêu cầu bấm phân tích
            this.aiMessages = [{ 
                sender: 'ai', 
                text: 'Xin chào! Mình là Pawsy, trợ lý AI chăm sóc sức khỏe của bé **<?php echo htmlspecialchars($pet->name); ?>**. \n\nHãy nhấn nút **🚀 Bắt đầu Phân tích Dinh dưỡng** bên dưới để mình phân tích thể trạng và đưa ra thực đơn khuyến nghị tốt nhất cho bé trước khi chúng ta trò chuyện nhé!' 
            }];
            this.hasAnalyzed = false;
            // Xóa lịch sử cũ vì thông số pet đã thay đổi (cần phân tích lại)
            localStorage.removeItem('pawsy_chat_history_pet_<?php echo $pet->id; ?>');
        }
    },

    async startNutritionAnalysis() {
        this.aiLoading = true;
        this.aiMessages.push({ sender: 'ai', text: '*(Hệ thống đang thu thập thông tin cân nặng, độ tuổi và nhật ký sức khỏe để phân tích y khoa...)*' });
        
        try {
            const res = await fetch('<?php echo URLROOT; ?>/ai/pet_chat', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' }, 
                body: JSON.stringify({ 
                    pet_id: <?php echo $pet->id; ?>, 
                    message: 'Hãy phân tích thể trạng, nhu cầu dinh dưỡng và gợi ý chế độ ăn uống tốt nhất dựa theo thông số của bé cưng.',
                    history: []
                }) 
            });
            const data = await res.json();
            if (data.success) {
                // Xóa tin nhắn loading tạm thời
                this.aiMessages = this.aiMessages.filter(function(m) { return !m.text.includes('Hệ thống đang thu thập'); });
                
                // Lưu kết quả phân tích vào cache
                localStorage.setItem('pawsy_nutrition_cache_data', data.reply);
                localStorage.setItem('pawsy_nutrition_cache_key', this.cacheKey);
                
                this.aiMessages.push({ sender: 'ai', text: data.reply });
                this.hasAnalyzed = true;
                
                // Lưu lịch sử chat
                localStorage.setItem('pawsy_chat_history_pet_<?php echo $pet->id; ?>', JSON.stringify(this.aiMessages));
            } else {
                this.aiMessages.push({ sender: 'ai', text: 'Có lỗi xảy ra trong quá trình phân tích: ' + data.message });
            }
        } catch (e) {
            this.aiMessages.push({ sender: 'ai', text: 'Không thể kết nối với Pawsy. Vui lòng kiểm tra lại kết nối mạng.' });
        } finally {
            this.aiLoading = false;
            this.$nextTick(function() {
                const container = document.getElementById('chatMessages');
                if (container) container.scrollTop = container.scrollHeight;
            });
        }
    },

    async sendAiMessage() { 
        if (!this.aiInput.trim()) return; 
        const userMsg = this.aiInput; 
        this.aiMessages.push({ sender: 'user', text: userMsg }); 
        this.aiInput = ''; 
        this.aiLoading = true; 
        
        // Lưu lịch sử chat tạm thời (chứa tin nhắn user)
        localStorage.setItem('pawsy_chat_history_pet_<?php echo $pet->id; ?>', JSON.stringify(this.aiMessages));
        
        this.$nextTick(function() { 
            const container = document.getElementById('chatMessages'); 
            if (container) container.scrollTop = container.scrollHeight; 
        });

        try { 
            const res = await fetch('<?php echo URLROOT; ?>/ai/pet_chat', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' }, 
                body: JSON.stringify({ 
                    pet_id: <?php echo $pet->id; ?>, 
                    message: userMsg,
                    history: this.aiMessages 
                }) 
            }); 
            const data = await res.json(); 
            if (data.success) { 
                this.aiMessages.push({ sender: 'ai', text: data.reply }); 
                // Lưu lịch sử chat hoàn chỉnh (có tin nhắn AI phản hồi)
                localStorage.setItem('pawsy_chat_history_pet_<?php echo $pet->id; ?>', JSON.stringify(this.aiMessages));
            } else { 
                this.aiMessages.push({ sender: 'ai', text: 'Có lỗi xảy ra: ' + data.message }); 
            } 
        } catch (e) { 
            this.aiMessages.push({ sender: 'ai', text: 'Không thể kết nối với Pawsy. Vui lòng kiểm tra lại kết nối mạng.' }); 
        } finally { 
            this.aiLoading = false; 
            this.$nextTick(function() { 
                const container = document.getElementById('chatMessages'); 
                if (container) container.scrollTop = container.scrollHeight; 
            }); 
        } 
    }, 
    
    init() { 
        this.calculateNutrition(); 
        this.checkNutritionCache();
    } 
}">
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
    <div class="border-b border-gray-200 mb-8 overflow-x-auto">
        <nav class="flex space-x-8 min-w-max" aria-label="Tabs">

            <button @click="activeTab = 'clinic_records'; window.history.replaceState(null, null, '?tab=clinic_records')" 
                    :class="activeTab === 'clinic_records' ? 'border-primary text-primary border-b-2 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 text-sm font-semibold border-b-2 transition duration-300 focus:outline-none">
                <i class="fa-solid fa-stethoscope mr-2"></i> Lịch sử khám bệnh (PETSHOP)
            </button>
            <button @click="activeTab = 'vaccinations'; window.history.replaceState(null, null, '?tab=vaccinations')" 
                    :class="activeTab === 'vaccinations' ? 'border-primary text-primary border-b-2 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 text-sm font-semibold border-b-2 transition duration-300 focus:outline-none">
                <i class="fa-solid fa-syringe mr-2"></i> Lịch tiêm phòng
            </button>
            <button @click="activeTab = 'nutrition_ai'; window.history.replaceState(null, null, '?tab=nutrition_ai')" 
                    :class="activeTab === 'nutrition_ai' ? 'border-primary text-primary border-b-2 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 text-sm font-semibold border-b-2 transition duration-300 focus:outline-none">
                <i class="fa-solid fa-bowl-food mr-2"></i> Dinh dưỡng & Trợ lý AI
            </button>
        </nav>
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
                                
                                <?php if (!empty($record->prescriptions)): ?>
                                    <div class="mt-4 pt-3 border-t border-emerald-100/40 space-y-2">
                                        <p class="text-[10px] font-black uppercase text-emerald-600 tracking-widest flex items-center gap-1">
                                            <i class="fa-solid fa-receipt"></i> Chi tiết thuốc kê đơn:
                                        </p>
                                        <div class="space-y-2">
                                            <?php foreach ($record->prescriptions as $pres): ?>
                                                <div class="bg-white/60 p-2.5 rounded-xl border border-emerald-100/30 text-xs">
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

    <!-- TAB 3: Vaccinations -->
    <div x-show="activeTab === 'vaccinations'" x-transition class="space-y-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Sổ tiêm chủng & phòng bệnh</h3>
                <p class="text-xs text-gray-500 mt-0.5">Theo dõi lịch sử tiêm vắc xin và các mũi điều trị phòng ngừa định kỳ.</p>
            </div>
            <button onclick="document.getElementById('ai-vaccine-modal').classList.remove('hidden'); fetchAiVaccineSchedule();" class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-4 py-2 rounded-xl text-xs font-bold hover:shadow-lg hover:shadow-emerald-500/30 transition-all flex items-center gap-2 group">
                <i class="fa-solid fa-wand-magic-sparkles group-hover:rotate-12 transition-transform"></i> Phác đồ AI
            </button>
        </div>

        <?php flash('vaccination_message'); ?>

        <?php if (empty($data['vaccinations'])): ?>
            <div class="bg-white rounded-[2rem] border border-gray-100 p-12 text-center text-gray-500 shadow-sm">
                <i class="fa-solid fa-syringe text-gray-300 text-4xl mb-4 block"></i>
                <p class="text-sm font-medium">Bé chưa có lịch sử tiêm chủng nào được ghi nhận.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($data['vaccinations'] as $vac): ?>
                    <div class="bg-white rounded-[2rem] border border-emerald-100 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-50 to-transparent rounded-bl-[4rem] z-0 group-hover:scale-110 transition-transform"></div>
                        
                        <div class="flex items-start justify-between mb-4 relative z-10">
                            <div>
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1 flex items-center gap-1">
                                    <i class="fa-solid fa-syringe"></i> Mũi tiêm / Vắc-xin
                                </p>
                                <h4 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($vac->vaccine_name); ?></h4>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Ngày tiêm</p>
                                <p class="text-sm font-bold text-gray-700"><?php echo date('d/m/Y', strtotime($vac->vaccinated_date)); ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4 relative z-10">
                            <div class="bg-gray-50/80 p-3 rounded-2xl border border-gray-100/50">
                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">Sinh hiệu</p>
                                <p class="text-xs font-bold text-gray-700">
                                    <i class="fa-solid fa-weight-scale text-orange-400 mr-1"></i><?php echo !empty($vac->weight) ? $vac->weight . 'kg' : '--'; ?> 
                                    <span class="text-gray-300 mx-1">|</span> 
                                    <i class="fa-solid fa-temperature-half text-red-400 mr-1"></i><?php echo !empty($vac->temperature) ? $vac->temperature . '°C' : '--'; ?>
                                </p>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-2xl border border-gray-100/50">
                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">Bác sĩ & Lô SX</p>
                                <p class="text-xs font-bold text-gray-700 truncate" title="<?php echo htmlspecialchars($vac->veterinarian_name ?? 'Bác sĩ'); ?>">
                                    <i class="fa-solid fa-user-doctor text-blue-400 mr-1"></i><?php echo htmlspecialchars($vac->veterinarian_name ?? 'Bác sĩ'); ?>
                                </p>
                                <?php if(!empty($vac->batch_number)): ?>
                                <p class="text-[10px] text-gray-500 truncate mt-0.5">Lô: <?php echo htmlspecialchars($vac->batch_number); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($vac->test_result) || !empty($vac->reaction_notes) || !empty($vac->notes)): ?>
                        <div class="space-y-2 mb-4 text-xs relative z-10">
                            <?php if (!empty($vac->test_result)): ?>
                            <div class="bg-indigo-50/50 p-3 rounded-xl border border-indigo-50">
                                <span class="font-bold text-indigo-700 block mb-0.5"><i class="fa-solid fa-microscope mr-1"></i> Khám Sàng lọc:</span>
                                <span class="text-gray-600 leading-relaxed"><?php echo nl2br(htmlspecialchars($vac->test_result)); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($vac->reaction_notes)): ?>
                            <div class="bg-emerald-50/50 p-3 rounded-xl border border-emerald-50">
                                <span class="font-bold text-emerald-700 block mb-0.5"><i class="fa-solid fa-clipboard-list mr-1"></i> Dặn dò sau tiêm:</span>
                                <span class="text-gray-600 leading-relaxed"><?php echo nl2br(htmlspecialchars($vac->reaction_notes)); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($vac->notes)): ?>
                            <div class="bg-gray-50/50 p-3 rounded-xl border border-gray-100/50">
                                <span class="font-bold text-gray-500 block mb-0.5"><i class="fa-solid fa-comment-dots mr-1"></i> Ghi chú khác:</span>
                                <span class="text-gray-600 leading-relaxed"><?php echo nl2br(htmlspecialchars($vac->notes)); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 relative z-10">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Lịch nhắc tiếp theo</p>
                                <?php if (!empty($vac->next_due_date)): ?>
                                    <?php 
                                        $nextDate = strtotime($vac->next_due_date);
                                        $today = strtotime(date('Y-m-d'));
                                        $dueClass = ($nextDate < $today) ? 'text-red-600 bg-red-50 border-red-100' : 'text-indigo-600 bg-indigo-50 border-indigo-100';
                                    ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black border <?php echo $dueClass; ?>">
                                        <i class="fa-regular fa-calendar-check"></i> <?php echo date('d/m/Y', $nextDate); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic font-medium px-1">Không có</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- TAB 4: Nutrition & AI Advisor -->
    <div x-show="activeTab === 'nutrition_ai'" x-transition class="space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Nutrition Calculator -->
            <div class="lg:col-span-5 bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
                <div>
                    <h4 class="text-lg font-black text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-calculator text-primary"></i> Nhu cầu dinh dưỡng
                    </h4>
                    <p class="text-xs text-gray-500 mt-1">Dựa trên cân nặng thực tế và thể trạng của bé cưng.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Cân nặng hiện tại (kg)</label>
                        <div class="relative rounded-xl shadow-sm">
                            <input type="number" step="0.1" min="0.1" max="150" x-model.number="petWeight" @input="calculateNutrition()"
                                   class="w-full pl-4 pr-12 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary font-bold text-gray-800">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-semibold text-xs">kg</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Trạng thái phát triển & Sinh lý</label>
                        <select x-model="lifeStage" @change="calculateNutrition()"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-800 font-semibold">
                            <option value="puppy_kitten">Thú non / Cún miu con đang lớn</option>
                            <option value="adult_intact">Trưởng thành (chưa triệt sản)</option>
                            <option value="adult_neutered">Trưởng thành (đã triệt sản)</option>
                            <option value="obese_weight_loss">Béo phì / Cần giảm cân</option>
                        </select>
                    </div>
                </div>

                <!-- Calculation Results -->
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 space-y-4">
                    <h5 class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Khuyến nghị năng lượng & Định lượng</h5>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-3 rounded-xl border border-slate-100">
                            <span class="text-[10px] text-gray-400 font-semibold block">Nhu cầu nghỉ ngơi (RER)</span>
                            <span class="text-base font-black text-gray-800" x-text="rer + ' kcal'"></span>
                        </div>
                        <div class="bg-indigo-50/50 p-3 rounded-xl border border-indigo-100/30">
                            <span class="text-[10px] text-indigo-500 font-semibold block">Năng lượng/ngày (DER)</span>
                            <span class="text-base font-black text-primary" x-text="der + ' kcal'"></span>
                        </div>
                    </div>

                    <div class="space-y-2.5 pt-2 border-t border-slate-200/50">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-medium"><i class="fa-solid fa-cookie-bite mr-1.5 text-amber-500"></i>Thức ăn hạt khô (70%):</span>
                            <span class="font-black text-gray-800" x-text="foodRecommend + ' gram / ngày'"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-medium"><i class="fa-solid fa-fish-fins mr-1.5 text-cyan-500"></i>Pate / Thức ăn ướt (30%):</span>
                            <span class="font-black text-gray-800" x-text="pateRecommend + ' gram / ngày'"></span>
                        </div>
                    </div>
                </div>

                <!-- Products Suggestion -->
                <div class="space-y-4">
                    <h5 class="text-xs font-black uppercase text-gray-800 tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-bag-shopping text-primary"></i> Sản phẩm phù hợp đề xuất
                    </h5>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <?php if(!empty($data['suggestedProducts'])): ?>
                            <?php foreach($data['suggestedProducts'] as $p): ?>
                                <a href="<?php echo URLROOT; ?>/product/show/<?php echo $p->id; ?>" 
                                   class="bg-white border border-gray-100 rounded-2xl p-3 flex flex-col justify-between hover:shadow-md transition cursor-pointer group">
                                    <div>
                                        <div class="w-full aspect-square rounded-xl overflow-hidden bg-slate-50 mb-2">
                                            <?php if(!empty($p->image)): ?>
                                                <img src="<?php echo URLROOT . '/public/images/' . $p->image; ?>" 
                                                     alt="<?php echo htmlspecialchars($p->name); ?>" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <i class="fa-solid fa-paw text-2xl"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <h6 class="text-xs font-bold text-gray-900 line-clamp-2 leading-tight group-hover:text-primary transition"><?php echo htmlspecialchars($p->name); ?></h6>
                                    </div>
                                    <div class="mt-2 pt-2 border-t border-slate-50 flex items-center justify-between">
                                        <span class="text-xs font-black text-primary"><?php echo number_format($p->price, 0, ',', '.'); ?>đ</span>
                                        <span class="text-[10px] font-black text-indigo-600 group-hover:underline">Chi tiết</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-xs text-gray-400 col-span-2 italic text-center">Chưa có sản phẩm phù hợp.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- AI Chatbot Advisor -->
            <div class="lg:col-span-7 bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col h-[600px]">
                <!-- Chat Header -->
                <div class="p-6 border-b border-gray-100 bg-slate-50/50 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-primary text-white flex items-center justify-center text-lg shadow-md shadow-primary/25">
                            <i class="fa-solid fa-robot animate-pulse"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-gray-900 text-sm">Trợ lý dinh dưỡng Pawsy</h4>
                            <p class="text-[10px] text-emerald-500 font-bold flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Đang trực tuyến 24/7
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages Area -->
                <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/30">
                    <template x-for="(msg, index) in aiMessages" :key="index">
                        <div :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'" class="flex items-start gap-3">
                            <!-- Bot Avatar -->
                            <div x-show="msg.sender === 'ai'" class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 text-primary flex items-center justify-center text-xs shrink-0 shadow-inner">
                                <i class="fa-solid fa-robot"></i>
                            </div>

                            <!-- Bubble -->
                            <div :class="msg.sender === 'user' 
                                 ? 'bg-primary text-white rounded-[1.25rem] rounded-tr-none' 
                                 : 'bg-white border border-gray-100 text-gray-800 rounded-[1.25rem] rounded-tl-none shadow-sm'"
                                 class="p-4 max-w-[85%] text-xs font-semibold leading-relaxed chat-bubble-content">
                                <div x-html="msg.sender === 'user' ? msg.text : marked.parse(msg.text)"></div>
                            </div>
                        </div>
                    </template>

                    <!-- Loading Bouncing Dots -->
                    <div x-show="aiLoading" class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 text-primary flex items-center justify-center text-xs shrink-0 shadow-inner">
                            <i class="fa-solid fa-robot"></i>
                        </div>
                        <div class="bg-white border border-gray-100 p-4 rounded-[1.25rem] rounded-tl-none shadow-sm flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-slate-400 animate-bounce"></span>
                            <span class="w-2 h-2 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 0.2s"></span>
                            <span class="w-2 h-2 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 0.4s"></span>
                        </div>
                    </div>

                    <!-- Nút Bắt đầu phân tích khi chưa phân tích xong -->
                    <div x-show="!hasAnalyzed && !aiLoading" class="flex justify-center py-4">
                        <button @click="startNutritionAnalysis()"
                                class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-primary to-secondary text-white rounded-2xl font-black text-xs hover:shadow-lg hover:shadow-primary/30 transition-all transform hover:-translate-y-0.5 active:scale-95 cursor-pointer">
                            <i class="fa-solid fa-wand-magic-sparkles animate-pulse"></i> 🚀 Bắt đầu Phân tích Dinh dưỡng
                        </button>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="p-4 border-t border-gray-100 bg-white shrink-0">
                    <form @submit.prevent="sendAiMessage()" class="flex gap-2">
                        <input type="text" x-model="aiInput" 
                               :placeholder="hasAnalyzed ? 'Nhập câu hỏi tiếp theo cho Pawsy...' : '⚠️ Vui lòng nhấn nút Phân tích dinh dưỡng phía trên trước...'" 
                               :disabled="aiLoading || !hasAnalyzed"
                               class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-xs font-semibold disabled:bg-slate-50 disabled:text-gray-400">
                        <button type="submit" :disabled="aiLoading || !aiInput.trim() || !hasAnalyzed"
                                class="px-4 py-3 bg-primary text-white rounded-xl font-bold hover:bg-indigo-700 transition flex items-center justify-center gap-1.5 shadow-md shadow-primary/20 disabled:opacity-50">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL: Add Vaccination removed for customers -->
    </div>

<!-- AI Vaccine Schedule Modal -->
<div id="ai-vaccine-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="document.getElementById('ai-vaccine-modal').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-3xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 flex justify-between items-center text-white shrink-0">
            <h3 class="font-black text-lg flex items-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Phác Đồ Tiêm Chủng Đề Xuất (Bởi Bác Sĩ AI)
            </h3>
            <button onclick="document.getElementById('ai-vaccine-modal').classList.add('hidden')" class="text-white/80 hover:text-white transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="p-8 overflow-y-auto grow custom-scrollbar bg-gray-50/50" id="ai-vaccine-content">
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <i class="fa-solid fa-circle-notch fa-spin text-4xl text-emerald-500 mb-4"></i>
                <p class="font-bold text-sm">Bác sĩ AI đang phân tích dữ liệu độ tuổi và loài...</p>
                <p class="text-xs mt-1">Quá trình này có thể mất 10-15 giây</p>
            </div>
        </div>
        <div class="p-4 bg-white border-t border-gray-100 flex justify-end shrink-0">
            <button onclick="document.getElementById('ai-vaccine-modal').classList.add('hidden')" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold text-sm transition">Đóng lại</button>
        </div>
    </div>
</div>

<script>
    function fetchAiVaccineSchedule() {
        const contentDiv = document.getElementById('ai-vaccine-content');
        contentDiv.innerHTML = `
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <i class="fa-solid fa-circle-notch fa-spin text-4xl text-emerald-500 mb-4"></i>
                <p class="font-bold text-sm">Bác sĩ AI đang phân tích dữ liệu độ tuổi và loài của thú cưng...</p>
                <p class="text-xs mt-1">Quá trình này có thể mất 10-15 giây tùy thuộc vào tốc độ AI.</p>
            </div>
        `;
        
        const formData = new FormData();
        formData.append('pet_id', '<?php echo $data['pet']->id; ?>');
        
        fetch('<?php echo URLROOT; ?>/ai/getVaccineSchedule', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                contentDiv.innerHTML = `<div class="prose prose-sm prose-emerald max-w-none text-gray-800">${marked.parse(data.data)}</div>`;
            } else {
                contentDiv.innerHTML = `<div class="p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 text-sm font-bold"><i class="fa-solid fa-triangle-exclamation"></i> ${data.message}</div>`;
            }
        })
        .catch(err => {
            contentDiv.innerHTML = `<div class="p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 text-sm font-bold"><i class="fa-solid fa-triangle-exclamation"></i> Đã xảy ra lỗi khi kết nối với máy chủ AI.</div>`;
        });
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
