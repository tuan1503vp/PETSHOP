<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- Hero Section: VIP PRO Edition -->
<section class="relative min-h-[90vh] flex items-center overflow-hidden bg-dark">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo URLROOT; ?>/public/images/hero.png" class="w-full h-full object-cover opacity-50" alt="Pet Shop Hero">
        <div class="absolute inset-0 bg-gradient-to-r from-dark via-dark/90 to-transparent"></div>
    </div>
    
    <!-- Neon Blobs for futuristic look -->
    <div class="absolute top-20 left-10 w-72 h-72 bg-primary/30 rounded-full blur-[100px] animate-pulse"></div>
    <div class="absolute bottom-20 right-20 w-96 h-96 bg-secondary/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-20">
        <div class="lg:w-2/3">
            <span class="inline-flex items-center px-5 py-2 mb-6 text-xs font-black tracking-widest text-white uppercase bg-white/10 backdrop-blur-md rounded-full border border-white/20 animate-bounce shadow-xl">
                <i class="fa-solid fa-paw text-secondary mr-2"></i>PETSHOP
            </span>
            <h1 class="text-5xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-200 to-gray-400 leading-tight mb-6 drop-shadow-lg py-2">
                Chăm sóc cho những người bạn nhỏ
            </h1>
            <p class="text-xl text-slate-300 mb-10 max-w-2xl leading-relaxed">
                Nâng tầm trải nghiệm chăm sóc thú cưng với công nghệ AI tiên tiến, dịch vụ Spa chuẩn 5 sao và hệ thống mua sắm thông minh. Chúng tôi không chỉ là cửa hàng, chúng tôi là gia đình.
            </p>
            
            <div class="flex flex-wrap gap-5 mt-4">
                <a href="<?php echo URLROOT; ?>/service" class="px-8 py-4 bg-gradient-to-r from-primary to-indigo-500 text-white font-bold rounded-full hover:shadow-[0_0_30px_rgba(79,70,229,0.5)] hover:-translate-y-1 transition-all duration-300 flex items-center group">
                    <i class="fa-solid fa-calendar-check mr-3 group-hover:scale-110 transition-transform"></i> Đặt Lịch Ngay
                </a>
                <a href="<?php echo URLROOT; ?>/ai" class="px-8 py-4 bg-white/5 backdrop-blur-sm text-white font-bold rounded-full hover:bg-white/10 transition-all duration-300 flex items-center border border-white/10 hover:border-white/30 group">
                    <i class="fa-solid fa-robot mr-3 text-secondary group-hover:rotate-12 transition-transform"></i> Khám Bệnh AI
                </a>
            </div>

            <!-- Trust Badges -->
            <div class="mt-16 flex flex-wrap items-center gap-8 opacity-70">
                <div class="flex items-center text-white">
                    <i class="fa-solid fa-shield-cat text-2xl mr-2 text-secondary"></i>
                    <span class="text-sm font-medium">Bảo vệ thú cưng</span>
                </div>
                <div class="flex items-center text-white">
                    <i class="fa-solid fa-user-doctor text-2xl mr-2 text-blue-400"></i>
                    <span class="text-sm font-medium">Đội ngũ chuyên gia</span>
                </div>
                <div class="flex items-center text-white">
                    <i class="fa-solid fa-star text-2xl mr-2 text-accent"></i>
                    <span class="text-sm font-medium">4.9/5 Đánh giá</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Element -->
    <div class="hidden lg:block absolute right-10 top-1/2 -translate-y-1/2 animate-float">
        <div class="w-80 h-80 rounded-full border-2 border-white/10 flex items-center justify-center relative">
            <div class="absolute inset-0 rounded-full bg-primary/20 blur-3xl"></div>
            <img src="<?php echo URLROOT; ?>/public/images/spa.png" class="w-64 h-64 rounded-full object-cover border-4 border-white/20 shadow-2xl z-10" alt="Spa Service">
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-10 bg-white/80 backdrop-blur-xl relative z-20 -mt-12 mx-4 lg:mx-auto max-w-6xl rounded-3xl shadow-2xl grid grid-cols-2 md:grid-cols-4 gap-8 px-10 border border-white">
    <div class="text-center">
        <p class="text-4xl font-extrabold text-primary mb-1">10k+</p>
        <p class="text-gray-500 font-medium">Khách hàng</p>
    </div>
    
    <div class="text-center">
        <p class="text-4xl font-extrabold text-secondary mb-1">50+</p>
        <p class="text-gray-500 font-medium">Thú cưng</p>
    </div>
    <div class="text-center">
        <p class="text-4xl font-extrabold text-blue-500 mb-1">20+</p>
        <p class="text-gray-500 font-medium">Chuyên gia</p>
    </div>
    <div class="text-center">
        <p class="text-4xl font-extrabold text-accent mb-1">99%</p>
        <p class="text-gray-500 font-medium">Hài lòng</p>
    </div>
