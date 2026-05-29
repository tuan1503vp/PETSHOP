<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Quản lý Nhân sự</h1>
            <p class="text-sm text-gray-500">Danh sách bác sĩ và nhân viên cửa hàng</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/employee_add" class="bg-primary text-white px-6 py-3 rounded-2xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-primary/20 flex items-center">
            <i class="fa-solid fa-user-plus mr-2"></i> Thêm Nhân Viên
        </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-primary flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Tổng nhân sự</p>
                <p class="text-2xl font-black text-gray-800"><?php echo count($data['employees']); ?></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Bác sĩ</p>
                <p class="text-2xl font-black text-gray-800">
                    <?php echo count(array_filter($data['employees'], function($e){ return $e->role == 'doctor'; })); ?>
                </p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Thu ngân</p>
                <p class="text-2xl font-black text-gray-800">
                    <?php echo count(array_filter($data['employees'], function($e){ return $e->role == 'cashier'; })); ?>
                </p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-user"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Nhân viên</p>
                <p class="text-2xl font-black text-gray-800">
                    <?php echo count(array_filter($data['employees'], function($e){ return $e->role == 'staff'; })); ?>
                </p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center border-l-4 border-l-purple-500">
            <div class="h-12 w-12 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Quản lý</p>
                <p class="text-2xl font-black text-gray-800">
                    <?php echo count(array_filter($data['employees'], function($e){ return $e->role == 'manager'; })); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Nhân viên</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Mã NV</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Email / Chức vụ</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">CCCD</th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Địa chỉ</th>
                    <th class="px-8 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php if(empty($data['employees'])): ?>
                <tr>
                    <td colspan="6" class="px-8 py-20 text-center text-gray-500 italic">Chưa có nhân viên nào trong danh sách.</td>
                </tr>
                <?php else: ?>
                    <?php foreach($data['employees'] as $emp): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <?php if($emp->image): ?>
                                    <img src="<?php echo URLROOT; ?>/public/images/employees/<?php echo $emp->image; ?>" class="h-10 w-10 rounded-xl object-cover mr-3 border border-gray-100 shadow-sm">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded-xl bg-indigo-50 text-primary flex items-center justify-center font-black text-xs mr-3">
                                        <?php echo strtoupper(substr($emp->fullname, 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                <span class="text-sm font-bold text-gray-900"><?php echo $emp->fullname; ?></span>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <span class="text-xs font-black text-indigo-500 bg-indigo-50 px-2 py-1 rounded-lg"><?php echo $emp->employee_code; ?></span>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-600"><?php echo $emp->email; ?></span>
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
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-600">
                            <?php echo $emp->cccd; ?>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm text-gray-500 line-clamp-1 max-w-xs"><?php echo $emp->address; ?></span>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-right">
                            <div class="flex justify-end gap-3">
                                <form action="<?php echo URLROOT; ?>/admin/employee_delete/<?php echo $emp->id; ?>" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa nhân viên này?')">
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                        <i class="fa-solid fa-trash-can text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
