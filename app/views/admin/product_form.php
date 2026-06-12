<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="<?php echo URLROOT; ?>/admin/products" class="text-gray-500 hover:text-primary mr-4 transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">
            <?php echo isset($data['product']) ? 'Chỉnh Sửa Sản Phẩm' : 'Thêm Sản Phẩm Mới'; ?>
        </h1>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <form action="<?php echo isset($data['product']) ? URLROOT . '/admin/product_edit/' . $data['product']->id : URLROOT . '/admin/product_add'; ?>" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-100">
            
            <div class="p-8 space-y-8">
                <!-- Cơ bản -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Tên Sản Phẩm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required
                               value="<?php echo isset($data['product']) ? $data['product']->name : ''; ?>"
                               class="w-full border-gray-200 rounded-xl shadow-sm py-3 px-4 focus:ring-primary focus:border-primary border transition-all"
                               placeholder="VD: Hạt Royal Canin cho mèo">
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-bold text-gray-700 mb-2">Danh Mục <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <select name="category_id" id="category_id" required class="flex-1 border-gray-200 rounded-xl shadow-sm py-3 px-4 focus:ring-primary focus:border-primary border transition-all">
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach($data['categories'] as $category): ?>
                                    <option value="<?php echo $category->id; ?>" <?php echo (isset($data['product']) && $data['product']->category_id == $category->id) ? 'selected' : ''; ?>>
                                        <?php echo $category->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" id="btn-add-category" class="px-4 py-3 bg-indigo-50 text-primary rounded-xl hover:bg-primary hover:text-white transition-all border border-indigo-100">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-bold text-gray-700 mb-2">Giá Bán (VNĐ) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">₫</span>
                            <input type="number" name="price" id="price" required min="0" step="1000"
                                   value="<?php echo isset($data['product']) ? $data['product']->price : ''; ?>"
                                   class="w-full border-gray-200 rounded-xl shadow-sm py-3 pl-10 pr-4 focus:ring-primary focus:border-primary border transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="stock_quantity" class="block text-sm font-bold text-gray-700 mb-2">Số lượng tồn kho <span class="text-red-500">*</span></label>
                        <input type="number" name="stock_quantity" id="stock_quantity" required min="0"
                               value="<?php echo isset($data['product']) ? $data['product']->stock_quantity : '0'; ?>"
                               class="w-full border-gray-200 rounded-xl shadow-sm py-3 px-4 focus:ring-primary focus:border-primary border transition-all">
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-bold text-gray-700 mb-2">Hình ảnh sản phẩm</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-primary hover:file:bg-indigo-100 transition-all">
                        <?php if(isset($data['product']) && $data['product']->image): ?>
                            <p class="mt-2 text-xs text-gray-500 italic">Ảnh hiện tại: <?php echo $data['product']->image; ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="additional_images" class="block text-sm font-bold text-gray-700 mb-2">Hình ảnh bổ sung (nhiều ảnh)</label>
                        <input type="file" name="additional_images[]" id="additional_images" accept="image/*" multiple
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-primary hover:file:bg-indigo-100 transition-all">
                    </div>
                </div>

                <?php if(isset($data['product']) && !empty($data['additional_images'])): ?>
                    <div class="border-t border-gray-100 pt-6">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Hình ảnh bổ sung hiện tại (chọn để xóa)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                            <?php foreach($data['additional_images'] as $img): ?>
                                <div class="relative group rounded-2xl overflow-hidden border border-gray-100 shadow-sm aspect-square bg-gray-50 flex items-center justify-center">
                                    <img src="<?php echo URLROOT . '/public/images/' . $img->image; ?>" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <label class="flex items-center gap-1.5 text-white text-xs font-bold cursor-pointer bg-red-600/95 px-3 py-1.5 rounded-full select-none shadow-md">
                                            <input type="checkbox" name="delete_images[]" value="<?php echo $img->id; ?>" class="rounded text-red-600 focus:ring-0 w-3.5 h-3.5 border-none">
                                            Xóa ảnh
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Mô Tả Sản Phẩm</label>
                    <textarea name="description" id="description" rows="5" 
                              class="w-full border-gray-200 rounded-xl shadow-sm py-3 px-4 focus:ring-primary focus:border-primary border transition-all"
                              placeholder="Thông tin chi tiết về sản phẩm..."><?php echo isset($data['product']) ? $data['product']->description : ''; ?></textarea>
                </div>
            </div>

            <div class="p-8 bg-gray-50 flex justify-end space-x-4">
                <a href="<?php echo URLROOT; ?>/admin/products" class="px-8 py-3 text-gray-600 font-bold hover:text-gray-900 transition-colors">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-10 py-3 bg-primary text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-primary/20 flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> Lưu Sản Phẩm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Thêm Danh Mục Mới (Reuse logic) -->