</section>

<!-- Services Grid -->
<section class="py-24 max-w-7xl mx-auto px-4">
    <div class="text-center mb-16 reveal">
        <h2 class="text-secondary font-bold tracking-[0.2em] uppercase mb-2">Hệ sinh thái PetShop</h2>
        <p class="text-4xl md:text-5xl font-extrabold text-dark dark:text-white tracking-tight">Dịch vụ tận tâm</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- POS & Shop -->
        <div onclick="window.location.href='<?php echo URLROOT; ?>/product'" class="group reveal relative p-[2px] rounded-3xl transition-all duration-500 hover:-translate-y-2 bg-gradient-to-b from-primary/30 to-transparent shadow-xl shadow-primary/5 cursor-pointer">
            <div class="bg-white/90 backdrop-blur-sm p-6 rounded-[22px] h-full flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-gradient-to-br from-primary/10 to-primary/5 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fa-solid fa-store text-xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 mb-3"> Mua Sắm Online </h3>
                    <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Trải nghiệm mua sắm từ thức ăn hạt cao cấp đến các phụ kiện thời thượng.</p>
                </div>
                <a href="<?php echo URLROOT; ?>/product" class="inline-flex items-center text-primary text-xs font-bold hover:text-indigo-800 transition-colors group/btn">
                    Khám phá ngay <i class="fa-solid fa-arrow-right ml-2 transform group-hover/btn:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Spa & Grooming -->
        <div onclick="window.location.href='<?php echo URLROOT; ?>/service'" class="group reveal relative p-[2px] rounded-3xl transition-all duration-500 hover:-translate-y-2 bg-gradient-to-b from-secondary/30 to-transparent shadow-xl shadow-secondary/5 cursor-pointer" style="transition-delay: 100ms;">
            <div class="bg-white/90 backdrop-blur-sm p-6 rounded-[22px] h-full flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-gradient-to-br from-secondary/10 to-secondary/5 rounded-2xl flex items-center justify-center text-secondary mb-6 group-hover:scale-110 group-hover:bg-secondary group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fa-solid fa-scissors text-xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 mb-3">Chăm Sóc & Đặt Lịch</h3>
                    <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Đội ngũ kỹ thuật viên tay nghề cao giúp "boss" của bạn lột xác với diện mạo mới.</p>
                </div>
                <a href="<?php echo URLROOT; ?>/service" class="inline-flex items-center text-secondary text-xs font-bold hover:text-pink-700 transition-colors group/btn">
                    Xem bảng giá <i class="fa-solid fa-arrow-right ml-2 transform group-hover/btn:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- AI Health -->
        <div onclick="window.location.href='<?php echo URLROOT; ?>/ai'" class="group reveal relative p-[2px] rounded-3xl transition-all duration-500 hover:-translate-y-2 bg-gradient-to-b from-blue-500/30 to-transparent shadow-xl shadow-blue-500/5 cursor-pointer" style="transition-delay: 200ms;">
            <div class="bg-white/90 backdrop-blur-sm p-6 rounded-[22px] h-full flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500/10 to-blue-500/5 rounded-2xl flex items-center justify-center text-blue-500 mb-6 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fa-solid fa-microchip text-xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 mb-3">Bác Sĩ AI</h3>
                    <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Chẩn đoán sơ bộ bằng trí tuệ nhân tạo, phát hiện sớm các bất thường qua ảnh chụp.</p>
                </div>
                <a href="<?php echo URLROOT; ?>/ai" class="inline-flex items-center text-blue-500 text-xs font-bold hover:text-blue-700 transition-colors group/btn">
                    Trải nghiệm AI <i class="fa-solid fa-arrow-right ml-2 transform group-hover/btn:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Pet Care & Health Book -->
        <div onclick="window.location.href='<?php echo URLROOT; ?>/pet'" class="group reveal relative p-[2px] rounded-3xl transition-all duration-500 hover:-translate-y-2 bg-gradient-to-b from-purple-500/30 to-transparent shadow-xl shadow-purple-500/5 cursor-pointer" style="transition-delay: 300ms;">
            <div class="bg-white/90 backdrop-blur-sm p-6 rounded-[22px] h-full flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500/10 to-purple-500/5 rounded-2xl flex items-center justify-center text-purple-500 mb-6 group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fa-solid fa-paw text-xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 mb-3">Sổ Sức Khỏe & Pet</h3>
                    <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Quản lý danh sách thú cưng tại nhà và theo dõi hồ sơ khám từ phòng khám.</p>
                </div>
                <a href="<?php echo URLROOT; ?>/pet" class="inline-flex items-center text-purple-500 text-xs font-bold hover:text-purple-700 transition-colors group/btn">
                    Quản lý ngay <i class="fa-solid fa-arrow-right ml-2 transform group-hover/btn:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- AI Promo Section -->
