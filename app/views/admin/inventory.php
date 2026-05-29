<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Quản Lý Kho Hàng</h1>
            <p class="text-sm text-gray-500">Cập nhật số lượng tồn kho theo danh mục sản phẩm</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/admin/product_add" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20 flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Thêm sản phẩm
            </a>
        </div>
    </div>

    <!-- Inventory Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-primary flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Tổng sản phẩm</p>
                <p class="text-2xl font-black text-gray-800">
                    <?php 
                        $totalCount = 0;
                        foreach($data['groupedProducts'] as $cat) $totalCount += count($cat);
                        echo $totalCount;
                    ?>
                </p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-400 flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Số danh mục</p>
                <p class="text-2xl font-black text-gray-800"><?php echo count($data['groupedProducts']); ?></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-red-100 shadow-sm flex items-center">
            <div class="h-12 w-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Sắp hết hàng</p>
                <p class="text-2xl font-black text-red-600">
                    <?php 
                        $lowStockCount = 0;
                        foreach($data['groupedProducts'] as $cat) {
                            foreach($cat as $p) if($p->stock_quantity < 10) $lowStockCount++;
                        }
                        echo $lowStockCount;
                    ?>
                </p>
            </div>
        </div>
    </div>

    <?php flash('inventory_success'); ?>

    <form action="<?php echo URLROOT; ?>/admin/inventory_update" method="POST">
        <?php foreach($data['groupedProducts'] as $catName => $products): ?>
        <div class="mb-10">
            <h2 class="text-lg font-black text-indigo-600 mb-4 flex items-center">
                <i class="fa-solid fa-folder-open mr-2"></i>
                <?php echo $catName; ?>
                <span class="ml-3 text-xs font-bold bg-indigo-50 text-indigo-400 px-2 py-1 rounded-full">
                    <?php echo count($products); ?> sản phẩm
                </span>
            </h2>
            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Sản phẩm</th>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Giá bán</th>
                            <th class="px-8 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Số lượng tồn</th>
                            <th class="px-8 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php foreach($products as $p): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <?php if($p->image): ?>
                                        <img src="<?php echo URLROOT; ?>/public/images/<?php echo $p->image; ?>" class="h-10 w-10 rounded-xl object-cover mr-3 border border-gray-100">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded-xl bg-gray-50 flex items-center justify-center mr-3 border border-gray-100">
                                            <i class="fa-solid fa-box text-gray-300"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="text-sm font-bold text-gray-800"><?php echo $p->name; ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-500">
                                <?php echo number_format($p->price, 0, ',', '.'); ?> đ
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex justify-center">
                                    <input type="number" name="stock[<?php echo $p->id; ?>]" value="<?php echo $p->stock_quantity; ?>" 
                                           class="w-24 px-4 py-2 rounded-xl border border-gray-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-gray-50/50 text-center font-bold <?php echo ($p->stock_quantity < 10) ? 'text-red-500' : 'text-gray-800'; ?>">
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="<?php echo URLROOT; ?>/admin/product_edit/<?php echo $p->id; ?>" class="text-blue-500 hover:text-blue-700 transition">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="<?php echo URLROOT; ?>/admin/product_delete/<?php echo $p->id; ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="fixed bottom-8 right-8 z-30">
            <button type="submit" class="bg-primary text-white px-10 py-4 rounded-2xl font-black shadow-2xl shadow-primary/40 hover:bg-indigo-700 hover:-translate-y-1 transition transform flex items-center">
                <i class="fa-solid fa-save mr-2"></i> Lưu tất cả thay đổi
            </button>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