<div id="modal-category" class="fixed inset-0 bg-dark/60 flex items-center justify-center z-50 hidden backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 overflow-hidden reveal">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Thêm Danh Mục Sản Phẩm</h3>
            <button type="button" class="btn-close-modal text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="p-8">
            <div class="space-y-6">
                <div>
                    <label for="new_category_name" class="block text-sm font-bold text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                    <input type="text" id="new_category_name" class="w-full border-gray-200 rounded-xl shadow-sm py-3 px-4 focus:ring-primary focus:border-primary border transition-all" placeholder="VD: Thức ăn khô">
                </div>
                <div>
                    <label for="new_category_desc" class="block text-sm font-bold text-gray-700 mb-2">Mô tả</label>
                    <textarea id="new_category_desc" rows="3" class="w-full border-gray-200 rounded-xl shadow-sm py-3 px-4 focus:ring-primary focus:border-primary border transition-all" placeholder="Mô tả danh mục..."></textarea>
                </div>
            </div>
            <div id="category-error" class="mt-4 text-sm text-red-600 font-medium hidden"></div>
        </div>
        <div class="px-8 py-6 bg-gray-50 flex justify-end space-x-3">
            <button type="button" class="btn-close-modal px-6 py-2 text-gray-600 font-bold hover:text-gray-900 transition-colors">
                Hủy
            </button>
            <button type="button" id="btn-save-category" class="px-8 py-2 bg-primary text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-primary/20">
                Lưu lại
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-category');
    const btnAdd = document.getElementById('btn-add-category');
    const btnsClose = document.querySelectorAll('.btn-close-modal');
    const btnSave = document.getElementById('btn-save-category');
    const categorySelect = document.getElementById('category_id');
    const errorMsg = document.getElementById('category-error');

    btnAdd.addEventListener('click', () => modal.classList.remove('hidden'));
    btnsClose.forEach(btn => btn.addEventListener('click', () => {
        modal.classList.add('hidden');
        errorMsg.classList.add('hidden');
    }));

    btnSave.addEventListener('click', async () => {
        const name = document.getElementById('new_category_name').value.trim();
        const desc = document.getElementById('new_category_desc').value.trim();

        if (!name) {
            errorMsg.textContent = 'Vui lòng nhập tên danh mục';
            errorMsg.classList.remove('hidden');
            return;
        }

        btnSave.disabled = true;
        btnSave.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Đang lưu...';

        const formData = new FormData();
        formData.append('name', name);
        formData.append('description', desc);
        formData.append('type', 'product'); // Quan trọng: type là product

        try {
            const response = await fetch('<?php echo URLROOT; ?>/admin/add_category_ajax', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                const option = new Option(result.name, result.id);
                categorySelect.add(option);
                categorySelect.value = result.id;
                modal.classList.add('hidden');
                document.getElementById('new_category_name').value = '';
                document.getElementById('new_category_desc').value = '';
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
    });
});
</script>

<?php require APPROOT . '/views/admin/footer.php'; ?>
