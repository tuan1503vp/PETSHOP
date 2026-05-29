<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Quản lý Danh mục Dịch vụ</h1>
        <a href="<?php echo URLROOT; ?>/admin/service_add" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Thêm Dịch Vụ Mới
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Dịch Vụ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phân Loại</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Giá Dịch Vụ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(empty($data['services'])): ?>
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Chưa có dịch vụ nào.</td>
                </tr>
                <?php else: ?>
                    <?php 
                        $groupedServices = [];
                        foreach($data['services'] as $service) {
                            $catName = $service->category_name ?? 'Dịch vụ';
                            $groupedServices[$catName][] = $service;
                        }
                    ?>

                    <?php foreach($groupedServices as $categoryName => $services): ?>
                        <!-- Category Header Row -->
                        <tr class="bg-gray-50/50">
                            <td colspan="4" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                <i class="fa-solid fa-folder-open mr-2 text-primary"></i> <?php echo $categoryName; ?> 
                                <span class="ml-2 text-xs font-normal text-gray-400 normal-case">(<?php echo count($services); ?> dịch vụ)</span>
                            </td>
                        </tr>

                        <?php foreach($services as $service): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900"><?php echo $service->name; ?></div>
                                <div class="text-sm text-gray-500 truncate w-48"><?php echo $service->description; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-50 text-blue-800 border border-blue-100">
                                    <?php echo $service->category_name ?? 'Dịch vụ'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-primary">
                                <?php if(strpos(mb_strtolower($service->category_name ?? ''), 'khám') !== false || strpos(mb_strtolower($service->category_name ?? ''), 'chữa') !== false): ?>
                                    <span class="text-gray-500 font-normal italic text-xs">Bác sĩ báo giá sau</span>
                                <?php else: ?>
                                    <?php echo number_format($service->price, 0, ',', '.'); ?> đ
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="<?php echo URLROOT; ?>/admin/service_edit/<?php echo $service->id; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="<?php echo URLROOT; ?>/admin/service_delete/<?php echo $service->id; ?>" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này không?');">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
