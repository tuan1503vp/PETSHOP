<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['seo']['title']) ? $data['seo']['title'] : SITENAME; ?></title>
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo isset($data['seo']['description']) ? $data['seo']['description'] : 'Cửa hàng PetShop - Nơi cung cấp thức ăn, phụ kiện, đồ chơi chính hãng cho thú cưng.'; ?>">
    <meta property="og:title" content="<?php echo isset($data['seo']['title']) ? $data['seo']['title'] : SITENAME; ?>">
    <meta property="og:description" content="<?php echo isset($data['seo']['description']) ? $data['seo']['description'] : 'Cửa hàng PetShop - Nơi cung cấp thức ăn, phụ kiện, đồ chơi chính hãng cho thú cưng.'; ?>">
    <meta property="og:image" content="<?php echo isset($data['seo']['image']) && !empty($data['seo']['image']) ? $data['seo']['image'] : URLROOT . '/public/img/default-seo.jpg'; ?>">
    <meta property="og:url" content="<?php echo URLROOT . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:type" content="website">
    <!-- Tailwind CSS (CDN for rapid prototyping) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css?v=<?php echo time(); ?>">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5', // Indigo-600
                        secondary: '#ec4899', // Pink-500
                        accent: '#f59e0b', // Amber-500
                        dark: '#0f172a' // Tối hơn cho sang trọng
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .text-gradient {
            background: linear-gradient(to right, #4f46e5, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        [x-cloak] { display: none !important; }
    </style>
    <!-- Dark Mode Init Script (Prevents flash of light theme) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    </script>
</head>
<body x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" 
      :class="{ 'dark': darkMode }" 
      class="bg-slate-50 font-sans leading-normal tracking-normal overflow-x-hidden">
    <!-- Navbar -->
    <nav x-data="{ mobileMenuOpen: false }" class="bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center w-full sm:w-auto justify-between sm:justify-start">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="<?php echo URLROOT; ?>" class="text-2xl font-bold text-primary">
                            <i class="fa-solid fa-paw mr-2 text-secondary"></i>PETSHOP
                        </a>
                    </div>
                    
                    <!-- Mobile Hamburger Button (Only on Mobile) -->
                    <div class="flex items-center sm:hidden gap-4">
                        <?php if(isLoggedIn()) : ?>
                        <?php 
                            $cartCountMobile = 0;
                            if(isset($_SESSION['cart'])) {
                                foreach($_SESSION['cart'] as $item) {
                                    $cartCountMobile += $item['quantity'];
                                }
                            }
                        ?>
                        <a href="<?php echo URLROOT; ?>/cart" class="text-gray-500 hover:text-primary relative" title="Giỏ hàng">
                            <i class="fa-solid fa-cart-shopping text-xl"></i>
                            <span id="cart-badge-mobile" class="absolute -top-1 -right-2 inline-flex items-center justify-center px-1.5 py-0.5 text-[9px] font-bold leading-none text-white bg-indigo-600 rounded-full <?php echo $cartCountMobile == 0 ? 'hidden' : ''; ?>"><?php echo $cartCountMobile; ?></span>
                        </a>
                        <?php endif; ?>
                        <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light'); if(darkMode) { document.documentElement.classList.add('dark') } else { document.documentElement.classList.remove('dark') }" 
                                class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 flex items-center justify-center transition-all duration-300" 
                                title="Chuyển chế độ tối">
                            <span x-show="darkMode" class="text-amber-400 text-sm" x-cloak><i class="fa-solid fa-sun animate-spin-slow"></i></span>
                            <span x-show="!darkMode" class="text-indigo-600 text-sm" x-cloak><i class="fa-solid fa-moon"></i></span>
                        </button>
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-primary focus:outline-none">
                            <i class="fa-solid fa-bars text-2xl" x-show="!mobileMenuOpen"></i>
                            <i class="fa-solid fa-xmark text-2xl" x-show="mobileMenuOpen" x-cloak></i>
                        </button>
                    </div>
                    <?php 
                    $current_url = $_SERVER['REQUEST_URI'];
                    $path = trim(str_replace(URLROOT, '', (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $current_url), '/');
                    $is_home = ($path == '' || $path == 'home' || $path == 'home/index' || $path == 'PETSHOP');
                    $is_product = strpos($current_url, '/product') !== false;
                    $is_service = strpos($current_url, '/service') !== false;
                    $is_ai = strpos($current_url, '/ai') !== false;
                    
                    $active_nav = "border-primary text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold whitespace-nowrap";
                    $inactive_nav = "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition whitespace-nowrap";

                ?>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-4 lg:space-x-6">
                    <a href="<?php echo URLROOT; ?>" class="<?php echo $is_home ? $active_nav : $inactive_nav; ?>">
                        Trang chủ
                    </a>
                    <a href="<?php echo URLROOT; ?>/product" class="<?php echo $is_product ? $active_nav : $inactive_nav; ?>">
                        Cửa hàng
                    </a>
                    <a href="<?php echo URLROOT; ?>/service" class="<?php echo $is_service ? $active_nav : $inactive_nav; ?>">
                        Dịch vụ
                    </a>
                    <a href="<?php echo URLROOT; ?>/ai" class="<?php echo $is_ai ? $active_nav : $inactive_nav; ?>">
                        AI Phân tích
                    </a>
                    <?php if (isLoggedIn()): ?>
                    <a href="<?php echo URLROOT; ?>/pet" class="<?php echo (strpos($current_url, '/pet') !== false) ? $active_nav : $inactive_nav; ?>">
                        Thú cưng của tôi
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo URLROOT; ?>/contact" class="<?php echo (strpos($current_url, '/contact') !== false) ? $active_nav : $inactive_nav; ?>">
                        Liên hệ
                    </a>
                </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <!-- Dark Mode Toggle Button -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light'); if(darkMode) { document.documentElement.classList.add('dark') } else { document.documentElement.classList.remove('dark') }" 
                            class="mr-4 w-10 h-10 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-500 dark:text-gray-400 rounded-2xl flex items-center justify-center transition-all duration-300 shadow-sm border border-gray-100 dark:border-slate-700 hover:scale-105 active:scale-95 cursor-pointer" 
                            title="Chuyển chế độ tối">
                        <!-- Sun Icon (visible in Dark Mode) -->
                        <span x-show="darkMode" class="text-amber-400 text-lg" x-cloak>
                            <i class="fa-solid fa-sun animate-spin-slow"></i>
                        </span>
                        <!-- Moon Icon (visible in Light Mode) -->
                        <span x-show="!darkMode" class="text-indigo-600 text-lg" x-cloak>
                            <i class="fa-solid fa-moon"></i>
                        </span>
                    </button>

                    <?php 
                        $cartCount = 0;
                        if(isset($_SESSION['cart'])) {
                            foreach($_SESSION['cart'] as $item) {
                                $cartCount += $item['quantity'];
                            }
                        }
                    ?>
                    <?php if(isLoggedIn()) : ?>
                        <?php 
                            $wishCount = isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0;
                        ?>
                        <a href="<?php echo URLROOT; ?>/wishlist" class="text-gray-500 hover:text-red-500 relative px-3 py-2 transition-colors" title="Danh sách yêu thích">
                            <i class="fa-solid fa-heart text-xl"></i>
                            <span id="wishlist-badge" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-500 rounded-full <?php echo $wishCount == 0 ? 'hidden' : ''; ?>"><?php echo $wishCount; ?></span>
                        </a>

                        <a href="<?php echo URLROOT; ?>/cart" class="text-gray-500 hover:text-primary relative px-3 py-2 transition-colors ml-2" title="Giỏ hàng">
                            <i class="fa-solid fa-cart-shopping text-xl"></i>
                            <span id="cart-badge" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-indigo-600 rounded-full <?php echo $cartCount == 0 ? 'hidden' : ''; ?>"><?php echo $cartCount; ?></span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if(isLoggedIn()) : 
                        require_once APPROOT . '/models/User.php';
                        $userModel = new User();
                        $stats = null;
                        $member = null;
                        $level = 'Đồng';
                        try {
                            $membershipInfo = $userModel->getMembershipFullInfo($_SESSION['user_id']);
                            $stats = $membershipInfo['stats'];
                            $member = $membershipInfo['member'];
                            $level = $member->membership_level ?? 'Đồng';
                        } catch (Exception $e) {
                            // Cơ sở dữ liệu ngoại tuyến, sử dụng cấp độ mặc định
                        }
                        
                        $badgeClass = "bg-orange-100 text-orange-700"; // Đồng
                        if ($level == 'Bạc') $badgeClass = "bg-slate-100 text-slate-700";
                        if ($level == 'Vàng') $badgeClass = "bg-yellow-100 text-yellow-700";
                        if ($level == 'Bạch kim') $badgeClass = "bg-blue-100 text-blue-700";
                        if ($level == 'VIP') $badgeClass = "bg-purple-100 text-purple-700 border border-purple-200 animate-pulse";

                        // Membership upgrade calculations
                        $annual_spent = $stats->annual_spent ?? 0;
                        $next_level = '';
                        $level_min = 0;
                        $level_max = 0;

                        if ($level === 'Đồng') {
                            $next_level = 'Bạc';
                            $level_min = 0;
                            $level_max = 1000000;
                        } elseif ($level === 'Bạc') {
                            $next_level = 'Vàng';
                            $level_min = 1000000;
                            $level_max = 5000000;
                        } elseif ($level === 'Vàng') {
                            $next_level = 'Bạch kim';
                            $level_min = 5000000;
                            $level_max = 10000000;
                        } elseif ($level === 'Bạch kim') {
                            $next_level = 'VIP';
                            $level_min = 10000000;
                            $level_max = 10000000;
                        } else {
                            $next_level = '';
                            $level_min = 10000000;
                            $level_max = 10000000;
                        }

                        if ($level_max > $level_min) {
                            $progress_percent = (($annual_spent - $level_min) / ($level_max - $level_min)) * 100;
                            $progress_percent = max(0, min(100, $progress_percent));
                            $needed_amount = $level_max - $annual_spent;
                        } else {
                            $progress_percent = 100;
                            $needed_amount = 0;
                        }
                    ?>
                        <div class="relative ml-3 flex items-center" x-data="{ openMember: false }">
                            <?php if($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'staff' || $_SESSION['user_role'] == 'manager' || $_SESSION['user_role'] == 'cashier' || $_SESSION['user_role'] == 'doctor') : ?>
                                <a href="<?php echo URLROOT; ?>/admin" class="bg-gray-800 text-white hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium mr-4 flex items-center transition">
                                    <i class="fa-solid fa-gauge-high mr-2 text-blue-400"></i> Quản Trị
                                </a>
                            <?php endif; ?>

                            <!-- Notification Bell -->
                            <?php 
                                $notifications = [];
                                $unreadCount = 0;
                                try {
                                    $notifModel = $this->model('Notification');
                                    $notifications = $notifModel->getNotificationsByUser($_SESSION['user_id']);
                                    $unreadCount = $notifModel->getUnreadCount($_SESSION['user_id']);
                                } catch (Exception $e) {
                                    // Cơ sở dữ liệu ngoại tuyến, bỏ qua việc tải thông báo
                                }
                            ?>
                            <div class="relative mr-4" x-data="{ 
                                openNotif: false, 
                                unreadCount: <?php echo $unreadCount; ?>,
                                notifications: <?php echo htmlspecialchars(json_encode($notifications)); ?>,
                                selectedNotif: null,
                                showNotifDetail: false,
                                
                                async init() {
                                    setInterval(async () => {
                                        try {
                                            const res = await fetch('<?php echo URLROOT; ?>/notification/check_new');
                                            const data = await res.json();
                                            if (data.success) {
                                                // If new unread notification arrived, we can show an alert or just update badge
                                                if (data.unreadCount > this.unreadCount && this.unreadCount !== null) {
                                                    // Trigger a subtle pulse animation on the bell (optional)
                                                }
                                                this.unreadCount = data.unreadCount;
                                                this.notifications = data.notifications;
                                            }
                                        } catch (e) {}
                                    }, 60000); // Tăng lên 60 giây để tránh bị host giới hạn truy vấn
                                },

                                async markAsRead(notif) {
                                    this.selectedNotif = notif;
                                    this.showNotifDetail = true;
                                    
                                    if (parseInt(notif.is_read) === 1) return;
                                    
                                    try {
                                        const response = await fetch('<?php echo URLROOT; ?>/notification/mark_read/' + notif.id);
                                        const result = await response.json();
                                        if (result.success) {
                                            notif.is_read = 1;
                                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                                            // Update the local notification object in the list
                                            let n = this.notifications.find(item => item.id === notif.id);
                                            if (n) n.is_read = 1;
                                        }
                                    } catch (e) { console.error(e); }
                                },
                                
                                async markAllAsRead() {
                                    try {
                                        const response = await fetch('<?php echo URLROOT; ?>/notification/mark_all_read');
                                        const result = await response.json();
                                        if (result.success) {
                                            this.unreadCount = 0;
                                            this.notifications.forEach(n => n.is_read = 1);
                                        }
                                    } catch (e) { console.error(e); }
                                },
                                
                                async deleteAll() {
                                    if (!confirm('Bạn có chắc chắn muốn xóa tất cả thông báo?')) return;
                                    try {
                                        const response = await fetch('<?php echo URLROOT; ?>/notification/delete_all');
                                        const result = await response.json();
                                        if (result.success) {
                                            this.notifications = [];
                                            this.unreadCount = 0;
                                        }
                                    } catch (e) { console.error(e); }
                                }
                            }">
                                <button @click="openNotif = !openNotif" class="relative p-2 text-gray-400 hover:text-primary transition-colors focus:outline-none">
                                    <i class="fa-solid fa-bell text-xl"></i>
                                    <template x-if="unreadCount > 0">
                                        <span class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white" x-text="unreadCount">
                                        </span>
                                    </template>
                                </button>

                                <!-- Notification Dropdown Container -->
                                <div x-show="openNotif" x-cloak 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     @click.away="openNotif = false; showNotifDetail = false"
                                     class="absolute right-0 mt-4 w-80 bg-white rounded-3xl shadow-2xl border border-gray-100 z-[110] overflow-hidden">
                                    
                                    <!-- List View -->
                                    <div x-show="!showNotifDetail" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                        <div class="p-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                                            <h3 class="font-black text-gray-800 text-sm">Thông báo</h3>
                                            <div class="flex gap-2">
                                                <button @click="markAllAsRead()" class="text-[10px] font-bold text-primary hover:underline">Đọc tất cả</button>
                                                <span class="text-gray-300">|</span>
                                                <button @click="deleteAll()" class="text-[10px] font-bold text-red-400 hover:underline">Xóa tất cả</button>
                                            </div>
                                        </div>
                                        <div class="max-h-[400px] overflow-y-auto">
                                            <template x-if="notifications.length === 0">
                                                <div class="p-8 text-center">
                                                    <i class="fa-solid fa-bell-slash text-gray-200 text-3xl mb-2"></i>
                                                    <p class="text-xs text-gray-400 font-medium">Bạn chưa có thông báo nào</p>
                                                </div>
                                            </template>
                                            <template x-for="n in notifications" :key="n.id">
                                                <div @click="markAsRead(n)" 
                                                     class="p-4 border-b border-gray-50 hover:bg-slate-50 transition-colors cursor-pointer"
                                                     :class="{'opacity-60 bg-gray-50/30': n.id === selectedNotif?.id || parseInt(n.is_read) === 1}">
                                                    <div class="flex gap-3">
                                                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0"
                                                             :class="n.type === 'order' ? 'bg-orange-100 text-orange-600' : 
                                                                    (n.type === 'rank' ? 'bg-purple-100 text-purple-600' : 
                                                                    (n.type === 'service' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600'))">
                                                            <i class="fa-solid text-xs"
                                                               :class="n.type === 'order' ? 'fa-box' : 
                                                                      (n.type === 'rank' ? 'fa-crown' : 
                                                                      (n.type === 'service' ? 'fa-check-circle' : 'fa-info-circle'))"></i>
                                                        </div>
                                                        <div class="flex-1 overflow-hidden">
                                                            <p class="text-xs leading-tight mb-1" 
                                                               :class="parseInt(n.is_read) === 1 ? 'font-medium text-gray-600' : 'font-bold text-gray-800 text-primary'"
                                                               x-text="n.title">
                                                            </p>
                                                            <p class="text-[11px] text-gray-500 leading-normal truncate w-full" x-text="n.message"></p>
                                                            <p class="text-[9px] text-gray-400 mt-2 font-medium" x-text="new Date(n.created_at).toLocaleString('vi-VN')"></p>
                                                        </div>
                                                        <template x-if="parseInt(n.is_read) === 0">
                                                            <div class="w-2 h-2 rounded-full bg-primary mt-1 shrink-0"></div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Detail View (Integrated relative to maintain height) -->
                                    <div x-show="showNotifDetail" 
                                         x-transition:enter="transition ease-out duration-300" 
                                         x-transition:enter-start="opacity-0 translate-x-4" 
                                         x-transition:enter-end="opacity-100 translate-x-0"
                                         class="p-6 min-h-[300px]">
                                        <button @click="showNotifDetail = false" class="mb-4 text-[10px] font-black text-gray-400 hover:text-primary uppercase tracking-widest flex items-center gap-2">
                                            <i class="fa-solid fa-arrow-left"></i> Quay lại
                                        </button>
                                        
                                        <div class="flex items-center gap-3 mb-6">
                                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-xl"
                                                 :class="selectedNotif?.type === 'order' ? 'bg-orange-500 text-white' : 
                                                         (selectedNotif?.type === 'rank' ? 'bg-purple-500 text-white' : 
                                                         (selectedNotif?.type === 'service' ? 'bg-blue-500 text-white' : 'bg-gray-800 text-white'))">
                                                <i class="fa-solid" :class="selectedNotif?.type === 'order' ? 'fa-box' : 
                                                                           (selectedNotif?.type === 'rank' ? 'fa-crown' : 
                                                                           (selectedNotif?.type === 'service' ? 'fa-check-circle' : 'fa-info-circle'))"></i>
                                            </div>
                                            <div class="overflow-hidden">
                                                <h4 class="text-sm font-black text-gray-800 truncate" x-text="selectedNotif?.title"></h4>
                                                <p class="text-[9px] text-gray-400 font-bold" x-text="selectedNotif ? new Date(selectedNotif.created_at).toLocaleString('vi-VN') : ''"></p>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-100">
                                            <p class="text-xs text-gray-600 leading-relaxed font-medium" x-text="selectedNotif?.message"></p>
                                        </div>
                                        
                                        <button @click="showNotifDetail = false" 
                                                class="w-full py-3 bg-gray-900 text-white rounded-xl text-xs font-black hover:bg-black transition-all">
                                            Đóng
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mr-6 group cursor-pointer bg-slate-50 hover:bg-slate-100 border border-slate-100 p-2 pr-3 rounded-2xl transition-all" @click.stop="openMember = !openMember">
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none mb-1">Xin chào,</p>
                                    <p class="text-sm font-black text-gray-800 leading-none"><?php echo $_SESSION['user_name']; ?></p>
                                </div>
                                <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter <?php echo $badgeClass; ?>">
                                    <?php echo $level; ?>
                                </span>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 group-hover:text-primary transition-transform duration-300" :class="{'rotate-180': openMember}"></i>
                            </div>

                            <!-- Member Info Dropdown -->
                            <div x-show="openMember" x-cloak 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-1"
                                 @click.away="openMember = false"
                                 class="absolute top-full right-0 mt-4 w-80 bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100 overflow-hidden z-[100]">
                                
                                <div class="p-6 text-center bg-gradient-to-br from-slate-900 to-dark text-white relative">
                                    <?php if (!empty($_SESSION['user_avatar'])): ?>
                                        <img src="<?php echo $_SESSION['user_avatar']; ?>" alt="Avatar" class="w-16 h-16 rounded-2xl mx-auto object-cover mb-3 shadow-lg border-2 border-white/10">
                                    <?php else: ?>
                                        <div class="w-16 h-16 bg-gradient-to-tr <?php echo $level == 'VIP' ? 'from-purple-500 to-pink-500' : 'from-primary to-blue-400'; ?> rounded-2xl mx-auto flex items-center justify-center text-2xl font-black mb-3 shadow-lg border-2 border-white/10">
                                            <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="text-lg font-black leading-tight"><?php echo $_SESSION['user_name']; ?></h3>
                                    <p class="text-[10px] text-primary-light font-bold uppercase tracking-[0.2em] mt-1">Hạng <?php echo $level; ?></p>
                                </div>
                                
                                <div class="p-6">
                                    <div class="space-y-2">
                                        <a href="<?php echo URLROOT; ?>/profile" class="flex items-center justify-between w-full p-3 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition border border-gray-100 shadow-sm group">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                                Hồ sơ cá nhân
                                            </div>
                                            <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:translate-x-1 transition-transform"></i>
                                        </a>

                                        <a href="<?php echo URLROOT; ?>/order/history" class="flex items-center justify-between w-full p-3 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition border border-gray-100 shadow-sm group">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                                </div>
                                                Lịch sử mua hàng
                                            </div>
                                            <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:translate-x-1 transition-transform"></i>
                                        </a>

                                        <a href="<?php echo URLROOT; ?>/pet" class="flex items-center justify-between w-full p-3 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition border border-gray-100 shadow-sm group">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-primary flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                    <i class="fa-solid fa-paw"></i>
                                                </div>
                                                Thú cưng của tôi
                                            </div>
                                            <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:translate-x-1 transition-transform"></i>
                                        </a>
                                        
                                        <a href="<?php echo URLROOT; ?>/auth/logout" class="flex items-center justify-between w-full p-3 bg-white hover:bg-red-50 text-red-600 text-sm font-bold rounded-xl transition border border-red-50 shadow-sm group">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                    <i class="fa-solid fa-right-from-bracket"></i>
                                                </div>
                                                Đăng xuất
                                            </div>
                                            <i class="fa-solid fa-chevron-right text-xs text-red-200 group-hover:translate-x-1 transition-transform"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo URLROOT; ?>/auth/login" class="text-gray-600 hover:text-primary px-3 py-2 rounded-full text-sm font-bold transition-colors">Đăng nhập</a>
                        <a href="<?php echo URLROOT; ?>/auth/register" class="bg-gradient-to-r from-primary to-secondary text-white hover:shadow-lg hover:shadow-primary/30 px-6 py-2.5 rounded-full text-sm font-bold ml-3 transition-all transform hover:-translate-y-0.5">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
         class="sm:hidden fixed inset-x-0 top-16 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-b border-gray-100 dark:border-slate-800 z-50 shadow-2xl h-[calc(100vh-64px)] overflow-y-auto w-full">
        <div class="px-4 pt-2 pb-6 space-y-1">
            <?php 
                $m_current = $_SERVER['REQUEST_URI'];
                $m_path = trim(str_replace(URLROOT, '', (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $m_current), '/');
                $m_is_home = ($m_path == '' || $m_path == 'home' || $m_path == 'home/index' || $m_path == 'PETSHOP');
                $m_is_product = strpos($m_current, '/product') !== false;
                $m_is_service = strpos($m_current, '/service') !== false;
                $m_is_ai = strpos($m_current, '/ai') !== false;
                $m_active = "bg-indigo-50 dark:bg-indigo-900/30 text-primary dark:text-indigo-400 block px-3 py-3 rounded-xl text-base font-black border-l-4 border-primary";
                $m_inactive = "text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white block px-3 py-3 rounded-xl text-base font-bold transition-colors";
            ?>

            <a href="<?php echo URLROOT; ?>" class="<?php echo $m_is_home ? $m_active : $m_inactive; ?>">
                <i class="fa-solid fa-house w-6 text-center"></i> Trang chủ
            </a>
            <a href="<?php echo URLROOT; ?>/product" class="<?php echo $m_is_product ? $m_active : $m_inactive; ?>">
                <i class="fa-solid fa-store w-6 text-center"></i> Cửa hàng
            </a>
            <a href="<?php echo URLROOT; ?>/service" class="<?php echo $m_is_service ? $m_active : $m_inactive; ?>">
                <i class="fa-solid fa-spa w-6 text-center"></i> Dịch vụ
            </a>
            <a href="<?php echo URLROOT; ?>/ai" class="<?php echo $m_is_ai ? $m_active : $m_inactive; ?>">
                <i class="fa-solid fa-robot w-6 text-center"></i> AI Phân tích
            </a>
            <a href="<?php echo URLROOT; ?>/contact" class="<?php echo (strpos($m_current, '/contact') !== false) ? $m_active : $m_inactive; ?>">
                <i class="fa-solid fa-envelope w-6 text-center"></i> Liên hệ
            </a>
            
            <div class="pt-4 mt-4 border-t border-gray-100 dark:border-slate-800">
                <?php if(isLoggedIn()) : ?>
                    <div class="flex items-center px-3 mb-4">
                        <?php if (!empty($_SESSION['user_avatar'])): ?>
                            <img src="<?php echo $_SESSION['user_avatar']; ?>" alt="Avatar" class="w-10 h-10 rounded-xl object-cover shadow-md">
                        <?php else: ?>
                            <div class="w-10 h-10 bg-gradient-to-tr from-primary to-blue-400 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-md">
                                <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <div class="ml-3">
                            <p class="text-sm font-black text-gray-800 dark:text-white"><?php echo $_SESSION['user_name']; ?></p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase">Hội viên <?php echo $level ?? 'Đồng'; ?></p>
                        </div>
                    </div>
                    <?php if($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'staff' || $_SESSION['user_role'] == 'manager') : ?>
                        <a href="<?php echo URLROOT; ?>/admin" class="block px-3 py-3 rounded-xl text-base font-bold text-blue-600 bg-blue-50 dark:bg-blue-900/30 mb-2">
                            <i class="fa-solid fa-gauge-high w-6 text-center"></i> Trang Quản Trị
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo URLROOT; ?>/order/history" class="block px-3 py-3 rounded-xl text-base font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800">
                        <i class="fa-solid fa-history w-6 text-center"></i> Lịch sử đơn hàng
                    </a>
                    <a href="<?php echo URLROOT; ?>/pet" class="block px-3 py-3 rounded-xl text-base font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800">
                        <i class="fa-solid fa-paw w-6 text-center"></i> Thú cưng của tôi
                    </a>
                    <a href="<?php echo URLROOT; ?>/wishlist" class="block px-3 py-3 rounded-xl text-base font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800">
                        <i class="fa-solid fa-heart w-6 text-center"></i> Yêu thích
                    </a>
                    <a href="<?php echo URLROOT; ?>/auth/logout" class="block px-3 py-3 rounded-xl text-base font-bold text-red-600 bg-red-50 dark:bg-red-900/20 mt-2">
                        <i class="fa-solid fa-right-from-bracket w-6 text-center"></i> Đăng xuất
                    </a>
                <?php else : ?>
                    <div class="grid grid-cols-2 gap-3 px-3">
                        <a href="<?php echo URLROOT; ?>/auth/login" class="block text-center py-3 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-white font-bold rounded-xl">Đăng nhập</a>
                        <a href="<?php echo URLROOT; ?>/auth/register" class="block text-center py-3 bg-primary text-white font-bold rounded-xl shadow-md shadow-primary/30">Đăng ký</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </nav>
    
    <!-- Main Content Container -->
    <main class="min-h-screen">
