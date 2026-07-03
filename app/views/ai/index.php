<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="min-h-[calc(100vh-64px)] bg-slate-950 text-slate-100 py-16 relative overflow-hidden" x-data="{ symptoms: '<?php echo isset($data['symptoms']) ? addslashes(htmlspecialchars($data['symptoms'])) : ''; ?>', isAnalyzing: false }">
    
    <!-- Futuristic Background Glows -->
    <div class="absolute top-1/4 left-1/10 w-[300px] md:w-[500px] h-[300px] md:h-[500px] bg-indigo-500/10 rounded-full blur-[100px] md:blur-[150px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/10 w-[300px] md:w-[500px] h-[300px] md:h-[500px] bg-pink-500/10 rounded-full blur-[100px] md:blur-[150px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] md:w-[700px] h-[400px] md:h-[700px] bg-purple-500/5 rounded-full blur-[120px] md:blur-[200px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Title & Subtitle Section -->
        <div class="text-center mb-14">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-xl shadow-indigo-500/30 mb-6 animate-pulse">
                <i class="fa-solid fa-brain text-white text-4xl"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-black tracking-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">AI Doctor</span> Phân Tích Sức Khỏe
            </h1>
            <p class="mt-4 text-slate-400 max-w-2xl mx-auto text-base md:text-lg font-medium leading-relaxed">
                Hệ thống chuẩn đoán lâm sàng tự động sử dụng trí tuệ nhân tạo chuyên sâu về thú y. Hãy mô tả trạng thái của bé để nhận lời khuyên y tế tức thì.
            </p>
        </div>

        <!-- Main Form Box -->
        <div class="bg-slate-900/60 backdrop-blur-xl border border-slate-800/80 rounded-3xl shadow-2xl overflow-hidden mb-12">
            <div class="p-8 md:p-10">
                <form action="<?php echo URLROOT; ?>/ai" method="POST" @submit="isAnalyzing = true">
                    <div class="mb-6">
                        <label for="symptoms" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">
                            <i class="fa-solid fa-file-waveform mr-1 text-indigo-400"></i> Mô tả chi tiết triệu chứng của bé
                        </label>
                        <textarea id="symptoms" name="symptoms" x-model="symptoms" rows="6" 
                                  class="w-full bg-slate-950/80 text-white placeholder:text-slate-600 border border-slate-800 rounded-2xl p-5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300 text-base md:text-lg shadow-inner"
                                  placeholder="Ví dụ: Bé Poodle 2 tuổi, bỏ ăn từ hôm qua, nôn ra dịch màu vàng nhạt, cơ thể có dấu hiệu mệt mỏi và nằm im một góc..." required></textarea>
                        
                        <?php if(!empty($data['error'])) : ?>
                            <div class="mt-3 flex items-center gap-2 text-red-400 text-sm font-bold bg-red-950/30 border border-red-900/50 px-4 py-2.5 rounded-xl">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span><?php echo $data['error']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Quick Suggestions -->
                    <div class="mb-8">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">
                            <i class="fa-regular fa-lightbulb mr-1"></i> Gợi ý triệu chứng nhanh
                        </p>
                        <div class="flex flex-wrap gap-2.5">
                            <button type="button" @click="symptoms = 'Chó Poodle 2 tuổi, bỏ ăn 2 ngày nay, bị nôn mửa dịch vàng, mệt mỏi.'"
                                    class="text-xs px-4 py-2 bg-slate-800/50 hover:bg-indigo-600/30 border border-slate-700/50 hover:border-indigo-500/50 rounded-xl transition duration-300 text-slate-300 hover:text-white font-medium flex items-center gap-1.5 shadow-sm">
                                🐕 Chó bỏ ăn, nôn dịch vàng
                            </button>
                            <button type="button" @click="symptoms = 'Mèo Anh lông ngắn bị rụng lông nhiều thành mảng, da mẩn đỏ và gãi liên tục.'"
                                    class="text-xs px-4 py-2 bg-slate-800/50 hover:bg-pink-600/30 border border-slate-700/50 hover:border-pink-500/50 rounded-xl transition duration-300 text-slate-300 hover:text-white font-medium flex items-center gap-1.5 shadow-sm">
                                🐱 Mèo ngứa ngáy, rụng lông
                            </button>
                            <button type="button" @click="symptoms = 'Cún Corgi bị đi ngoài phân lỏng, có mùi lạ, đi nhiều lần trong ngày kèm sốt nhẹ.'"
                                    class="text-xs px-4 py-2 bg-slate-800/50 hover:bg-purple-600/30 border border-slate-700/50 hover:border-purple-500/50 rounded-xl transition duration-300 text-slate-300 hover:text-white font-medium flex items-center gap-1.5 shadow-sm">
                                🤒 Đi ngoài lỏng, mệt mỏi
                            </button>
                            <button type="button" @click="symptoms = 'Bé mèo bị sổ mũi, thở khò khè, chảy nước mắt nhiều, hắt hơi liên tục.'"
                                    class="text-xs px-4 py-2 bg-slate-800/50 hover:bg-amber-600/30 border border-slate-700/50 hover:border-amber-500/50 rounded-xl transition duration-300 text-slate-300 hover:text-white font-medium flex items-center gap-1.5 shadow-sm">
                                🤧 Sổ mũi, hắt hơi khò khè
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-800/50">
                        <button type="submit" 
                                :disabled="isAnalyzing"
                                :class="isAnalyzing ? 'opacity-75 cursor-not-allowed' : 'hover:shadow-indigo-500/40 hover:scale-[1.03] active:scale-[0.98]'"
                                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white text-base font-black rounded-2xl shadow-xl shadow-indigo-500/20 transition-all duration-300">
                            <span x-show="!isAnalyzing"><i class="fa-solid fa-wand-magic-sparkles mr-2"></i> Bắt Đầu Phân Tích</span>
                            <span x-show="isAnalyzing"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Đang Phân Tích...</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Kết quả AI -->
            <?php if(!empty($data['ai_response'])) : ?>
                <div class="bg-slate-950 p-8 md:p-10 border-t border-slate-800/80 relative">
                    <!-- Tech overlay line -->
                    <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <i class="fa-solid fa-stethoscope text-indigo-400 mr-1"></i> Chẩn Đoán Sơ Bộ Từ AI Doctor
                    </h3>
                    
                    <div class="prose prose-invert max-w-none text-slate-300 bg-slate-900/40 border border-slate-800/80 p-6 md:p-8 rounded-2xl shadow-inner leading-relaxed text-base space-y-4">
                        <?php 
                            // Chuyển markdown basic (**, *, \n) thành HTML
                            $text = htmlspecialchars($data['ai_response']);
                            // Bọc các tiêu đề 
                            $text = preg_replace('/(1\.\s+Phân tích triệu chứng|2\.\s+Nguyên nhân có thể|3\.\s+Mức độ khẩn cấp|4\.\s+Lời khuyên chăm sóc tại nhà)/', '<h4 class="text-lg font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400 mt-6 first:mt-0 pb-2 border-b border-slate-800/60 mb-3">$1</h4>', $text);
                            $text = preg_replace('/\*\*(.*?)\*\*/', '<strong class="text-white font-bold">$1</strong>', $text);
                            $text = preg_replace('/\*([^\*]+)\*/', '<em class="text-indigo-300">$1</em>', $text);
                            $text = nl2br($text);
                            echo $text;
                        ?>
                    </div>

                    <!-- Call To Action Warning / Booking Banner -->
                    <div class="mt-8 bg-gradient-to-r from-indigo-950/80 via-purple-950/60 to-pink-950/40 border border-indigo-500/20 p-6 rounded-2xl shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 flex-shrink-0">
                                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-amber-300">Khuyến Cáo Y Tế Quan Trọng</p>
                                <p class="text-xs text-slate-400 mt-1 leading-relaxed max-w-xl">
                                    Kết quả phân tích trên chỉ là chẩn đoán sơ bộ bằng trí tuệ nhân tạo và không thể thay thế xét nghiệm lâm sàng. Bạn nên đặt lịch với bác sĩ thú y của chúng tôi để được tư vấn chính xác nhất.
                                </p>
                            </div>
                        </div>
                        <a href="<?php echo URLROOT; ?>/service" 
                           class="whitespace-nowrap px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-slate-950 text-sm font-black rounded-xl shadow-lg shadow-amber-500/20 transition-all hover:scale-105 active:scale-95 flex items-center gap-1.5">
                            <i class="fa-solid fa-calendar-check"></i> Đặt Lịch Khám Ngay
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
