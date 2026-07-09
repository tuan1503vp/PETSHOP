<?php require APPROOT . '/views/admin/header.php'; ?>

<style>[x-cloak] { display: none !important; }</style>

<div class="p-6" x-data="productManagement()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quản lý Sản phẩm</h1>
            <p class="text-sm text-gray-500 mt-1">Tổng cộng <span class="font-bold text-primary"><?php echo $data['total_products']; ?></span> sản phẩm trong hệ thống</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo URLROOT; ?>/admin/export_products" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-50 transition flex items-center">
                <i class="fa-solid fa-download mr-2 text-green-600"></i> Xuất Excel (CSV)
            </a>
            <button type="button" @click="openAddModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Thêm sản phẩm mới
            </button>
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
                                <button type="button" @click="openEditModal(<?php echo $ep->id; ?>)" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                </button>
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

    <?php 
        $groupedProducts = [];
        foreach($data['products'] as $product) {
            $catName = $product->category_name ?? 'Chưa phân loại';
            $groupedProducts[$catName][] = $product;
        }
        $catIndex = 0;
    ?>

    <?php foreach($groupedProducts as $categoryName => $products): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-4" x-data="{ expanded: <?php echo count($products) <= 10 ? 'true' : 'false'; ?> }">
        <!-- Category Header -->
        <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-200 flex justify-between items-center cursor-pointer" @click="expanded = !expanded">
            <div class="flex items-center">
                <i class="fa-solid fa-folder-open mr-2 text-primary"></i>
                <span class="text-sm font-bold text-gray-700"><?php echo $categoryName; ?></span>
                <span class="ml-2 text-xs text-gray-400">(<?php echo count($products); ?> sản phẩm)</span>
            </div>
            <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="expanded ? 'rotate-180' : ''"></i>
        </div>
        
        <div x-show="expanded" x-collapse>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hạn sử dụng</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php foreach($products as $product): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 py-1.5 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <img class="h-8 w-8 rounded-lg object-cover border border-gray-100" src="<?php echo !empty($product->image) ? URLROOT . '/public/images/' . $product->image : 'https://placehold.co/100x100?text=' . urlencode($product->name); ?>" alt="">
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-bold text-gray-900"><?php echo $product->name; ?></div>
                                    <div class="text-xs text-gray-500 truncate max-w-[200px]"><?php echo $product->description; ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-1.5 whitespace-nowrap text-sm text-gray-900 font-black">
                            <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                        </td>
                        <td class="px-3 py-1.5 whitespace-nowrap">
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
                        <td class="px-3 py-1.5 whitespace-nowrap">
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
                        <td class="px-3 py-1.5 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-1.5">
                                <button type="button" @click="openEditModal(<?php echo $product->id; ?>)" class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all text-xs">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="<?php echo URLROOT; ?>/admin/product_delete/<?php echo $product->id; ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                    <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-xs">
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
    <?php $catIndex++; endforeach; ?>

    <!-- ===================== ADD PRODUCT MODAL ===================== -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-dark/60 transition-opacity backdrop-blur-sm" @click="showAddModal = false"></div>

            <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-2xl w-full">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-box-open text-primary"></i> Thêm Sản Phẩm Mới
                    </h3>
                    <button type="button" @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                
                <form action="<?php echo URLROOT; ?>/admin/product_add" method="POST" enctype="multipart/form-data" @submit="validateAndSubmit($event)">
                    <div class="p-6 space-y-4 max-h-[calc(100vh-16rem)] overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="modal_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tên Sản Phẩm <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="modal_name" class="w-full border-gray-200 rounded-xl shadow-sm py-1.5 px-3 focus:ring-primary focus:border-primary border transition-all text-xs" placeholder="VD: Hạt Royal Canin cho mèo">
                                <span class="text-[10px] text-red-500 font-bold block mt-1" x-show="errors.name" x-html="errors.name"></span>
                            </div>

                            <div>
                                <label for="modal_category_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Danh Mục <span class="text-red-500">*</span></label>
                                <div class="flex gap-2">
                                    <select name="category_id" id="modal_category_id" class="flex-1 border-gray-200 rounded-xl shadow-sm py-1.5 px-3 focus:ring-primary focus:border-primary border transition-all text-xs">
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php foreach($data['categories'] as $category): ?>
                                            <option value="<?php echo $category->id; ?>">
                                                <?php echo $category->name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" @click="showAddCatModal = true" class="px-2.5 py-1.5 bg-indigo-50 text-primary rounded-xl hover:bg-primary hover:text-white transition-all border border-indigo-100 text-xs">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                                <span class="text-[10px] text-red-500 font-bold block mt-1" x-show="errors.category_id" x-text="errors.category_id"></span>
                            </div>

                            <div>
                                <label for="modal_price" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Giá Bán (VNĐ) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs">₫</span>
                                    <input type="number" name="price" id="modal_price" min="0" step="1000" class="w-full border-gray-200 rounded-xl shadow-sm py-1.5 pl-8 pr-3 focus:ring-primary focus:border-primary border transition-all text-xs">
                                </div>
                                <span class="text-[10px] text-red-500 font-bold block mt-1" x-show="errors.price" x-text="errors.price"></span>
                            </div>

                            <div>
                                <label for="modal_stock_quantity" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Số lượng tồn kho <span class="text-red-500">*</span></label>
                                <input type="number" name="stock_quantity" id="modal_stock_quantity" min="0" value="0" class="w-full border-gray-200 rounded-xl shadow-sm py-1.5 px-3 focus:ring-primary focus:border-primary border transition-all text-xs">
                                <span class="text-[10px] text-red-500 font-bold block mt-1" x-show="errors.stock_quantity" x-text="errors.stock_quantity"></span>
                            </div>

                            <div>
                                <label for="modal_expiry_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Hạn sử dụng</label>
                                <input type="date" name="expiry_date" id="modal_expiry_date" class="w-full border-gray-200 rounded-xl shadow-sm py-1.5 px-3 focus:ring-primary focus:border-primary border transition-all text-xs">
                            </div>

                            <div>
                                <label for="modal_image" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Hình ảnh chính <span class="text-red-500">*</span></label>
                                <input type="file" name="image" id="modal_image" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-indigo-50 file:text-primary hover:file:bg-indigo-100 transition-all">
                                <span class="text-[10px] text-red-500 font-bold block mt-1" x-show="errors.image" x-text="errors.image"></span>
                            </div>

                            <div class="md:col-span-2">
                                <label for="modal_additional_images" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Hình ảnh bổ sung (nhiều ảnh)</label>
                                <input type="file" name="additional_images[]" id="modal_additional_images" accept="image/*" multiple class="w-full text-xs text-gray-500 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-indigo-50 file:text-primary hover:file:bg-indigo-100 transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="modal_description" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Mô Tả Sản Phẩm</label>
                            <textarea name="description" id="modal_description" rows="3" class="w-full border-gray-200 rounded-xl shadow-sm py-1.5 px-3 focus:ring-primary focus:border-primary border transition-all text-xs placeholder:text-gray-300" placeholder="Thông tin chi tiết về sản phẩm..."></textarea>
                        </div>
                    </div>

                    <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-3xl">
                        <button type="button" @click="showAddModal = false" class="px-6 py-2.5 text-gray-600 font-bold hover:text-gray-900 transition-colors text-sm">Hủy bỏ</button>
                        <button type="submit" class="px-8 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-primary/20 flex items-center text-sm">
                            <i class="fa-solid fa-save mr-2"></i> Lưu Sản Phẩm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== EDIT PRODUCT MODAL ===================== -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-dark/60 transition-opacity backdrop-blur-sm" @click="showEditModal = false"></div>

            <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-2xl w-full">
                
                <!-- Loading state -->
                <div x-show="editLoading" class="p-12 text-center">
                    <i class="fa-solid fa-spinner fa-spin text-3xl text-primary mb-4"></i>
                    <p class="text-sm text-gray-500 font-medium">Đang tải dữ liệu sản phẩm...</p>
                </div>

                <template x-if="!editLoading && editProduct">
                    <div>
                        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-blue-50/50">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-pen-to-square text-blue-600"></i> Chỉnh Sửa Sản Phẩm
                            </h3>
                            <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>
                        
                        <form :action="'<?php echo URLROOT; ?>/admin/product_edit/' + editProduct.id" method="POST" enctype="multipart/form-data" @submit="validateEditAndSubmit($event)">
                            <div class="p-6 space-y-4 max-h-[calc(100vh-16rem)] overflow-y-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tên Sản Phẩm <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" x-model="editProduct.name" required class="w-full border-gray-200 rounded-xl shadow-sm py-1.5 px-3 focus:ring-primary focus:border-primary border transition-all text-xs">
                                        <span class="text-[10px] text-red-500 font-bold block mt-1" x-show="editErrors.name" x-html="editErrors.name"></span>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Danh Mục <span class="text-red-500">*</span></label>
                                        <select name="category_id" x-model="editProduct.category_id" required class="w-full border-gray-200 rounded-xl shadow-sm py-1.5 px-3 focus:ring-primary focus:border-primary border transition-all text-xs">
                                            <option value="">-- Chọn danh mục --</option>
                                            <?php foreach($data['categories'] as $category): ?>
                                                <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Giá Bán (VNĐ) <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs">₫</span>
                                            <input type="number" name="price" x-model="editProduct.price" required min="0" step="1000" class="w-full border-gray-200 rounded-xl shadow-sm py-1.5 pl-8 pr-3 focus:ring-primary focus:border-primary border transition-all text-xs">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Số lượng tồn kho <span class="text-red-500">*</span></label>
                                        <input type="number" name="stock_quantity" x-model="editProduct.stock_quantity" required min="0" class="w-full border-gray-200 rounded-xl shadow-sm py-1.5 px-3 focus:ring-primary focus:border-primary border transition-all text-xs">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Hạn sử dụng</label>
                                        <input type="date" name="expiry_date" x-model="editProduct.expiry_date" class="w-full border-gray-200 rounded-xl shadow-sm py-1.5 px-3 focus:ring-primary focus:border-primary border transition-all text-xs">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Thay đổi ảnh chính</label>
                                        <input type="file" name="image" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-indigo-50 file:text-primary hover:file:bg-indigo-100 transition-all">
                                        <template x-if="editProduct.image">
                                            <p class="text-[10px] text-gray-400 mt-1 italic">Ảnh hiện tại: <span x-text="editProduct.image"></span></p>
                                        </template>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Thêm ảnh bổ sung mới</label>
                                        <input type="file" name="additional_images[]" accept="image/*" multiple class="w-full text-xs text-gray-500 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-indigo-50 file:text-primary hover:file:bg-indigo-100 transition-all">
                                    </div>
                                </div>

                                <!-- Ảnh bổ sung hiện có -->
                                <template x-if="editProduct.additional_images && editProduct.additional_images.length > 0">
                                    <div class="border-t border-gray-100 pt-4">
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Ảnh bổ sung hiện có (chọn để xóa)</label>
                                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                                            <template x-for="img in editProduct.additional_images" :key="img.id">
                                                <div class="relative group rounded-xl overflow-hidden border border-gray-100 shadow-sm aspect-square bg-gray-50">
                                                    <img :src="'<?php echo URLROOT; ?>/public/images/' + img.image" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <label class="flex items-center gap-1 text-white text-[10px] font-bold cursor-pointer bg-red-600/90 px-2 py-1 rounded-full select-none">
                                                            <input type="checkbox" name="delete_images[]" :value="img.id" class="rounded text-red-600 w-3 h-3 border-none">
                                                            Xóa
                                                        </label>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Mô Tả Sản Phẩm</label>
                                    <textarea name="description" x-model="editProduct.description" rows="3" class="w-full border-gray-200 rounded-xl shadow-sm py-1.5 px-3 focus:ring-primary focus:border-primary border transition-all text-xs placeholder:text-gray-300" placeholder="Thông tin chi tiết..."></textarea>
                                </div>
                            </div>

                            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-3xl">
                                <button type="button" @click="showEditModal = false" class="px-6 py-2.5 text-gray-600 font-bold hover:text-gray-900 transition-colors text-sm">Hủy bỏ</button>
                                <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-md shadow-blue-600/20 flex items-center text-sm">
                                    <i class="fa-solid fa-save mr-2"></i> Cập Nhật
                                </button>
                            </div>
                        </form>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- ===================== CATEGORY ADD MODAL ===================== -->
    <div x-show="showAddCatModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showAddCatModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-dark/60 transition-opacity backdrop-blur-sm" @click="showAddCatModal = false"></div>

            <div x-show="showAddCatModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-base font-bold text-gray-800">Thêm Danh Mục Mới</h3>
                    <button type="button" @click="showAddCatModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="new_category_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                        <input type="text" id="new_category_name" class="w-full border-gray-200 rounded-xl shadow-sm py-2 px-3 focus:ring-primary focus:border-primary border transition-all text-sm" placeholder="VD: Thức ăn khô">
                    </div>
                    <div>
                        <label for="new_category_desc" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Mô tả</label>
                        <textarea id="new_category_desc" rows="3" class="w-full border-gray-200 rounded-xl shadow-sm py-2 px-3 focus:ring-primary focus:border-primary border transition-all text-sm placeholder:text-gray-300" placeholder="Mô tả danh mục..."></textarea>
                    </div>
                    <div id="category-error" class="text-xs text-red-600 font-medium hidden"></div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2 rounded-b-3xl">
                    <button type="button" @click="showAddCatModal = false" class="px-4 py-2 text-gray-600 font-bold hover:text-gray-900 transition-colors text-xs">Hủy</button>
                    <button type="button" @click="saveCategory()" id="btn-save-category" class="px-5 py-2 bg-primary text-white font-bold rounded-xl hover:bg-indigo-700 transition-all text-xs shadow-sm">Lưu lại</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function productManagement() {
    return {
        showAddModal: false,
        showEditModal: false,
        showAddCatModal: false,
        editLoading: false,
        editProduct: null,
        errors: { name: '', category_id: '', price: '', stock_quantity: '', image: '' },
        editErrors: { name: '' },

        openAddModal() {
            this.errors = { name: '', category_id: '', price: '', stock_quantity: '', image: '' };
            this.showAddModal = true;
        },

        async openEditModal(productId) {
            this.editLoading = true;
            this.editProduct = null;
            this.editErrors = { name: '' };
            this.showEditModal = true;

            try {
                const res = await fetch('<?php echo URLROOT; ?>/admin/get_product_json/' + productId);
                const data = await res.json();
                if (data.error) {
                    alert(data.error);
                    this.showEditModal = false;
                    return;
                }
                this.editProduct = data;
            } catch (err) {
                console.error('Lỗi tải sản phẩm:', err);
                alert('Không thể tải dữ liệu sản phẩm.');
                this.showEditModal = false;
            } finally {
                this.editLoading = false;
            }
        },

        async validateAndSubmit(e) {
            e.preventDefault();
            const formEl = e.target;
            this.errors = { name: '', category_id: '', price: '', stock_quantity: '', image: '' };
            let hasError = false;

            const nameVal = document.getElementById('modal_name').value.trim();
            const catVal = document.getElementById('modal_category_id').value;
            const priceVal = document.getElementById('modal_price').value;
            const stockVal = document.getElementById('modal_stock_quantity').value;
            const imageVal = document.getElementById('modal_image').value;

            if (!nameVal) { this.errors.name = 'Tên sản phẩm không được để trống'; hasError = true; }
            if (!catVal) { this.errors.category_id = 'Vui lòng chọn danh mục'; hasError = true; }
            if (!priceVal) { this.errors.price = 'Vui lòng nhập giá bán sản phẩm'; hasError = true; }
            else if (parseFloat(priceVal) < 0) { this.errors.price = 'Giá bán phải lớn hơn hoặc bằng 0'; hasError = true; }
            if (!stockVal) { this.errors.stock_quantity = 'Vui lòng nhập số lượng tồn kho'; hasError = true; }
            else if (parseInt(stockVal) < 0) { this.errors.stock_quantity = 'Số lượng tồn kho phải lớn hơn hoặc bằng 0'; hasError = true; }
            if (!imageVal) { this.errors.image = 'Vui lòng chọn ảnh chính cho sản phẩm'; hasError = true; }

            if (hasError) return false;

            try {
                const response = await fetch('<?php echo URLROOT; ?>/admin/check_product_name?name=' + encodeURIComponent(nameVal));
                const result = await response.json();
                if (result.exists) {
                    this.errors.name = 'Sản phẩm "' + nameVal + '" đã tồn tại! <a href="<?php echo URLROOT; ?>/admin/product_edit/' + result.id + '" class="text-blue-600 underline font-black">Chỉnh sửa sản phẩm cũ</a>.';
                    return false;
                }
            } catch (err) { console.error(err); }

            formEl.submit();
            return true;
        },

        async validateEditAndSubmit(e) {
            e.preventDefault();
            const formEl = e.target;
            this.editErrors = { name: '' };

            const nameVal = this.editProduct.name ? this.editProduct.name.trim() : '';
            if (!nameVal) {
                this.editErrors.name = 'Tên sản phẩm không được để trống';
                return false;
            }

            try {
                const response = await fetch('<?php echo URLROOT; ?>/admin/check_product_name?name=' + encodeURIComponent(nameVal) + '&exclude_id=' + this.editProduct.id);
                const result = await response.json();
                if (result.exists) {
                    this.editErrors.name = 'Sản phẩm "' + nameVal + '" đã tồn tại!';
                    return false;
                }
            } catch (err) { console.error(err); }

            formEl.submit();
            return true;
        },

        async saveCategory() {
            const name = document.getElementById('new_category_name').value.trim();
            const desc = document.getElementById('new_category_desc').value.trim();
            const errorMsg = document.getElementById('category-error');

            if (!name) {
                errorMsg.textContent = 'Vui lòng nhập tên danh mục';
                errorMsg.classList.remove('hidden');
                return;
            }

            const btnSave = document.getElementById('btn-save-category');
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Đang lưu...';

            const formData = new FormData();
            formData.append('name', name);
            formData.append('description', desc);
            formData.append('type', 'product');

            try {
                const response = await fetch('<?php echo URLROOT; ?>/admin/add_category_ajax', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    const categorySelect = document.getElementById('modal_category_id');
                    const option = new Option(result.name, result.id);
                    categorySelect.add(option);
                    categorySelect.value = result.id;
                    
                    document.getElementById('new_category_name').value = '';
                    document.getElementById('new_category_desc').value = '';
                    errorMsg.classList.add('hidden');
                    this.showAddCatModal = false;
                } else {
                    errorMsg.textContent = result.message;
                    errorMsg.classList.remove('hidden');
                }
            } catch (error) {
                errorMsg.textContent = 'Lỗi kết nối';
                errorMsg.classList.remove('hidden');
            } finally {
                btnSave.disabled = false;
                btnSave.textContent = 'Lưu lại';
            }
        }
    };
}
</script>

<?php require APPROOT . '/views/admin/footer.php'; ?>
