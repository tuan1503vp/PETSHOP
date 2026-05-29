<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="bg-white" x-data="{ openZoom: false, zoomImg: '' }">
    <!-- Hero Section cho Dịch Vụ -->
    <div class="relative bg-indigo-800">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1541364983171-a8ba01e95cfc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Pet grooming">
            <div class="absolute inset-0 bg-indigo-800 mix-blend-multiply" aria-hidden="true"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Dịch Vụ Chăm Sóc & Khám Bệnh</h1>
            <p class="mt-6 max-w-3xl mx-auto text-xl text-indigo-100">Bác sĩ thú y tận tâm, dịch vụ grooming chuyên nghiệp giúp thú cưng của bạn luôn khỏe mạnh và xinh đẹp.</p>
        </div>
    </div>

    <!-- Nội dung chính -->
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <?php flash('booking_success'); ?>

        <?php if(isset($data['categories'])): ?>
            <!-- HIỂN THỊ DANH MỤC -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Chọn Nhóm Dịch Vụ</h2>
                <p class="mt-4 text-lg text-gray-500">Vui lòng chọn loại dịch vụ bạn đang quan tâm</p>
            </div>
            
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach($data['categories'] as $category): ?>
                    <a href="<?php echo URLROOT; ?>/service/index/<?php echo $category->id; ?>" 
                       class="group relative bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="p-8 text-center">
                            <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-indigo-100 text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                <i class="fa-solid <?php 
                                    // Gán icon dựa trên tên hoặc mặc định
                                    if(strpos(strtolower($category->name), 'khám') !== false) echo 'fa-stethoscope';
                                    elseif(strpos(strtolower($category->name), 'grooming') !== false || strpos(strtolower($category->name), 'chăm sóc') !== false) echo 'fa-scissors';
                                    elseif(strpos(strtolower($category->name), 'hotel') !== false || strpos(strtolower($category->name), 'khách sạn') !== false) echo 'fa-hotel';
                                    else echo 'fa-paw';
                                ?> text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors"><?php echo $category->name; ?></h3>
                            <p class="mt-4 text-gray-500 text-sm line-clamp-2"><?php echo $category->description; ?></p>
                            <div class="mt-6 flex items-center justify-center text-primary font-semibold">
                                Xem chi tiết <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- HIỂN THỊ DỊCH VỤ THEO DANH MỤC -->
            <div class="flex items-center mb-10">
                <a href="<?php echo URLROOT; ?>/service" class="text-gray-500 hover:text-primary mr-4 transition-colors">
                    <i class="fa-solid fa-arrow-left text-2xl"></i>
                </a>
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900"><?php echo $data['category']->name; ?></h2>
                    <p class="text-gray-500"><?php echo count($data['services']); ?> dịch vụ sẵn có</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
                <?php foreach($data['services'] as $service) : ?>
                    <div class="flex flex-col rounded-2xl shadow-xl overflow-hidden md:flex-row hover:shadow-2xl transition-shadow duration-300 bg-white border border-gray-100 p-4 md:items-center gap-6">
                        <div class="flex-shrink-0 w-full md:w-[320px] h-[200px] md:h-[200px] rounded-xl overflow-hidden relative bg-slate-900 border border-gray-100 shadow-sm cursor-zoom-in group flex items-center justify-center"
                             @click="openZoom = true; zoomImg = '<?php echo !empty($service->image) ? URLROOT . '/public/images/' . $service->image : 'https://placehold.co/400x400?text=' . urlencode($service->name); ?>'">
                            <!-- Blurred Background Image to fill empty spaces -->
                            <img class="absolute inset-0 w-full h-full object-cover filter blur-xl opacity-50 scale-110 select-none pointer-events-none" src="<?php echo !empty($service->image) ? URLROOT . '/public/images/' . $service->image : 'https://placehold.co/400x400?text=' . urlencode($service->name); ?>" alt="">
                            <!-- Foreground Full Image -->
                            <img class="relative z-10 max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-102" src="<?php echo !empty($service->image) ? URLROOT . '/public/images/' . $service->image : 'https://placehold.co/400x400?text=' . urlencode($service->name); ?>" alt="<?php echo $service->name; ?>">
                            <!-- Zoom Indicator Overlay -->
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center z-20">
                                <i class="fa-solid fa-magnifying-glass-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-secondary uppercase tracking-widest">
                                    <?php echo $service->category_name; ?>
                                </p>
                                <div class="block mt-2">
                                    <p class="text-xl font-semibold text-gray-900"><?php echo $service->name; ?></p>
                                    <p class="mt-3 text-base text-gray-500 line-clamp-3"><?php echo $service->description; ?></p>
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-50">
                                <div>
                                    <?php if(strpos(mb_strtolower($service->category_name), 'khám') !== false || strpos(mb_strtolower($service->category_name), 'chữa') !== false): ?>
                                        <p class="text-xl font-bold text-primary">Liên hệ</p>
                                        <p class="text-xs text-gray-400 italic">Bác sĩ sẽ báo giá sau</p>
                                    <?php else: ?>
                                        <p class="text-xl font-bold text-primary"><?php echo number_format($service->price, 0, ',', '.'); ?>đ</p>
                                        <p class="text-xs text-gray-400 italic"><i class="fa-regular fa-clock"></i> ~<?php echo $service->duration_minutes; ?> phút</p>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo URLROOT; ?>/service/book/<?php echo $service->id; ?>" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-primary hover:bg-indigo-700 focus:outline-none transition-all hover:scale-105">
                                    Đặt lịch ngay
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if(empty($data['services'])): ?>
                <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                    <i class="fa-solid fa-folder-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900">Chưa có dịch vụ nào trong mục này</h3>
                    <p class="mt-2 text-gray-500">Chúng tôi sẽ sớm cập nhật các dịch vụ cho nhóm này.</p>
                    <a href="<?php echo URLROOT; ?>/service" class="mt-6 inline-block text-primary font-bold hover:underline">Quay lại danh sách nhóm</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <!-- Lightbox Modal for Infographic Zooming -->
    <div x-show="openZoom" x-cloak 
         class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/85 backdrop-blur-md p-4 transition-all duration-300"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="openZoom = false">
        <div class="absolute inset-0 cursor-zoom-out" @click="openZoom = false"></div>
        <div class="relative max-w-4xl w-full bg-white rounded-3xl overflow-hidden shadow-2xl p-3 z-10" @click.stop>
            <button @click="openZoom = false" class="absolute top-6 right-6 w-12 h-12 rounded-full bg-black/60 hover:bg-black text-white flex items-center justify-center transition-all z-50 hover:scale-110 shadow-lg">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <div class="overflow-y-auto max-h-[80vh] rounded-2xl bg-gray-50 flex items-center justify-center p-2">
                <img :src="zoomImg" class="max-w-full h-auto max-h-[75vh] object-contain rounded-xl shadow-md">
            </div>
            <div class="p-3 text-center text-xs text-gray-500 font-medium">
                <i class="fa-solid fa-info-circle mr-1"></i> Nhấn ESC hoặc click ra ngoài để đóng xem ảnh
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
