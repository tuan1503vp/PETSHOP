<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6 max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="<?php echo URLROOT; ?>/admin/service_list" class="text-gray-500 hover:text-primary mr-4">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">
            <?php echo isset($data['service']) ? 'Chỉnh Sửa Dịch Vụ' : 'Thêm Dịch Vụ Mới'; ?>
        </h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <form action="<?php echo isset($data['service']) ? URLROOT . '/admin/service_edit/' . $data['service']->id : URLROOT . '/admin/service_add'; ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tên Dịch Vụ -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Tên Dịch Vụ <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required
                           value="<?php echo isset($data['service']) ? $data['service']->name : ''; ?>"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                </div>

                <!-- Phân Loại -->
                <div class="md:col-span-2">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Phân Loại Dịch Vụ</label>
                    <div class="mt-1 flex gap-2">
                        <select name="category_id" id="category_id" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                            <?php if(!empty($data['categories'])): ?>
                                <?php foreach($data['categories'] as $category): ?>
                                    <option value="<?php echo $category->id; ?>" <?php echo (isset($data['service']) && $data['service']->category_id == $category->id) ? 'selected' : ''; ?>>
                                        <?php echo $category->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">-- Chưa có danh mục nào --</option>
                            <?php endif; ?>
                        </select>
                        <button type="button" id="btn-add-category" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" title="Thêm phân loại mới">
                            <i class="fa-solid fa-plus text-primary"></i>
                        </button>
                    </div>
                </div>

                <!-- Giá Dịch Vụ -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Giá Dịch Vụ (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="price" required min="0" step="1000"
                           value="<?php echo isset($data['service']) ? $data['service']->price : '0'; ?>"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                    <p class="mt-1 text-xs text-gray-400 italic">* Lưu ý: Giá này sẽ được áp dụng khi thu ngân thanh toán (trừ dịch vụ khám chữa bệnh).</p>
                </div>

                <!-- Thời gian dự kiến -->
                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Thời gian dự kiến (phút)</label>
                    <input type="number" name="duration_minutes" id="duration_minutes" min="0"
                           value="<?php echo isset($data['service']) ? $data['service']->duration_minutes : '30'; ?>"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                </div>

                <!-- Ảnh Dịch Vụ -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Ảnh Dịch Vụ</label>
                    <div class="mt-2 flex items-center gap-6">
                        <?php if(isset($data['service']) && !empty($data['service']->image)): ?>
                            <div class="flex-shrink-0 h-24 w-24 rounded-lg overflow-hidden border border-gray-200">
                                <img src="<?php echo URLROOT . '/public/images/' . $data['service']->image; ?>" class="h-full w-full object-cover">
                            </div>
                        <?php endif; ?>
                        <div class="flex-1">
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-indigo-700 transition">
                            <p class="mt-1 text-xs text-gray-500">Định dạng: JPG, PNG. Dung lượng tối đa 2MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mô tả -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Mô Tả Dịch Vụ</label>
                <textarea name="description" id="description" rows="4" 
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary"><?php echo isset($data['service']) ? $data['service']->description : ''; ?></textarea>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <a href="<?php echo URLROOT; ?>/admin/service_list" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none mr-3">
                    Hủy bỏ
                </a>
                <button type="submit" class="bg-primary border border-transparent rounded-md shadow-sm py-2 px-6 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="fa-solid fa-save mr-2"></i> Lưu Dịch Vụ
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Thêm Phân Loại Mới -->
<div id="modal-category" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Thêm Phân Loại Dịch Vụ</h3>
            <button type="button" class="btn-close-modal text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <label for="new_category_name" class="block text-sm font-medium text-gray-700">Tên phân loại <span class="text-red-500">*</span></label>
                    <input type="text" id="new_category_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary" placeholder="VD: Khám chữa bệnh">
                </div>
                <div>
                    <label for="new_category_desc" class="block text-sm font-medium text-gray-700">Mô tả (không bắt buộc)</label>
                    <textarea id="new_category_desc" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary" placeholder="Mô tả ngắn gọn về phân loại này"></textarea>
                </div>
            </div>
            <div id="category-error" class="mt-3 text-sm text-red-600 hidden"></div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <button type="button" class="btn-close-modal px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none">
                Hủy
            </button>
            <button type="button" id="btn-save-category" class="px-6 py-2 text-sm font-medium text-white bg-primary border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
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

    // Mở modal
    btnAdd.addEventListener('click', () => {
        modal.classList.remove('hidden');
        document.getElementById('new_category_name').focus();
    });

    // Đóng modal
    btnsClose.forEach(btn => {
        btn.addEventListener('click', () => {
            modal.classList.add('hidden');
            errorMsg.classList.add('hidden');
        });
    });

    // Đóng modal khi click ra ngoài
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Lưu phân loại mới qua AJAX
    btnSave.addEventListener('click', async () => {
        const name = document.getElementById('new_category_name').value.trim();
        const desc = document.getElementById('new_category_desc').value.trim();

        if (!name) {
            errorMsg.textContent = 'Vui lòng nhập tên phân loại';
            errorMsg.classList.remove('hidden');
            return;
        }

        btnSave.disabled = true;
        btnSave.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Đang lưu...';

        const formData = new FormData();
        formData.append('name', name);
        formData.append('description', desc);
        formData.append('type', 'service');

        try {
            const response = await fetch('<?php echo URLROOT; ?>/admin/add_category_ajax', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                // Tạo option mới và chọn nó
                const option = new Option(result.name, result.id);
                categorySelect.add(option);
                categorySelect.value = result.id;

                // Reset form và đóng modal
                document.getElementById('new_category_name').value = '';
                document.getElementById('new_category_desc').value = '';
                modal.classList.add('hidden');
            } else {
                errorMsg.textContent = result.message || 'Có lỗi xảy ra';
                errorMsg.classList.remove('hidden');
            }
        } catch (error) {
            errorMsg.textContent = 'Lỗi kết nối máy chủ';
            errorMsg.classList.remove('hidden');
        } finally {
            btnSave.disabled = false;
            btnSave.textContent = 'Lưu lại';
        }
    });
});
</script>

<?php require APPROOT . '/views/admin/footer.php'; ?>