<section class="py-24 bg-dark overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
        <div class="relative reveal">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
            <img src="<?php echo URLROOT; ?>/public/images/ai_health.png" class="rounded-3xl shadow-2xl relative z-10 border border-white/10" alt="AI Health Analysis">
            <div class="absolute -bottom-6 -right-6 glass p-6 rounded-2xl z-20 border border-white/20 animate-float">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="text-dark font-bold">AI Phân tích xong!</p>
                        <p class="text-xs text-slate-500">Thú cưng của bạn rất khỏe mạnh</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="reveal" style="transition-delay: 200ms;">
            <h2 class="text-primary font-bold tracking-[0.2em] uppercase mb-4">Chuẩn đoán bệnh bằng AI</h2>
            <h3 class="text-4xl md:text-5xl font-extrabold text-white mb-8 leading-tight">Sức khỏe Thú cưng <br> Trong Tầm Tay Bạn</h3>
            <p class="text-slate-400 text-lg mb-10 leading-relaxed">
                Hệ thống trí tuệ nhân tạo của chúng tôi được huấn luyện với hàng triệu dữ liệu hình ảnh, giúp nhận diện nhanh chóng các biểu hiện bất thường trên da, mắt và hành vi của thú cưng.
            </p>
            <ul class="space-y-4 mb-10">
                <li class="flex items-center text-slate-300">
                    <i class="fa-solid fa-circle-check text-primary mr-3"></i> Phản hồi trong 5 giây
                </li>
                <li class="flex items-center text-slate-300">
                    <i class="fa-solid fa-circle-check text-primary mr-3"></i> Độ chính xác lên tới 92%
                </li>
                <li class="flex items-center text-slate-300">
                    <i class="fa-solid fa-circle-check text-primary mr-3"></i> Hoàn toàn miễn phí
                </li>
            </ul>
            <a href="<?php echo URLROOT; ?>/ai" class="inline-block px-10 py-4 bg-gradient-to-r from-primary to-secondary text-white font-bold rounded-full hover:shadow-[0_0_30px_rgba(236,72,153,0.5)] hover:-translate-y-1 transition-all duration-300">
                Thử Ngay Bác Sĩ AI
            </a>
        </div>
    </div>
</section>

