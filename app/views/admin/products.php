<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Quản lý Sản phẩm</h1>
        <a href="<?php echo URLROOT; ?>/admin/product_add" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Thêm sản phẩm mới
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php 
                    $groupedProducts = [];
                    foreach($data['products'] as $product) {
                        $catName = $product->category_name ?? 'Chưa phân loại';
                        $groupedProducts[$catName][] = $product;
                    }
                ?>

                <?php foreach($groupedProducts as $categoryName => $products): ?>
                    <!-- Category Header Row -->
                    <tr class="bg-gray-50/50">
                        <td colspan="5" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                            <i class="fa-solid fa-folder-open mr-2 text-primary"></i> <?php echo $categoryName; ?> 
                            <span class="ml-2 text-xs font-normal text-gray-400 normal-case">(<?php echo count($products); ?> sản phẩm)</span>
                        </td>
                    </tr>

                    <?php foreach($products as $product): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img class="h-12 w-12 rounded-lg object-cover border border-gray-100" src="<?php echo !empty($product->image) ? URLROOT . '/public/images/' . $product->image : 'https://placehold.co/100x100?text=' . urlencode($product->name); ?>" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900"><?php echo $product->name; ?></div>
                                    <div class="text-xs text-gray-500 truncate max-w-[200px]"><?php echo $product->description; ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-indigo-50 text-primary border border-indigo-100">
                                <?php echo $product->category_name ?? 'Chưa phân loại'; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-black">
                            <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm <?php echo $product->stock_quantity <= 5 ? 'text-red-500 font-bold' : 'text-gray-600'; ?>">
                                <?php echo $product->stock_quantity; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="<?php echo URLROOT; ?>/admin/product_edit/<?php echo $product->id; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="<?php echo URLROOT; ?>/admin/product_delete/<?php echo $product->id; ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
