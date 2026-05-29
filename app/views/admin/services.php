<?php require APPROOT . '/views/admin/header.php'; ?>
<style>[x-cloak] { display: none !important; }</style>

<div class="p-6" x-data="{ showModal: false, selectedApp: null, availableDoctors: [], loadingDoctors: false, requiredRoleLabel: '',
    openModal(app) { this.selectedApp = app; this.showModal = true; this.availableDoctors = []; },
    async fetchDoctors() { 
        this.loadingDoctors = true; 
        try { 
            let role = (this.selectedApp.category_name && this.selectedApp.category_name.toLowerCase().includes('khám')) ? 'doctor' : 'staff';
            this.requiredRoleLabel = role === 'doctor' ? 'Bác sĩ' : 'Nhân viên';
            const r = await fetch(`<?php echo URLROOT; ?>/admin/get_available_staff?date=${this.selectedApp.appointment_date}&time=${this.selectedApp.appointment_time}&role=${role}`); 
            this.availableDoctors = await r.json(); 
        } catch(e){} finally { this.loadingDoctors = false; } 
    }
}">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800"><?php echo ($data['is_doctor_view'] ?? false) ? 'Lịch Dịch Vụ Của Tôi' : 'Lịch Đặt Dịch Vụ'; ?></h1>
            <p class="text-sm text-gray-500"><?php echo ($data['is_doctor_view'] ?? false) ? 'Các dịch vụ đang chờ và đã nhận' : 'Quản lý và theo dõi các lịch hẹn'; ?></p>
        </div>
    </div>

    <?php flash('service_success'); ?>
    <?php flash('admin_error'); ?>

    <?php if($data['is_doctor_view'] ?? false): ?>
    <!-- DOCTOR VIEW: KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <?php
            $pending = array_filter($data['appointments'], function($a){ return empty($a->doctor_id); });
            $myActive = array_filter($data['appointments'], function($a){ return !empty($a->doctor_id); });
            $myCompleted = $data['completed_appointments'] ?? [];
        ?>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl mr-4"><i class="fa-solid fa-clock"></i></div>
            <div><p class="text-xs font-black text-gray-400 uppercase tracking-widest">Chờ nhận</p><p class="text-2xl font-black text-gray-800"><?php echo count($pending); ?></p></div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl mr-4"><i class="fa-solid fa-user-check"></i></div>
            <div><p class="text-xs font-black text-gray-400 uppercase tracking-widest">Đang thực hiện</p><p class="text-2xl font-black text-gray-800"><?php echo count($myActive); ?></p></div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-xl mr-4"><i class="fa-solid fa-circle-check"></i></div>
            <div><p class="text-xs font-black text-gray-400 uppercase tracking-widest">Đã hoàn thành</p><p class="text-2xl font-black text-gray-800"><?php echo count($myCompleted); ?></p></div>
        </div>
    </div>

    <!-- Biểu đồ thời gian làm việc & rảnh rỗi (Hôm nay) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8 flex flex-col md:flex-row items-center gap-8">
        <div class="w-full md:w-1/3 flex justify-center">
            <div class="w-48 h-48 relative">
                <canvas id="timeChart"></canvas>
            </div>
        </div>
        <div class="w-full md:w-2/3">
            <h3 class="text-lg font-black text-gray-800 mb-2">Thống kê thời gian hôm nay</h3>
            <p class="text-sm text-gray-500 mb-4">Dựa trên các dịch vụ bạn đã nhận và hoàn thành trong ngày hôm nay. (Tiêu chuẩn: 8 giờ/ngày)</p>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-xl bg-indigo-50 text-indigo-700">
                    <span class="font-bold text-sm"><i class="fa-solid fa-briefcase-medical mr-2"></i> Thời gian làm dịch vụ</span>
                    <span class="font-black"><?php echo floor($data['work_minutes'] / 60); ?>h <?php echo $data['work_minutes'] % 60; ?>m</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 text-gray-600">
                    <span class="font-bold text-sm"><i class="fa-solid fa-mug-hot mr-2"></i> Thời gian rảnh</span>
                    <span class="font-black"><?php echo floor($data['free_minutes'] / 60); ?>h <?php echo $data['free_minutes'] % 60; ?>m</span>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('timeChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Làm việc', 'Rảnh rỗi'],
                    datasets: [{
                        data: [<?php echo $data['work_minutes']; ?>, <?php echo $data['free_minutes']; ?>],
                        backgroundColor: ['#4f46e5', '#e5e7eb'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    let mins = context.raw;
                                    let h = Math.floor(mins / 60);
                                    let m = mins % 60;
                                    label += h + 'h ' + m + 'm';
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    <?php endif; ?>

    <!-- BẢNG LỊCH HẸN ĐANG HOẠT ĐỘNG -->
    <?php if($data['is_doctor_view'] ?? false): ?>
    <h2 class="text-lg font-black text-gray-700 mb-4 flex items-center gap-2"><i class="fa-solid fa-list-check text-primary"></i> Lịch hẹn chờ xử lý & đang thực hiện</h2>
    <?php endif; ?>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Mã</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Khách hàng</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Dịch vụ</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Thú cưng</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Thời gian</th>
                    <?php if($_SESSION['user_role'] == 'cashier' || $_SESSION['user_role'] == 'admin'): ?>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Thành tiền</th>
                    <?php endif; ?>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Trạng thái</th>
                    <?php if($data['is_doctor_view'] ?? false): ?>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Nhận yêu cầu</th>
                    <?php elseif($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'manager' || $_SESSION['user_role'] == 'staff'): ?>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Bác sĩ / NV phụ trách</th>
                    <?php endif; ?>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php if(empty($data['appointments'])): ?>
                <tr><td colspan="10" class="px-6 py-16 text-center"><i class="fa-solid fa-calendar-xmark text-4xl text-gray-200 mb-3 block"></i><p class="text-gray-400">Không có lịch hẹn nào</p></td></tr>
                <?php else: ?>
                    <?php foreach($data['appointments'] as $app): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-5"><span class="text-xs font-black text-gray-400">#<?php echo str_pad($app->id, 5, '0', STR_PAD_LEFT); ?></span></td>
                        <td class="px-6 py-5">
                            <div class="flex items-center"><div class="h-8 w-8 rounded-full bg-indigo-50 text-primary flex items-center justify-center font-bold text-xs mr-3"><?php echo strtoupper(substr($app->customer_name, 0, 1)); ?></div><span class="text-sm font-bold text-gray-700"><?php echo $app->customer_name; ?></span></div>
                        </td>
                        <td class="px-6 py-5"><span class="text-sm font-medium text-gray-900"><?php echo $app->service_name; ?></span></td>
                        <td class="px-6 py-5"><span class="text-sm text-gray-700"><?php echo $app->pet_name ?? 'N/A'; ?></span><br><span class="text-[10px] text-gray-400 uppercase font-bold"><?php echo $app->pet_species ?? ''; ?></span></td>
                        <td class="px-6 py-5"><span class="text-sm font-black text-primary"><?php echo date('H:i', strtotime($app->appointment_time)); ?></span><br><span class="text-xs text-gray-500"><?php echo date('d/m/Y', strtotime($app->appointment_date)); ?></span></td>
                        <?php if($_SESSION['user_role'] == 'cashier' || $_SESSION['user_role'] == 'admin'): ?>
                        <td class="px-6 py-5 text-right">
                            <?php if(!empty($app->final_price)): ?>
                                <span class="text-sm font-bold text-green-600"><?php echo number_format($app->final_price, 0, ',', '.'); ?> đ</span>
                            <?php else: ?>
                                <span class="text-xs text-gray-400 italic">Chưa báo giá</span>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                        <td class="px-6 py-5">
                            <?php
                                $sClass = 'bg-yellow-50 text-yellow-600 border-yellow-100';
                                $sLabel = 'Chờ xác nhận';
                                if($app->status=='confirmed'){
                                    if(!empty($app->final_price)) {
                                        $sClass='bg-red-50 text-red-600 border-red-100 animate-pulse shadow-sm shadow-red-500/20'; 
                                        $sLabel='Chờ thanh toán';
                                    } else {
                                        $sClass='bg-indigo-50 text-indigo-600 border-indigo-100'; 
                                        $sLabel='Đã xác nhận';
                                    }
                                }
                                elseif($app->status=='completed'){ $sClass='bg-green-50 text-green-600 border-green-100'; $sLabel='Hoàn thành'; }
                            ?>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?php echo $sClass; ?> border"><?php echo $sLabel; ?></span>
                        </td>
                        <?php if($data['is_doctor_view'] ?? false): ?>
                        <td class="px-6 py-5 text-center">
                            <?php if($app->status == 'pending' && empty($app->doctor_id)): ?>
                                <?php 
                                    $slotKey = $app->appointment_date . '_' . $app->appointment_time;
                                    $isBusy = in_array($slotKey, $data['busy_slots'] ?? []);
                                ?>
                                <?php if($isBusy): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-2 rounded-xl bg-red-50 border border-red-200 text-red-500 text-xs font-bold">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Trùng lịch
                                    </span>
                                <?php else: ?>
                                    <a href="<?php echo URLROOT; ?>/admin/appointment_take/<?php echo $app->id; ?>" 
                                       class="bg-indigo-600 text-white px-4 py-2 rounded-xl font-bold text-xs hover:bg-indigo-700 transition inline-flex items-center gap-1 shadow-md shadow-indigo-500/20"
                                       onclick="return confirm('Bạn xác nhận nhận yêu cầu dịch vụ #<?php echo str_pad($app->id, 5, '0', STR_PAD_LEFT); ?>?')">
                                        <i class="fa-solid fa-hand"></i> Nhận yêu cầu
                                    </a>
                                <?php endif; ?>
                            <?php elseif(!empty($app->doctor_id) && $app->doctor_id == $_SESSION['user_id']): ?>
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-green-600"><i class="fa-solid fa-circle-check"></i> Bạn đã nhận</span>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                        <?php if(!($data['is_doctor_view'] ?? false) && in_array($_SESSION['user_role'], ['admin','manager','staff'])): ?>
                        <td class="px-6 py-5">
                            <?php if(!empty($app->doctor_name)): ?>
                                <div class="flex items-center gap-2">
                                    <div class="h-7 w-7 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold">
                                        <?php echo strtoupper(substr($app->doctor_name, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">BS. <?php echo $app->doctor_name; ?></p>
                                        <p class="text-[10px] text-green-500 font-bold"><i class="fa-solid fa-circle text-[6px] mr-1"></i>Đã nhận lịch</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-xs text-orange-500 font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-clock"></i> Chưa phân công
                                </span>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                        <td class="px-6 py-5 text-right space-x-1">
                            <?php if(($_SESSION['user_role'] == 'cashier' || $_SESSION['user_role'] == 'admin') && $app->status == 'confirmed' && !empty($app->final_price)): ?>
                            <a href="<?php echo URLROOT; ?>/admin/appointment_pay/<?php echo $app->id; ?>" class="bg-green-600 text-white px-3 py-1 rounded-lg font-bold text-[10px] uppercase hover:bg-green-700 transition inline-block"><i class="fa-solid fa-money-check-dollar mr-1"></i>Thanh toán</a>
                            <?php endif; ?>
                            <?php if($_SESSION['user_role'] != 'cashier'): ?>
                            <button type="button" @click="openModal(<?php echo htmlspecialchars(json_encode($app)); ?>)" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">
                                <?php 
                                    if ($_SESSION['user_role'] == 'manager') {
                                        echo 'Xem';
                                    } elseif ($_SESSION['user_role'] == 'doctor' || $_SESSION['user_role'] == 'staff') {
                                        echo 'Xác nhận hoàn thành';
                                    } else {
                                        echo 'Phân công';
                                    }
                                ?>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if(!($data['is_doctor_view'] ?? false) && in_array($_SESSION['user_role'], ['admin','manager','staff'])): ?>
    <!-- LỊCH BẬN CỦA BÁC SĨ / NHÂN VIÊN -->
    <h2 class="text-lg font-black text-gray-700 mb-4 flex items-center gap-2 mt-8">
        <i class="fa-solid fa-calendar-days text-orange-500"></i> Lịch Bác sĩ / Nhân viên
    </h2>
    <?php if(empty($data['staff_schedules'])): ?>
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center mb-8">
        <i class="fa-solid fa-calendar-check text-4xl text-gray-200 mb-3"></i>
        <p class="text-gray-400 font-medium">Tất cả bác sĩ / nhân viên đều đang rảnh</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <?php
            $grouped = [];
            foreach ($data['staff_schedules'] as $sch) {
                $grouped[$sch->staff_id]['name'] = $sch->staff_name;
                $grouped[$sch->staff_id]['role'] = $sch->staff_role;
                $grouped[$sch->staff_id]['slots'][] = $sch;
            }
        ?>
        <?php foreach ($grouped as $staffId => $staff): ?>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 flex items-center gap-3 border-b border-gray-100 <?php echo $staff['role'] == 'doctor' ? 'bg-blue-50/50' : 'bg-orange-50/50'; ?>">
                <div class="h-10 w-10 rounded-xl <?php echo $staff['role'] == 'doctor' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600'; ?> flex items-center justify-center font-bold text-sm">
                    <?php echo strtoupper(substr($staff['name'], 0, 1)); ?>
                </div>
                <div>
                    <p class="text-sm font-black text-gray-800"><?php echo $staff['role'] == 'doctor' ? 'BS.' : ''; ?> <?php echo $staff['name']; ?></p>
                    <p class="text-[10px] font-bold uppercase tracking-widest <?php echo $staff['role'] == 'doctor' ? 'text-blue-400' : 'text-orange-400'; ?>">
                        <?php echo $staff['role'] == 'doctor' ? 'Bác sĩ' : 'Nhân viên'; ?> · <?php echo count($staff['slots']); ?> lịch hẹn
                    </p>
                </div>
            </div>
            <div class="p-4 space-y-2 max-h-[250px] overflow-y-auto">
                <?php foreach ($staff['slots'] as $slot): ?>
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/80 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="text-center min-w-[48px]">
                            <p class="text-sm font-black text-primary"><?php echo date('H:i', strtotime($slot->appointment_time)); ?></p>
                            <p class="text-[10px] text-gray-400 font-bold"><?php echo date('d/m', strtotime($slot->appointment_date)); ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-800"><?php echo $slot->service_name; ?></p>
                            <p class="text-[10px] text-gray-400">KH: <?php echo $slot->customer_name; ?></p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase <?php echo $slot->status == 'confirmed' ? 'bg-indigo-50 text-indigo-500' : 'bg-yellow-50 text-yellow-500'; ?>">
                        <?php echo $slot->status == 'confirmed' ? 'Xác nhận' : 'Chờ'; ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    <?php if(($data['is_doctor_view'] ?? false) && !empty($data['completed_appointments'])): ?>
    <!-- BẢNG LỊCH SỬ ĐÃ HOÀN THÀNH CỦA BÁC SĨ -->
    <h2 class="text-lg font-black text-gray-700 mb-4 flex items-center gap-2"><i class="fa-solid fa-clipboard-check text-green-500"></i> Lịch sử dịch vụ đã hoàn thành</h2>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-green-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Mã</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Khách hàng</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Dịch vụ</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Thú cưng</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Ngày thực hiện</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Chi tiết</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php foreach($data['completed_appointments'] as $app): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-5"><span class="text-xs font-black text-gray-400">#<?php echo str_pad($app->id, 5, '0', STR_PAD_LEFT); ?></span></td>
                    <td class="px-6 py-5"><span class="text-sm font-bold text-gray-700"><?php echo $app->customer_name; ?></span></td>
                    <td class="px-6 py-5"><span class="text-sm text-gray-900"><?php echo $app->service_name; ?></span></td>
                    <td class="px-6 py-5"><span class="text-sm text-gray-700"><?php echo $app->pet_name ?? 'N/A'; ?></span></td>
                    <td class="px-6 py-5"><span class="text-sm text-gray-600"><?php echo date('d/m/Y H:i', strtotime($app->appointment_date . ' ' . $app->appointment_time)); ?></span></td>
                    <td class="px-6 py-5 text-right">
                        <button type="button" @click="openModal(<?php echo htmlspecialchars(json_encode($app)); ?>)" class="text-green-600 hover:text-green-800 font-bold text-sm">Xem</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Modal Chi tiết -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl" @click.away="showModal = false">
            <div class="px-8 py-6 bg-dark text-white flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black">Chi Tiết Lịch Hẹn</h2>
                    <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-bold">Mã: <span x-text="'#APP-' + selectedApp?.id?.toString().padStart(5, '0')"></span></p>
                </div>
                <button @click="showModal = false" class="text-gray-400 hover:text-white transition"><i class="fa-solid fa-xmark text-2xl"></i></button>
            </div>
            <div class="p-8 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3"><i class="fa-solid fa-user-tag mr-1 text-primary"></i> Khách hàng</h4>
                        <p class="text-sm font-bold text-gray-800" x-text="selectedApp?.customer_name"></p>
                        <p class="text-xs text-gray-500 mt-1" x-text="selectedApp?.customer_email"></p>
                        <p class="text-xs text-gray-500" x-text="selectedApp?.customer_phone || 'Chưa có SĐT'"></p>
                    </div>
                    <div class="p-5 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                        <h4 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3"><i class="fa-solid fa-paw mr-1"></i> Thú cưng</h4>
                        <p class="text-sm font-bold text-gray-800" x-text="selectedApp?.pet_name || 'Chưa xác định'"></p>
                        <p class="text-xs text-gray-500" x-text="selectedApp?.pet_species || ''"></p>
                    </div>
                </div>
                <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 mb-6">
                    <div class="flex items-center justify-between">
                        <div><p class="text-[10px] text-gray-400 font-bold uppercase">Dịch vụ</p><p class="text-sm font-black text-gray-900" x-text="selectedApp?.service_name"></p></div>
                        <div class="text-right"><p class="text-[10px] text-gray-400 font-bold uppercase">Thời gian</p><p class="text-sm font-black text-primary" x-text="selectedApp?.appointment_time + ' | ' + new Date(selectedApp?.appointment_date).toLocaleDateString('vi-VN')"></p></div>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 rounded-2xl border-l-4 border-primary text-sm text-gray-600 italic mb-6" x-text="selectedApp?.notes || 'Không có ghi chú'"></div>

                <!-- Phân công bác sĩ (admin/manager) -->
                <div class="p-5 bg-orange-50 rounded-2xl border border-orange-100">
                    <div class="flex items-center gap-3 mb-3">
                        <i class="fa-solid fa-user-doctor text-orange-500 text-lg"></i>
                        <div><p class="text-[10px] text-orange-400 font-bold uppercase">Người thực hiện</p><p class="text-sm font-black text-gray-900" x-text="selectedApp?.doctor_name || 'Chưa phân công'"></p></div>
                    </div>

                    <?php if($_SESSION['user_role'] == 'doctor'): ?>
                    <template x-if="!selectedApp?.doctor_name && selectedApp?.status === 'pending'">
                        <a :href="'<?php echo URLROOT; ?>/admin/appointment_take/' + selectedApp?.id" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 transition inline-flex items-center mt-2"><i class="fa-solid fa-user-check mr-2"></i> Nhận dịch vụ này</a>
                    </template>
                    <?php elseif($_SESSION['user_role'] != 'cashier'): ?>
                    <template x-if="!selectedApp?.doctor_name && availableDoctors.length === 0">
                        <button @click="fetchDoctors()" class="bg-orange-500 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-orange-600 transition inline-flex items-center mt-2"><i class="fa-solid fa-search mr-2"></i> Kiểm tra <span x-text="requiredRoleLabel" class="mx-1"></span> rảnh</button>
                    </template>
                    <?php endif; ?>

                    <?php if($_SESSION['user_role'] != 'doctor'): ?>
                    <template x-if="availableDoctors.length > 0">
                        <form action="<?php echo URLROOT; ?>/admin/appointment_assign" method="POST" class="mt-3 pt-3 border-t border-orange-100">
                            <input type="hidden" name="appointment_id" :value="selectedApp?.id">
                            <div class="flex gap-2">
                                <select name="doctor_id" required class="flex-1 px-3 py-2 rounded-xl border border-orange-100 text-sm">
                                    <template x-for="doc in availableDoctors" :key="doc.id"><option :value="doc.id" x-text="(requiredRoleLabel == 'Bác sĩ' ? 'BS. ' : 'NV. ') + doc.fullname"></option></template>
                                </select>
                                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-orange-600 transition">Phân công</button>
                            </div>
                        </form>
                    </template>
                    <template x-if="loadingDoctors"><p class="text-xs text-orange-400 animate-pulse font-bold mt-2">Đang tìm...</p></template>
                    <?php endif; ?>

                    <!-- Form báo giá cho Bác sĩ / Nhân viên -->
                    <?php if(in_array($_SESSION['user_role'], ['doctor', 'staff'])): ?>
                    <template x-if="selectedApp?.status === 'confirmed' && selectedApp?.doctor_id == <?php echo $_SESSION['user_id']; ?> && !selectedApp?.final_price">
                        <form action="<?php echo URLROOT; ?>/admin/appointment_set_price" method="POST" class="mt-3 pt-3 border-t border-orange-100">
                            <input type="hidden" name="appointment_id" :value="selectedApp?.id">
                            <p class="text-[10px] text-orange-400 font-bold uppercase mb-2">Nhập Thành Tiền (Báo giá / Xác nhận hoàn thành)</p>
                            <div class="flex gap-2">
                                <input type="number" name="final_price" required :value="selectedApp?.service_price" placeholder="VD: 500000" class="flex-1 px-3 py-2 rounded-xl border border-orange-100 text-sm">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 transition">Xác nhận Hoàn thành</button>
                            </div>
                        </form>
                    </template>
                    <?php endif; ?>

                    <!-- Hiển thị báo giá nếu đã có -->
                    <template x-if="selectedApp?.final_price">
                        <div class="mt-3 pt-3 border-t border-orange-100 flex items-center justify-between">
                            <p class="text-[10px] text-green-500 font-bold uppercase">Thành tiền (Đã báo giá)</p>
                            <p class="text-lg font-black text-green-600" x-text="new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(selectedApp?.final_price)"></p>
                        </div>
                    </template>
                </div>
            </div>
            <div class="px-8 py-5 bg-gray-50 flex justify-end gap-3">
                <button @click="showModal = false" class="px-6 py-2 rounded-xl text-sm font-bold text-gray-500 hover:text-gray-800 transition">Đóng</button>
                <?php if($_SESSION['user_role'] == 'cashier' || $_SESSION['user_role'] == 'admin'): ?>
                <template x-if="selectedApp?.status === 'confirmed' && selectedApp?.final_price">
                    <a :href="'<?php echo URLROOT; ?>/admin/appointment_pay/' + selectedApp?.id" class="px-6 py-2 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition inline-flex items-center"><i class="fa-solid fa-money-check-dollar mr-2"></i> Thanh toán</a>
                </template>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