<!-- FAQ & Customer Care Section -->
<section class="py-24 bg-white dark:bg-slate-950 relative">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16 reveal">
            <h2 class="text-primary font-bold tracking-[0.2em] uppercase mb-2">Hỗ trợ khách hàng</h2>
            <p class="text-4xl md:text-5xl font-extrabold text-dark dark:text-white tracking-tight">Câu Hỏi Thường Gặp</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- FAQ Accordion -->
            <div class="space-y-4 reveal">
                <!-- FAQ Item 1 -->
                <div class="border border-gray-200 dark:border-slate-700 rounded-2xl overflow-hidden group">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center bg-gray-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors focus:outline-none" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <span class="font-bold text-gray-800 dark:text-slate-200">Làm thế nào để đặt lịch Spa cho thú cưng?</span>
                        <i class="fa-solid fa-chevron-down text-gray-400 dark:text-slate-500 group-hover:text-primary transition-colors"></i>
                    </button>
                    <div class="hidden px-6 py-4 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 leading-relaxed border-t border-gray-100 dark:border-slate-700">
                        Bạn có thể dễ dàng đặt lịch Spa bằng cách nhấn vào nút "Đặt Lịch Ngay" trên trang chủ hoặc truy cập phần Dịch Vụ, chọn gói Spa phù hợp và điền thông tin thú cưng của bạn.
                    </div>
                </div>
                <!-- FAQ Item 2 -->
                <div class="border border-gray-200 dark:border-slate-700 rounded-2xl overflow-hidden group">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center bg-gray-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors focus:outline-none" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <span class="font-bold text-gray-800 dark:text-slate-200">Hệ thống AI chuẩn đoán có chính xác không?</span>
                        <i class="fa-solid fa-chevron-down text-gray-400 dark:text-slate-500 group-hover:text-primary transition-colors"></i>
                    </button>
                    <div class="hidden px-6 py-4 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 leading-relaxed border-t border-gray-100 dark:border-slate-700">
                        Hệ thống AI của chúng tôi được huấn luyện trên hàng triệu hình ảnh với độ chính xác lên tới 92%. Tuy nhiên, đây chỉ là chuẩn đoán sơ bộ và không thể thay thế hoàn toàn ý kiến của bác sĩ thú y chuyên môn.
                    </div>
                </div>
                <!-- FAQ Item 3 -->
                <div class="border border-gray-200 dark:border-slate-700 rounded-2xl overflow-hidden group">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center bg-gray-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors focus:outline-none" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <span class="font-bold text-gray-800 dark:text-slate-200">Chính sách đổi trả hàng như thế nào?</span>
                        <i class="fa-solid fa-chevron-down text-gray-400 dark:text-slate-500 group-hover:text-primary transition-colors"></i>
                    </button>
                    <div class="hidden px-6 py-4 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 leading-relaxed border-t border-gray-100 dark:border-slate-700">
                        Chúng tôi hỗ trợ đổi trả trong vòng 7 ngày đối với các sản phẩm thức ăn và phụ kiện chưa bóc tem, hư hỏng do lỗi nhà sản xuất. Vui lòng giữ lại hóa đơn khi đổi trả.
                    </div>
                </div>
                <!-- FAQ Item 4 -->
                <div class="border border-gray-200 dark:border-slate-700 rounded-2xl overflow-hidden group">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center bg-gray-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors focus:outline-none" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <span class="font-bold text-gray-800 dark:text-slate-200">Cửa hàng có dịch vụ giao hàng tận nơi không?</span>
                        <i class="fa-solid fa-chevron-down text-gray-400 dark:text-slate-500 group-hover:text-primary transition-colors"></i>
                    </button>
                    <div class="hidden px-6 py-4 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 leading-relaxed border-t border-gray-100 dark:border-slate-700">
                        Có, PetShop hỗ trợ giao hàng toàn quốc. Freeship cho các đơn hàng từ 500.000đ trở lên tại khu vực nội thành.
                    </div>
                </div>
                <!-- FAQ Item 5 -->
                <div class="border border-gray-200 dark:border-slate-700 rounded-2xl overflow-hidden group">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center bg-gray-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors focus:outline-none" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <span class="font-bold text-gray-800 dark:text-slate-200">
                            <i class="fa-solid fa-crown text-yellow-500 mr-2"></i>
                            Hệ thống hạng hội viên của PETSHOP gồm những hạng nào?
                        </span>
                        <i class="fa-solid fa-chevron-down text-gray-400 dark:text-slate-500 group-hover:text-primary transition-colors"></i>
                    </button>
                    <div class="hidden px-6 py-4 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 leading-relaxed border-t border-gray-100 dark:border-slate-700">
                        PETSHOP có <strong>4 hạng hội viên</strong>:
                        <ul class="mt-3 space-y-2">
                            <li class="flex items-center gap-2"><span class="inline-block w-3 h-3 rounded-full bg-slate-400"></span> <strong class="text-slate-500">Thành viên</strong> — Mặc định khi đăng ký tài khoản (0đ)</li>
                            <li class="flex items-center gap-2"><span class="inline-block w-3 h-3 rounded-full bg-yellow-600"></span> <strong class="text-yellow-700">Đồng</strong> — Tích lũy từ 500.000đ</li>
                            <li class="flex items-center gap-2"><span class="inline-block w-3 h-3 rounded-full bg-slate-500"></span> <strong class="text-slate-600">Bạc</strong> — Tích lũy từ 2.000.000đ</li>
                            <li class="flex items-center gap-2"><span class="inline-block w-3 h-3 rounded-full bg-yellow-400"></span> <strong class="text-yellow-600">Vàng</strong> — Tích lũy từ 5.000.000đ</li>
                            <li class="flex items-center gap-2"><span class="inline-block w-3 h-3 rounded-full bg-blonde-400"></span> <strong class="text-yellow-600">Bạch Kim</strong> — Tích lũy từ 10.000.000đ</li>

                        </ul>
                    </div>
                </div>
                <!-- FAQ Item 6 -->
                <div class="border border-gray-200 dark:border-slate-700 rounded-2xl overflow-hidden group">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center bg-gray-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors focus:outline-none" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <span class="font-bold text-gray-800 dark:text-slate-200">
                            <i class="fa-solid fa-arrow-trend-up text-green-500 mr-2"></i>
                            Làm thế nào để nâng hạng hội viên?
                        </span>
                        <i class="fa-solid fa-chevron-down text-gray-400 dark:text-slate-500 group-hover:text-primary transition-colors"></i>
                    </button>
                    <div class="hidden px-6 py-4 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 leading-relaxed border-t border-gray-100 dark:border-slate-700">
                        Hạng hội viên được <strong>tự động nâng cấp</strong> dựa trên tổng chi tiêu tích lũy của bạn tại PETSHOP (bao gồm cả mua sản phẩm và đặt dịch vụ). Hệ thống sẽ kiểm tra và cập nhật hạng ngay sau mỗi đơn hàng hoàn thành — bạn không cần thao tác gì thêm!
                    </div>
                </div>
                <!-- FAQ Item 7 -->
                <div class="border border-gray-200 dark:border-slate-700 rounded-2xl overflow-hidden group">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center bg-gray-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors focus:outline-none" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <span class="font-bold text-gray-800 dark:text-slate-200">
                            <i class="fa-solid fa-gift text-pink-500 mr-2"></i>
                            Hội viên được hưởng ưu đãi gì?
                        </span>
                        <i class="fa-solid fa-chevron-down text-gray-400 dark:text-slate-500 group-hover:text-primary transition-colors"></i>
                    </button>
                    <div class="hidden px-6 py-4 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 leading-relaxed border-t border-gray-100 dark:border-slate-700">
                        Mỗi hạng hội viên nhận ưu đãi khác nhau:
                        <ul class="mt-3 space-y-2">
                            <li class="flex items-center gap-2"><span class="inline-block w-3 h-3 rounded-full bg-yellow-600"></span> <strong class="text-yellow-700">Đồng:</strong> Giảm 3% tổng đơn hàng, freeship nội thành</li>
                            <li class="flex items-center gap-2"><span class="inline-block w-3 h-3 rounded-full bg-slate-500"></span> <strong class="text-slate-600">Bạc:</strong> Giảm 5% + ưu tiên đặt lịch dịch vụ</li>
                            <li class="flex items-center gap-2"><span class="inline-block w-3 h-3 rounded-full bg-yellow-400"></span> <strong class="text-yellow-600">Vàng:</strong> Giảm 10% + quà sinh nhật thú cưng + tư vấn bác sĩ miễn phí</li>
                        </ul>
                        <p class="mt-3 text-sm text-primary font-medium">✨ Đăng nhập và xem hạng hội viên của bạn trong trang <a href="<?php echo URLROOT; ?>/profile" class="underline">Tài khoản</a>.</p>
                    </div>
                </div>
            </div>

            <!-- Customer Care Info -->
            <div class="reveal" style="transition-delay: 200ms;">
                <div class="bg-gradient-to-br from-indigo-50 to-pink-50 dark:from-slate-800 dark:to-slate-900 p-8 rounded-3xl border border-white dark:border-slate-700 shadow-xl h-full flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary/10 rounded-full blur-2xl"></div>
                    
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6 relative z-10">Bạn cần hỗ trợ thêm?</h3>
                    <p class="text-slate-600 dark:text-slate-400 mb-8 relative z-10">Đội ngũ chăm sóc khách hàng của chúng tôi luôn sẵn sàng giải đáp mọi thắc mắc của bạn 24/7. Đừng ngần ngại liên hệ!</p>
                    
                    <div class="space-y-6 relative z-10">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center text-primary shadow-sm mr-4">
                                <i class="fa-solid fa-phone-volume text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-slate-400 font-bold uppercase tracking-wider">Hotline</p>
                                <p class="text-lg font-black text-gray-800 dark:text-slate-200">0947647052</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center text-secondary shadow-sm mr-4">
                                <i class="fa-solid fa-envelope text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-slate-400 font-bold uppercase tracking-wider">Email</p>
                                <p class="text-lg font-black text-gray-800 dark:text-slate-200">nmtvp11223311@gmail.com</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center text-blue-500 shadow-sm mr-4">
                                <i class="fa-brands fa-facebook-messenger text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-slate-400 font-bold uppercase tracking-wider">Facebook</p>
                                <a href="https://www.facebook.com/nmtuan2004" target="_blank" class="text-lg font-black text-blue-600 dark:text-blue-400 hover:underline">nmtuan2004</a>
                            </div>
                        </div>

                        <div class="mt-8 pt-4">
                            <a href="<?php echo URLROOT; ?>/contact" class="inline-flex w-full justify-center items-center px-6 py-3 bg-gradient-to-r from-primary to-indigo-600 text-white font-bold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                <i class="fa-solid fa-envelope-open-text mr-2"></i> Gửi Yêu Cầu Liên Hệ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scroll Reveal Script moved to footer -->

<?php require APPROOT . '/views/inc/footer.php'; ?>
