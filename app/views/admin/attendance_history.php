<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Bảng Tổng Hợp Chấm Công</h1>
            <p class="text-sm text-gray-500">Xem chi tiết chuyên cần tháng <?php echo $data['current_month']; ?>/<?php echo $data['current_year']; ?></p>
        </div>
        <?php if($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'manager'): ?>
        <a href="<?php echo URLROOT; ?>/admin/attendance" class="bg-indigo-50 text-primary px-4 py-2 rounded-xl font-bold hover:bg-primary hover:text-white transition flex items-center">
            <i class="fa-solid fa-plus-circle mr-2"></i> Chấm công hôm nay
        </a>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
        <form action="<?php echo URLROOT; ?>/admin/attendance_history" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-black text-gray-400 uppercase mb-2">Tháng</label>
                <select name="month" onchange="this.form.submit()" class="w-full px-4 py-2 rounded-xl border border-gray-100 outline-none focus:ring-2 focus:ring-primary/20 bg-gray-50/50">
                    <?php for($i=1; $i<=12; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($i == $data['current_month']) ? 'selected' : ''; ?>>Tháng <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-black text-gray-400 uppercase mb-2">Năm</label>
                <select name="year" onchange="this.form.submit()" class="w-full px-4 py-2 rounded-xl border border-gray-100 outline-none focus:ring-2 focus:ring-primary/20 bg-gray-50/50">
                    <?php for($i=date('Y')-2; $i<=date('Y'); $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($i == $data['current_year']) ? 'selected' : ''; ?>>Năm <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-xl font-black hover:bg-indigo-700 transition shadow-lg shadow-primary/20 flex items-center">
                    <i class="fa-solid fa-rotate mr-2"></i> Làm mới
                </button>
                <button type="button" onclick="window.print()" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-black hover:bg-gray-200 transition flex items-center">
                    <i class="fa-solid fa-print mr-2"></i> In báo cáo
                </button>
            </div>
        </form>
    </div>

    <!-- Matrix Legend -->
    <div class="flex gap-4 mb-4 text-[10px] font-black uppercase">
        <div class="flex items-center gap-1"><span class="w-3 h-3 bg-green-500 rounded-sm"></span> Có mặt</div>
        <div class="flex items-center gap-1"><span class="w-3 h-3 bg-orange-500 rounded-sm"></span> Muộn</div>
        <div class="flex items-center gap-1"><span class="w-3 h-3 bg-red-500 rounded-sm"></span> Vắng</div>
        <div class="flex items-center gap-1"><span class="w-3 h-3 bg-blue-500 rounded-sm"></span> Nghỉ</div>
        <div class="flex items-center gap-1"><span class="w-3 h-3 bg-gray-100 border border-gray-200 rounded-sm"></span> Trống</div>
    </div>

    <!-- Matrix Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="sticky left-0 z-10 bg-gray-50 px-6 py-4 text-left text-xs font-black text-gray-400 border-b border-r border-gray-100 min-w-[200px]">Nhân viên</th>
                    <?php foreach($data['dates'] as $date): ?>
                        <th class="px-2 py-4 text-center text-[10px] font-black text-gray-400 border-b border-gray-100 min-w-[40px]">
                            <?php echo date('d', strtotime($date)); ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['employees'] as $emp): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="sticky left-0 z-10 bg-white px-6 py-4 whitespace-nowrap border-r border-b border-gray-100 shadow-[2px_0_5px_rgba(0,0,0,0.02)]">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-800"><?php echo $emp->fullname; ?></span>
                            <span class="text-[9px] font-black uppercase text-gray-400"><?php echo $emp->role; ?></span>
                        </div>
                    </td>
                    <?php foreach($data['dates'] as $date): ?>
                        <td class="px-1 py-4 text-center border-b border-gray-50">
                            <?php 
                                $status = $data['matrix'][$emp->user_id][$date] ?? null;
                                $color = 'bg-gray-50';
                                if($status == 'present') $color = 'bg-green-500';
                                elseif($status == 'late') $color = 'bg-orange-500';
                                elseif($status == 'absent') $color = 'bg-red-500';
                                elseif($status == 'on_leave') $color = 'bg-blue-500';
                            ?>
                            <div class="w-6 h-6 mx-auto rounded-lg <?php echo $color; ?> shadow-sm transition-transform hover:scale-125 cursor-pointer" 
                                 title="<?php echo date('d/m', strtotime($date)) . ': ' . ($status ? $status : 'Chưa chấm công'); ?>">
                            </div>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Ẩn scrollbar nhưng vẫn cho phép scroll */
    .overflow-x-auto {
        scrollbar-width: thin;
        scrollbar-color: #4f46e5 #f3f4f6;
    }
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f3f4f6;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background-color: #4f46e5;
        border-radius: 20px;
    }
</style>

<?php require APPROOT . '/views/admin/footer.php'; ?>
