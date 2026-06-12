<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?php echo URLROOT; ?>" class="hover:text-primary"><i class="fa-solid fa-house mr-2"></i>Trang chủ</a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-xs mx-2"></i>
                    <span class="text-gray-900 font-semibold">Thú cưng của tôi</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                <i class="fa-solid fa-paw text-secondary"></i> Thú cưng của tôi
            </h1>
            <p class="mt-2 text-sm text-gray-500">Quản lý và theo dõi thông tin sức khỏe cho các bé cưng của bạn.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/pet/add" 
           class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-bold rounded-2xl text-white bg-gradient-to-r from-primary to-secondary hover:shadow-lg hover:shadow-primary/30 transition-all duration-300 hover:-translate-y-0.5">
            <i class="fa-solid fa-plus mr-2"></i> Thêm bé mới
        </a>
    </div>

    <!-- Alert / Message -->
    <?php flash('pet_message'); ?>

    <!-- Pets Grid -->
    <?php if (empty($data['pets'])): ?>
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-12 text-center max-w-xl mx-auto mt-6">
            <div class="w-20 h-20 bg-indigo-50 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-dog text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Bạn chưa thêm thú cưng nào</h3>
            <p class="mt-2 text-sm text-gray-500">Hãy thêm thú cưng của mình để lưu trữ thông tin và bắt đầu theo dõi sức khỏe tại cửa hàng của chúng tôi.</p>
            <div class="mt-8">
                <a href="<?php echo URLROOT; ?>/pet/add" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-bold rounded-2xl text-white bg-primary hover:bg-indigo-700 transition">
                    <i class="fa-solid fa-plus mr-2"></i> Thêm bé đầu tiên
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($data['pets'] as $pet): ?>
                <div class="bg-white rounded-[2rem] overflow-hidden border border-gray-100 hover:shadow-xl hover:shadow-gray-100/50 transition-all duration-300 flex flex-col group">
                    <!-- Pet Image -->
                    <div class="relative aspect-video bg-slate-100 overflow-hidden shrink-0">
                        <?php if (!empty($pet->image)): ?>
                            <img src="<?php echo URLROOT . '/public/images/' . $pet->image; ?>" 
                                 alt="<?php echo htmlspecialchars($pet->name); ?>" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                <i class="fa-solid fa-paw text-5xl"></i>
                                <span class="text-xs mt-2 font-bold uppercase tracking-wider text-slate-400">Không có hình ảnh</span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Pet Code Badge -->
                        <span class="absolute top-4 right-4 bg-white/90 backdrop-blur-md text-gray-800 px-3 py-1 rounded-xl text-xs font-black shadow-sm tracking-wider border border-white/20">
                            <?php echo $pet->pet_code; ?>
                        </span>
                    </div>

                    <!-- Pet Info Body -->
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-4">
                            <h2 class="text-xl font-bold text-gray-900 leading-tight"><?php echo htmlspecialchars($pet->name); ?></h2>
                            <p class="text-sm text-gray-500 font-medium mt-1">
                                <?php echo htmlspecialchars($pet->species); ?> 
                                <?php echo !empty($pet->breed) ? '• ' . htmlspecialchars($pet->breed) : ''; ?>
                            </p>
                        </div>

                        <!-- Pet Attributes -->
                        <div class="grid grid-cols-2 gap-3 mb-6 bg-slate-50/50 p-4 rounded-2xl border border-slate-100/50 text-xs text-gray-600 font-medium">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-cake-candles text-primary/80"></i>
                                <span><?php echo $pet->age; ?> tháng tuổi</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-venus-mars text-secondary/80"></i>
                                <span>
                                    <?php 
                                        if ($pet->gender == 'male') echo 'Đực';
                                        elseif ($pet->gender == 'female') echo 'Cái';
                                        else echo 'Chưa rõ';
                                    ?>
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-palette text-amber-500/80"></i>
                                <span><?php echo !empty($pet->color) ? htmlspecialchars($pet->color) : 'Chưa nhập'; ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-weight-scale text-emerald-500/80"></i>
                                <span><?php echo !empty($pet->weight) ? floatval($pet->weight) . ' kg' : 'Chưa rõ'; ?></span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-auto pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-2">
                            <a href="<?php echo URLROOT; ?>/pet/health_book/<?php echo $pet->id; ?>" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-black text-white bg-primary hover:bg-indigo-700 transition shadow-sm hover:shadow-primary/20">
                                <i class="fa-solid fa-book-medical mr-1.5"></i> Sổ Sức Khỏe
                            </a>
                            <div class="flex gap-2 shrink-0">
                                <a href="<?php echo URLROOT; ?>/pet/edit/<?php echo $pet->id; ?>" 
                                   class="inline-flex items-center justify-center p-2.5 rounded-xl border border-gray-200 text-gray-500 hover:text-primary hover:border-primary/30 hover:bg-slate-50 transition" 
                                   title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="<?php echo URLROOT; ?>/pet/delete/<?php echo $pet->id; ?>" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa bé <?php echo htmlspecialchars($pet->name); ?> khỏi hệ thống? Các nhật ký sức khỏe liên quan sẽ mất hết.');" 
                                   class="inline-flex items-center justify-center p-2.5 rounded-xl border border-red-100 text-red-500 hover:bg-red-50 transition" 
                                   title="Xóa">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
