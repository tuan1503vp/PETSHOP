<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?php echo URLROOT; ?>" class="hover:text-primary"><i class="fa-solid fa-house mr-2"></i>Trang chủ</a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-xs mx-2"></i>
                    <a href="<?php echo URLROOT; ?>/pet" class="hover:text-primary">Thú cưng của tôi</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-xs mx-2"></i>
                    <span class="text-gray-900 font-semibold">Thêm bé mới</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Form Container -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden p-8 sm:p-12">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                <i class="fa-solid fa-circle-plus text-primary"></i> Đăng ký thú cưng mới
            </h1>
            <p class="text-sm text-gray-500 mt-2">Vui lòng điền đầy đủ các thông tin của bé cưng để hệ thống lưu trữ.</p>
        </div>

        <form action="<?php echo URLROOT; ?>/pet/add" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Row 1: Tên & Loại -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Tên bé cưng <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo htmlspecialchars($data['name']); ?>" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-800 transition" 
                           placeholder="Ví dụ: Milu, Kiki...">
                    <span class="text-xs text-red-500 font-medium mt-1 block"><?php echo $data['name_err']; ?></span>
                </div>

                <div>
                    <label for="species" class="block text-sm font-bold text-gray-700 mb-2">Loại thú cưng <span class="text-red-500">*</span></label>
                    <select id="species" name="species" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-800 transition">
                        <option value="">-- Chọn loài --</option>
                        <option value="Chó" <?php echo $data['species'] == 'Chó' ? 'selected' : ''; ?>>Chó</option>
                        <option value="Mèo" <?php echo $data['species'] == 'Mèo' ? 'selected' : ''; ?>>Mèo</option>
                        <option value="Chim" <?php echo $data['species'] == 'Chim' ? 'selected' : ''; ?>>Chim</option>
                        <option value="Chuột Hamster" <?php echo $data['species'] == 'Chuột Hamster' ? 'selected' : ''; ?>>Chuột Hamster</option>
                        <option value="Khác" <?php echo $data['species'] == 'Khác' ? 'selected' : ''; ?>>Khác</option>
                    </select>
                    <span class="text-xs text-red-500 font-medium mt-1 block"><?php echo $data['species_err']; ?></span>
                </div>
            </div>

            <!-- Row 2: Giống & Tuổi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="breed" class="block text-sm font-bold text-gray-700 mb-2">Giống (Breed)</label>
                    <input type="text" id="breed" name="breed" 
                           value="<?php echo htmlspecialchars($data['breed']); ?>" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-800 transition" 
                           placeholder="Ví dụ: Poodle, Golden Retriever, British Shorthair...">
                </div>

                <div>
                    <label for="age" class="block text-sm font-bold text-gray-700 mb-2">Tuổi (Số tháng tuổi) <span class="text-red-500">*</span></label>
                    <input type="number" id="age" name="age" min="1" max="300"
                           value="<?php echo htmlspecialchars($data['age']); ?>" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-800 transition" 
                           placeholder="Ví dụ: 12 (tương đương 1 tuổi)">
                    <span class="text-xs text-red-500 font-medium mt-1 block"><?php echo $data['age_err']; ?></span>
                </div>
            </div>

            <!-- Row 3: Giới tính & Màu sắc & Cân nặng -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="gender" class="block text-sm font-bold text-gray-700 mb-2">Giới tính</label>
                    <select id="gender" name="gender" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-800 transition">
                        <option value="unknown" <?php echo $data['gender'] == 'unknown' ? 'selected' : ''; ?>>Chưa rõ</option>
                        <option value="male" <?php echo $data['gender'] == 'male' ? 'selected' : ''; ?>>Đực (Male)</option>
                        <option value="female" <?php echo $data['gender'] == 'female' ? 'selected' : ''; ?>>Cái (Female)</option>
                    </select>
                </div>

                <div>
                    <label for="color" class="block text-sm font-bold text-gray-700 mb-2">Màu sắc</label>
                    <input type="text" id="color" name="color" 
                           value="<?php echo htmlspecialchars($data['color']); ?>" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-800 transition" 
                           placeholder="Ví dụ: Vàng, Trắng, Tam thể...">
                </div>

                <div>
                    <label for="weight" class="block text-sm font-bold text-gray-700 mb-2">Cân nặng (kg)</label>
                    <input type="number" id="weight" name="weight" step="0.1" min="0.1" max="150"
                           value="<?php echo htmlspecialchars($data['weight']); ?>" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary text-gray-800 transition" 
                           placeholder="Ví dụ: 4.5">
                </div>
            </div>

            <!-- Row 4: Upload ảnh -->
            <div x-data="{ photoName: null, photoPreview: null }">
                <label class="block text-sm font-bold text-gray-700 mb-2">Hình ảnh thú cưng</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-primary transition-colors relative">
                    <div class="space-y-1 text-center" x-show="!photoPreview">
                        <i class="fa-regular fa-image text-gray-400 text-4xl mb-3 block"></i>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-bold text-primary hover:text-indigo-500 focus-within:outline-none">
                                <span>Tải ảnh lên</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*"
                                       @change="
                                           const file = $event.target.files[0];
                                           if (file) {
                                               photoName = file.name;
                                               const reader = new FileReader();
                                               reader.onload = (e) => { photoPreview = e.target.result; };
                                               reader.readAsDataURL(file);
                                           }
                                       ">
                            </label>
                        </div>
                        <p class="text-xs text-gray-400">Chấp nhận JPG, PNG, GIF dưới 2MB</p>
                    </div>

                    <!-- Image Preview -->
                    <div class="w-full flex flex-col items-center justify-center" x-show="photoPreview" x-cloak>
                        <img :src="photoPreview" class="max-h-48 rounded-xl object-cover shadow-sm mb-3">
                        <button type="button" 
                                @click="photoPreview = null; photoName = null; document.getElementById('image').value = ''"
                                class="text-xs font-bold text-red-500 hover:text-red-700 flex items-center gap-1">
                            <i class="fa-solid fa-trash-can"></i> Gỡ ảnh và chọn lại
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?php echo URLROOT; ?>/pet" 
                   class="px-6 py-3 border border-gray-200 rounded-xl text-sm font-bold text-gray-500 hover:text-gray-800 hover:bg-slate-50 transition">
                    Hủy bỏ
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-primary hover:bg-indigo-700 text-white rounded-xl text-sm font-bold transition shadow-md hover:shadow-primary/20">
                    Lưu thông tin
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
