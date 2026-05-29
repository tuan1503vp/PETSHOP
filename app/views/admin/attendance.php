<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Chấm Công Nhân Viên</h1>
            <p class="text-sm text-gray-500">Quản lý chuyên cần hàng ngày</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/admin/attendance_history" class="bg-indigo-50 text-primary px-4 py-2 rounded-xl font-bold hover:bg-primary hover:text-white transition flex items-center">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i> Lịch sử
            </a>
            <form action="<?php echo URLROOT; ?>/admin/attendance" method="GET" class="flex items-center gap-2">
                <input type="date" name="date" value="<?php echo $data['current_date']; ?>" 
                       onchange="this.form.submit()"
                       class="px-4 py-2 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-primary/20">
            </form>
        </div>
    </div>

    <?php flash('attendance_success'); ?>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ 
        setAll(status) {
            document.querySelectorAll('input[type=radio][value=' + status + ']').forEach(el => el.checked = true);
        }
    }">
        <div class="p-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Thiết lập nhanh cho tất cả:</span>
            <div class="flex gap-2">
                <button type="button" @click="setAll('present')" class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-[10px] font-black uppercase hover:bg-green-200 transition">Có mặt tất cả</button>
                <button type="button" @click="setAll('absent')" class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-[10px] font-black uppercase hover:bg-red-200 transition">Vắng tất cả</button>
            </div>
        </div>
        <form action="<?php echo URLROOT; ?>/admin/attendance_save" method="POST">
            <input type="hidden" name="date" value="<?php echo $data['current_date']; ?>">
            
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Nhân viên</th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Chức vụ</th>
                        <th class="px-8 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Trạng thái</th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Ghi chú</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php foreach($data['employees'] as $emp): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-50 text-primary flex items-center justify-center font-bold text-xs mr-3">
                                    <?php echo strtoupper(substr($emp->fullname, 0, 1)); ?>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900"><?php echo $emp->fullname; ?></span>
                                    <span class="text-xs text-gray-500"><?php echo $emp->employee_code; ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <span class="text-[10px] font-black uppercase <?php 
                                if($emp->role == 'admin') echo 'text-red-500';
                                elseif($emp->role == 'manager') echo 'text-purple-500';
                                elseif($emp->role == 'doctor') echo 'text-orange-500';
                                elseif($emp->role == 'cashier') echo 'text-green-500';
                                else echo 'text-blue-500';
                            ?>">
                                <?php 
                                    if($emp->role == 'admin') echo 'Hệ thống';
                                    elseif($emp->role == 'manager') echo 'Quản lý';
                                    elseif($emp->role == 'doctor') echo 'Bác sĩ';
                                    elseif($emp->role == 'cashier') echo 'Thu ngân';
                                    else echo 'Nhân viên';
                                ?>
                            </span>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex justify-center gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="status[<?php echo $emp->user_id; ?>]" value="present" 
                                           <?php echo ($emp->status == 'present' || !$emp->status) ? 'checked' : ''; ?> class="hidden peer">
                                    <span class="px-3 py-1 rounded-lg text-xs font-bold border border-gray-100 bg-gray-50 text-gray-400 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 transition">Có mặt</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status[<?php echo $emp->user_id; ?>]" value="late" 
                                           <?php echo ($emp->status == 'late') ? 'checked' : ''; ?> class="hidden peer">
                                    <span class="px-3 py-1 rounded-lg text-xs font-bold border border-gray-100 bg-gray-50 text-gray-400 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition">Muộn</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status[<?php echo $emp->user_id; ?>]" value="absent" 
                                           <?php echo ($emp->status == 'absent') ? 'checked' : ''; ?> class="hidden peer">
                                    <span class="px-3 py-1 rounded-lg text-xs font-bold border border-gray-100 bg-gray-50 text-gray-400 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 transition">Vắng</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status[<?php echo $emp->user_id; ?>]" value="on_leave" 
                                           <?php echo ($emp->status == 'on_leave') ? 'checked' : ''; ?> class="hidden peer">
                                    <span class="px-3 py-1 rounded-lg text-xs font-bold border border-gray-100 bg-gray-50 text-gray-400 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 transition">Nghỉ</span>
                                </label>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <input type="text" name="notes[<?php echo $emp->user_id; ?>]" value="<?php echo $emp->notes; ?>" 
                                   placeholder="Ghi chú..." class="w-full px-3 py-1 text-sm border-b border-gray-100 outline-none focus:border-primary transition">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="p-8 bg-gray-50 flex justify-end">
                <button type="submit" class="bg-primary text-white px-12 py-3 rounded-2xl font-black shadow-lg shadow-primary/20 hover:bg-indigo-700 transition">
                    Lưu Chấm Công
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
