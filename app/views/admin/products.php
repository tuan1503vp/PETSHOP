<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Quản lý Sản phẩm</h1>
        <div class="flex gap-3">
            <a href="<?php echo URLROOT; ?>/admin/export_products" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-50 transition flex items-center">
                <i class="fa-solid fa-download mr-2 text-green-600"></i> Xuất Excel (CSV)
            </a>
            <a href="<?php echo URLROOT; ?>/admin/product_add" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Thêm sản phẩm mới
            </a>
        </div>
    </div>

    <?php 
        // Lọc danh sách sản phẩm sắp hết hạn (<= 30 ngày)
        $expiringProducts = [];
        $today = date_create(date('Y-m-d'));
        foreach($data['products'] as $p) {
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
        
        // Sắp xếp sản phẩm sắp hết hạn theo số ngày còn lại (ít nhất lên đầu)
        usort($expiringProducts, function($a, $b) {
            return $a->days_to_expiry <=> $b->days_to_expiry;
        });
    ?>

    <?php if(!empty($expiringProducts)): ?>
    <div class="mb-8">
        <h2 class="text-xl font-bold text-red-600 mb-4 flex items-center">
            <i class="fa-solid fa-triangle-exclamation mr-2 animate-pulse"></i> Cảnh báo: Lô hàng sắp/đã hết hạn (<?php echo count($expiringProducts); ?>)
        </h2>
        <div class="bg-red-50 rounded-lg shadow-sm border border-red-200 overflow-hidden">
            <table class="min-w-full divide-y divide-red-200">
                <thead class="bg-red-100/50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Sản phẩm</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Tồn kho</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Tình trạng HSD</th>
                        <th class="px-4 py-2 text-right text-xs font-bold text-red-800 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-red-100">
                    <?php foreach($expiringProducts as $ep): ?>
                        <tr class="hover:bg-red-50/30 transition-colors">
                            <td class="px-4 py-2 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <img class="h-8 w-8 rounded-lg object-cover border border-gray-100" src="<?php echo !empty($ep->image) ? URLROOT . '/public/images/' . $ep->image : 'https://placehold.co/100x100?text=' . urlencode($ep->name); ?>" alt="">
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-xs font-bold text-gray-900"><?php echo $ep->name; ?></div>
                                        <div class="text-[10px] text-gray-500"><?php echo $ep->category_name ?? 'Chưa phân loại'; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                <span class="text-xs font-bold <?php echo $ep->stock_quantity <= 5 ? 'text-red-600' : 'text-gray-800'; ?>">
                                    <?php echo $ep->stock_quantity; ?>
                                </span>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                <?php if ($ep->days_to_expiry < 0): ?>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border bg-red-100 text-red-800 border-red-200">
                                        Đã hết hạn (<?php echo date('d/m/Y', strtotime($ep->expiry_date)); ?>)
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border bg-amber-100 text-amber-800 border-amber-200 animate-pulse">
                                        Còn <?php echo $ep->days_to_expiry; ?> ngày (<?php echo date('d/m/Y', strtotime($ep->expiry_date)); ?>)
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-right text-xs font-medium">
                                <a href="<?php echo URLROOT; ?>/admin/product_edit/<?php echo $ep->id; ?>" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fa-solid fa-boxes-stacked mr-2 text-primary"></i> Tất cả danh mục
    </h2>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hạn sử dụng</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
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
                        <td colspan="6" class="px-4 py-2 text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                            <i class="fa-solid fa-folder-open mr-2 text-primary"></i> <?php echo $categoryName; ?> 
                            <span class="ml-2 text-[10px] font-normal text-gray-400 normal-case">(<?php echo count($products); ?> sản phẩm)</span>
                        </td>
                    </tr>

                    <?php foreach($products as $product): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-lg object-cover border border-gray-100" src="<?php echo !empty($product->image) ? URLROOT . '/public/images/' . $product->image : 'https://placehold.co/100x100?text=' . urlencode($product->name); ?>" alt="">
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-bold text-gray-900"><?php echo $product->name; ?></div>
                                    <div class="text-xs text-gray-500 truncate max-w-[200px]"><?php echo $product->description; ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full bg-indigo-50 text-primary border border-indigo-100">
                                <?php echo $product->category_name ?? 'Chưa phân loại'; ?>
                            </span>
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap text-sm text-gray-900 font-black">
                            <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                        </td>
                        <!-- Stock Status -->
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <div class="space-y-0.5">
                                <span class="text-sm font-bold <?php echo $product->stock_quantity <= 5 ? 'text-red-600' : 'text-gray-800'; ?>">
                                    <?php echo $product->stock_quantity; ?>
                                </span>
                                <?php if ($product->stock_quantity == 0): ?>
                                    <span class="block px-1.5 py-0.5 text-[8px] font-black text-red-600 bg-red-50 border border-red-100 rounded-md uppercase tracking-wider w-max">Hết hàng</span>
                                <?php elseif ($product->stock_quantity <= 5): ?>
                                    <span class="block px-1.5 py-0.5 text-[8px] font-black text-amber-600 bg-amber-50 border border-amber-100 rounded-md uppercase tracking-wider w-max">Sắp hết</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <!-- Expiry Date Status -->
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <?php if (!empty($product->expiry_date)): ?>
                                <?php 
                                    $today = date_create(date('Y-m-d'));
                                    $expiry = date_create($product->expiry_date);
                                    $diff = date_diff($today, $expiry);
                                    $days = (int)$diff->format("%r%a");

                                    if ($days < 0) {
                                        $badge = "bg-red-50 text-red-700 border-red-150";
                                        $label = "Hết hạn (" . date('d/m/Y', strtotime($product->expiry_date)) . ")";
                                    } elseif ($days <= 30) {
                                        $badge = "bg-amber-50 text-amber-700 border-amber-150 animate-pulse";
                                        $label = "Còn " . $days . " ngày";
                                    } else {
                                        $badge = "bg-green-50 text-green-700 border-green-150";
                                        $label = "HSD: " . date('d/m/Y', strtotime($product->expiry_date));
                                    }
                                ?>
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold border <?php echo $badge; ?>">
                                    <?php echo $label; ?>
                                </span>
                            <?php else: ?>
                                <span class="text-xs text-gray-400 italic">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap text-right text-sm font-medium">
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

        <!-- Pagination Controls -->
        <?php if ($data['pagination']['total_pages'] > 1): ?>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Hiển thị trang <span class="font-bold text-gray-700"><?php echo $data['pagination']['current_page']; ?></span> / <span class="font-bold text-gray-700"><?php echo $data['pagination']['total_pages']; ?></span> (Tổng cộng <span class="font-bold text-gray-700"><?php echo $data['pagination']['total_products']; ?></span> sản phẩm)
                </div>
                <div class="flex gap-1.5">
                    <!-- Previous Button -->
                    <?php if ($data['pagination']['current_page'] > 1): ?>
                        <a href="<?php echo URLROOT; ?>/admin/products?page=<?php echo $data['pagination']['current_page'] - 1; ?>" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-50 transition flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-chevron-left text-[9px]"></i> Trước
                        </a>
                    <?php else: ?>
                        <span class="px-3 py-1.5 bg-gray-50 border border-gray-150 text-gray-300 rounded-lg text-xs font-bold flex items-center gap-1 cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left text-[9px]"></i> Trước
                        </span>
                    <?php endif; ?>

                    <!-- Page numbers -->
                    <?php 
                    $start = max(1, $data['pagination']['current_page'] - 2);
                    $end = min($data['pagination']['total_pages'], $data['pagination']['current_page'] + 2);
                    for ($i = $start; $i <= $end; $i++): 
                        $isCurrent = $i == $data['pagination']['current_page'];
                    ?>
                        <a href="<?php echo URLROOT; ?>/admin/products?page=<?php echo $i; ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition shadow-sm <?php echo $isCurrent ? 'bg-primary border-primary text-white' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Next Button -->
                    <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                        <a href="<?php echo URLROOT; ?>/admin/products?page=<?php echo $data['pagination']['current_page'] + 1; ?>" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-50 transition flex items-center gap-1 shadow-sm">
                            Sau <i class="fa-solid fa-chevron-right text-[9px]"></i>
                        </a>
                    <?php else: ?>
                        <span class="px-3 py-1.5 bg-gray-50 border border-gray-150 text-gray-300 rounded-lg text-xs font-bold flex items-center gap-1 cursor-not-allowed">
                            Sau <i class="fa-solid fa-chevron-right text-[9px]"></i>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/admin/footer.php'; ?>
