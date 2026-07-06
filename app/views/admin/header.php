<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - PETSHOP</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#ec4899',
                        dark: '#0f172a', /* Sâu hơn một chút (Slate 900) */
                        darker: '#020617' /* Slate 950 */
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.4s ease-out forwards',
                    }
                }
            }
        }
    </script>
    <style>
        /* Ẩn thanh trượt (scrollbar) cho Sidebar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-gray-100 font-sans flex h-screen overflow-hidden" x-data="{ mobileSidebarOpen: false }">
    <!-- Sidebar -->
    <?php 
        $current_url = $_SERVER['REQUEST_URI'];
        $is_admin_home = (trim($current_url, '/') == 'PETSHOP/admin' || strpos($current_url, '/admin/index') !== false);
        $is_pos = strpos($current_url, '/admin/pos') !== false;
        $is_products = strpos($current_url, '/admin/products') !== false;
        $is_orders = strpos($current_url, '/admin/orders') !== false;
        $is_services = strpos($current_url, '/admin/services') !== false;
        $is_customers = strpos($current_url, '/admin/customers') !== false;
        $is_employees = strpos($current_url, '/admin/employees') !== false;
        
        $active_class = "bg-primary/90 text-white font-bold shadow-lg shadow-primary/30 translate-x-1";
        $inactive_class = "hover:bg-white/10 text-gray-400 hover:text-white hover:translate-x-1 transition-all duration-300";

        // Đếm số nhân viên (dùng cho badge sidebar)
        $_sidebar_emp_count = 0;
        if(in_array($_SESSION['user_role'], ['admin','staff','manager'])) {
            $tmpDb = new Database;
            $tmpDb->query('SELECT COUNT(*) as cnt FROM employees');
            $_sidebar_emp_row = $tmpDb->single();
            $_sidebar_emp_count = $_sidebar_emp_row ? $_sidebar_emp_row->cnt : 0;
        }

        // Đếm số dịch vụ chờ thanh toán
        $_sidebar_payment_waiting_count = 0;
        if(in_array($_SESSION['user_role'], ['admin','manager','cashier'])) {
            $tmpDb2 = new Database;
            $tmpDb2->query("SELECT COUNT(*) as cnt FROM appointments WHERE status = 'confirmed' AND final_price IS NOT NULL");
            $_sidebar_payment_row = $tmpDb2->single();
            $_sidebar_payment_waiting_count = $_sidebar_payment_row ? $_sidebar_payment_row->cnt : 0;
        }

        // Đếm số đơn hàng và dịch vụ chờ xác nhận
        $_sidebar_pending_order_count = 0;
        $_sidebar_pending_appt_count = 0;
        if(in_array($_SESSION['user_role'], ['admin','manager'])) {
            $tmpDb3 = new Database;
            $tmpDb3->query("SELECT 
                (SELECT COUNT(*) FROM orders WHERE status = 'pending') as o_cnt,
                (SELECT COUNT(*) FROM appointments WHERE status = 'pending') as a_cnt
            ");
            $_sidebar_pending_row = $tmpDb3->single();
            if ($_sidebar_pending_row) {
                $_sidebar_pending_order_count = $_sidebar_pending_row->o_cnt;
                $_sidebar_pending_appt_count = $_sidebar_pending_row->a_cnt;
            }
        }
    ?>
    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 md:hidden transition-opacity duration-300">
    </div>

    <!-- Sidebar Navigation -->
    <aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
           class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-dark to-darker text-white flex flex-col h-full transform md:transform-none md:relative transition-transform duration-300 ease-in-out shadow-xl z-40 md:z-20">
        <div class="p-6 flex items-center justify-between border-b border-white/5">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center shadow-lg shadow-primary/20 mr-3">
                    <i class="fa-solid fa-paw text-white text-xl"></i>
                </div>
                <span class="text-xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">PETSHOP</span>
            </div>
            <button @click="mobileSidebarOpen = false" class="text-gray-400 hover:text-white md:hidden focus:outline-none">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-4 flex-1 overflow-y-auto no-scrollbar">
            <ul class="space-y-2">
                <?php if(in_array($_SESSION['user_role'], ['admin', 'manager'])): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin" class="flex items-center p-3 rounded-lg <?php echo $is_admin_home ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-chart-line w-6"></i>
                        <span>Tổng quan</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($_SESSION['user_role'] == 'cashier'): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/pos" class="flex items-center p-3 rounded-lg <?php echo $is_pos ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-cash-register w-6"></i>
                        <span>POS Bán Hàng</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(!in_array($_SESSION['user_role'], ['doctor', 'cashier', 'manager', 'staff'])): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/products" class="flex items-center p-3 rounded-lg <?php echo $is_products ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-box w-6"></i>
                        <span>Sản phẩm</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($_SESSION['user_role'], ['admin', 'manager'])): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/orders" class="flex items-center p-3 rounded-lg <?php echo $is_orders ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-file-invoice-dollar w-6"></i>
                        <span class="flex-1">Đơn hàng</span>
                        <span class="badge-pending-order ml-2 text-[10px] font-black bg-orange-500 text-white px-2 py-0.5 rounded-full animate-pulse shadow-md shadow-orange-500/50" style="<?php echo $_sidebar_pending_order_count > 0 ? 'display: inline-flex;' : 'display: none;'; ?>"><?php echo $_sidebar_pending_order_count; ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($_SESSION['user_role'] == 'manager'): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/service_list" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/service_') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-list-check w-6"></i>
                        <span>Danh mục Dịch vụ</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($_SESSION['user_role'], ['admin', 'manager', 'doctor'])): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/services" class="flex items-center p-3 rounded-lg <?php echo ($is_services && strpos($current_url, '/admin/service_') === false) ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-calendar-check w-6"></i>
                        <span class="flex-1">Lịch Hẹn Dịch vụ</span>
                        <?php if(($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'manager') && $_sidebar_payment_waiting_count > 0): ?>
                        <span class="ml-2 text-[10px] font-black bg-red-500 text-white px-2 py-0.5 rounded-full animate-pulse shadow-md shadow-red-500/50"><?php echo $_sidebar_payment_waiting_count; ?></span>
                        <?php endif; ?>
                        <span class="badge-pending-appt ml-2 text-[10px] font-black bg-orange-500 text-white px-2 py-0.5 rounded-full animate-pulse shadow-md shadow-orange-500/50" style="<?php echo $_sidebar_pending_appt_count > 0 ? 'display: inline-flex;' : 'display: none;'; ?>"><?php echo $_sidebar_pending_appt_count; ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($_SESSION['user_role'], ['admin', 'doctor'])): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/medical_report" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/medical_report') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-staff-snake w-6 text-emerald-400"></i>
                        <span>Báo cáo Y tế</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($_SESSION['user_role'], ['admin', 'manager'])): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/payment_history" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/payment_history') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-history w-6"></i>
                        <span>Lịch sử thanh toán</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($_SESSION['user_role'], ['manager', 'cashier'])): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/boarding" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/boarding') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-house-chimney-window w-6"></i>
                        <span>Bảng trông giữ</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(!in_array($_SESSION['user_role'], ['doctor', 'cashier', 'manager', 'staff'])): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/customers" class="flex items-center p-3 rounded-lg <?php echo $is_customers ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-users w-6"></i>
                        <span>Khách hàng</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/membership_benefits" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/membership_benefits') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-gift w-6 text-pink-400"></i>
                        <span>Ưu đãi Hội viên</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($_SESSION['user_role'], ['admin', 'manager'])): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/vouchers" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/vouchers') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-ticket w-6 text-emerald-400"></i>
                        <span>Quản lý Voucher</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($_SESSION['user_role'], ['admin', 'manager', 'doctor', 'staff'])): ?>
                <?php $is_admin_pets = strpos($current_url, '/admin/pets') !== false || strpos($current_url, '/admin/pet_') !== false; ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/pets" class="flex items-center p-3 rounded-lg <?php echo $is_admin_pets ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-paw w-6"></i>
                        <span>Quản lý Thú cưng</span>
                    </a>
                </li>
                <?php endif; ?>


                <?php if($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'manager'): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/employees" class="flex items-center p-3 rounded-lg <?php echo $is_employees ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-user-tie w-6"></i>
                        <span class="flex-1">Nhân sự</span>
                        <?php if($_sidebar_emp_count > 0): ?>
                        <span class="ml-2 text-[10px] font-black bg-white/20 text-white px-2 py-0.5 rounded-full"><?php echo $_sidebar_emp_count; ?></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <?php if($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'manager'): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/contacts" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/contacts') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-envelope-open-text w-6"></i>
                        <span>Hộp thư liên hệ</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($_SESSION['user_role'] == 'admin'): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/activity_logs" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/activity_logs') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-clock-rotate-left w-6"></i>
                        <span>Nhật ký hành vi</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($_SESSION['user_role'] == 'manager'): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/inventory" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/inventory') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-warehouse w-6"></i>
                        <span>Kho hàng</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/attendance" class="flex items-center p-3 rounded-lg <?php echo (strpos($current_url, '/admin/attendance') !== false && strpos($current_url, '_history') === false) ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-clipboard-user w-6"></i>
                        <span>Chấm công</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/attendance_history" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/attendance_history') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-table-list w-6"></i>
                        <span>Lịch sử Chấm công</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/payroll" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/payroll') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-money-check-dollar w-6"></i>
                        <span>Bảng lương</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($_SESSION['user_role'] == 'admin'): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/attendance_history" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/attendance_history') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-table-list w-6"></i>
                        <span>Lịch sử Chấm công</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/payroll" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/payroll') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-money-check-dollar w-6"></i>
                        <span>Báo cáo Bảng lương</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($_SESSION['user_role'] == 'staff'): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin/personal_report" class="flex items-center p-3 rounded-lg <?php echo strpos($current_url, '/admin/personal_report') !== false ? $active_class : $inactive_class; ?>">
                        <i class="fa-solid fa-file-invoice w-6"></i>
                        <span>Báo cáo cá nhân</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="p-4 border-t border-white/5">
            <div class="flex items-center justify-between group cursor-pointer hover:bg-white/5 p-2 rounded-xl transition-all duration-300">
                <div class="flex items-center">
                    <?php if (!empty($_SESSION['user_avatar'])): ?>
                        <img src="<?php echo $_SESSION['user_avatar']; ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover shadow-md border-2 border-white/10">
                    <?php else: ?>
                        <div class="w-10 h-10 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full flex justify-center items-center font-black text-white shadow-md border-2 border-white/10">
                            <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-white group-hover:text-primary transition"><?php echo $_SESSION['user_name']; ?></p>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest"><?php echo $_SESSION['user_role']; ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?php echo URLROOT; ?>/admin/profile" class="text-gray-500 hover:text-white transition" title="Cài đặt tài khoản">
                        <i class="fa-solid fa-gear"></i>
                    </a>
                    <a href="<?php echo URLROOT; ?>/auth/logout" class="text-gray-500 hover:text-red-400 transition" title="Đăng xuất">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full overflow-hidden bg-[#f8fafc] animate-fade-in">
        <!-- Top header for mobile / Breadcrumbs -->
        <header class="bg-white/80 backdrop-blur-md shadow-sm z-10 flex items-center justify-between p-4 lg:px-8 h-16 border-b border-gray-100/50 sticky top-0">
            <div class="flex items-center md:hidden">
                <button @click="mobileSidebarOpen = true" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <span class="ml-4 text-lg font-bold text-gray-900">PETSHOP</span>
            </div>
            <div class="hidden md:block">
                <?php if($_SESSION['user_role'] == 'cashier'): ?>
                <span class="text-sm font-bold text-gray-500 uppercase tracking-widest"><i class="fa-solid fa-cash-register mr-1"></i> QUẦY THU NGÂN</span>
                <?php elseif($_SESSION['user_role'] == 'staff'): ?>
                <span class="text-sm font-bold text-gray-500 uppercase tracking-widest"><i class="fa-solid fa-user-nurse mr-1"></i> NHÂN VIÊN CHĂM SÓC</span>
                <?php elseif(!in_array($_SESSION['user_role'], ['manager', 'admin', 'doctor', 'cashier', 'staff'])): ?>
                <a href="<?php echo URLROOT; ?>" class="text-sm text-gray-500 hover:text-primary">
                    <i class="fa-solid fa-external-link-alt mr-1"></i> Trở về trang chủ Website
                </a>
                <?php endif; ?>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-1 overflow-x-hidden <?php echo $is_pos ? 'overflow-hidden' : 'overflow-y-auto'; ?> bg-gray-100">
