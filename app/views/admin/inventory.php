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

    <?php 
        // Lọc danh sách sản phẩm sắp hết hạn (<= 30 ngày)
        $expiringProducts = [];
        $today = date_create(date('Y-m-d'));
        foreach($data['groupedProducts'] as $cat) {
            foreach($cat as $p) {
                if (!empty($p->expiry_date)) {
                    $expiry = date_create($p->expiry_date);
                    $diff = date_diff($today, $expiry);
                    $days = (int)$diff->format("%r%a");
                    if ($days <= 30) {
                        $p->days_to_expiry = $days;
                        $expiringProducts[] = $p;
                    }
                }
            }
        }
        
        usort($expiringProducts, function($a, $b) {
            return $a->days_to_expiry <=> $b->days_to_expiry;
        });
    ?>

    <?php if(!empty($expiringProducts)): ?>
    <div class="mb-10">
        <h2 class="text-xl font-bold text-red-600 mb-4 flex items-center">
            <i class="fa-solid fa-triangle-exclamation mr-2 animate-pulse"></i> Cảnh báo: Lô hàng sắp/đã hết hạn (<?php echo count($expiringProducts); ?>)
        </h2>
        <div class="bg-red-50 rounded-3xl shadow-sm border border-red-200 overflow-hidden">
            <table class="min-w-full divide-y divide-red-200">
                <thead class="bg-red-100/50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-widest">Sản phẩm</th>
                        <th class="px-8 py-4 text-center text-xs font-bold text-red-800 uppercase tracking-widest">Tồn kho</th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-widest">Tình trạng HSD</th>
                        <th class="px-8 py-4 text-right text-xs font-bold text-red-800 uppercase tracking-widest">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-red-100">
                    <?php foreach($expiringProducts as $ep): ?>
                        <tr class="hover:bg-red-50/30 transition-colors">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-lg object-cover border border-gray-100" src="<?php echo !empty($ep->image) ? URLROOT . '/public/images/' . $ep->image : 'https://placehold.co/100x100?text=' . urlencode($ep->name); ?>" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900"><?php echo $ep->name; ?></div>
                                        <div class="text-xs text-gray-500"><?php echo $ep->category_name ?? 'Chưa phân loại'; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-center">
                                <span class="text-sm font-bold <?php echo $ep->stock_quantity <= 5 ? 'text-red-600' : 'text-gray-800'; ?>">
                                    <?php echo $ep->stock_quantity; ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <?php if ($ep->days_to_expiry < 0): ?>
                                    <span class="px-2.5 py-1.5 rounded-lg text-xs font-bold border bg-red-100 text-red-800 border-red-200">
                                        Đã hết hạn (<?php echo date('d/m/Y', strtotime($ep->expiry_date)); ?>)
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1.5 rounded-lg text-xs font-bold border bg-amber-100 text-amber-800 border-amber-200 animate-pulse">
                                        Còn <?php echo $ep->days_to_expiry; ?> ngày (<?php echo date('d/m/Y', strtotime($ep->expiry_date)); ?>)
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?php echo URLROOT; ?>/admin/product_edit/<?php echo $ep->id; ?>" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

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
