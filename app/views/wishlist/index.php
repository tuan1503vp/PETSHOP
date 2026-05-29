<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="bg-gradient-to-b from-indigo-50/40 via-white to-indigo-50/20 min-h-screen py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 border-b border-gray-100 pb-8 mb-12">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center shadow-inner">
                        <i class="fa-solid fa-heart animate-pulse"></i>
                    </span>
                    Sản Phẩm Yêu Thích
                </h1>
                <p class="text-gray-500 mt-2 font-medium">Danh sách các sản phẩm bạn đã "thả tim" và lưu lại.</p>
            </div>
            <div>
                <a href="<?php echo URLROOT; ?>/product" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 text-gray-700 font-bold rounded-2xl hover:bg-gray-50 hover:border-gray-300 hover:text-gray-900 transition shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>

        <!-- Wishlist Content -->
        <?php if(empty($data['products'])): ?>
            <div class="text-center py-20 bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-100/80 max-w-xl mx-auto p-10 reveal">
                <div class="w-24 h-24 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-inner">
                    <i class="fa-regular fa-heart"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">Danh sách trống</h3>
                <p class="text-gray-500 mb-8 leading-relaxed font-medium">Bạn chưa thả tim sản phẩm nào. Hãy dạo quanh cửa hàng để tìm những món đồ ưng ý nhất cho bé yêu nhé!</p>
                <a href="<?php echo URLROOT; ?>/product" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary to-secondary text-white font-black px-8 py-4 rounded-2xl hover:shadow-xl hover:shadow-primary/20 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                    <i class="fa-solid fa-store"></i> Khám phá cửa hàng
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 reveal">
                <?php foreach($data['products'] as $product): ?>
                    <div class="group relative flex flex-col bg-white border border-gray-100/80 rounded-3xl shadow-sm hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-500 overflow-hidden">
                        
                        <!-- Image Container -->
                        <div class="relative w-full aspect-square overflow-hidden bg-slate-50 border-b border-gray-50">
                            <img src="<?php echo !empty($product->image) ? URLROOT . '/public/images/' . $product->image : 'https://placehold.co/400x400?text=' . urlencode($product->name); ?>" 
                                 alt="<?php echo $product->name; ?>" 
                                 class="w-full h-full object-center object-cover group-hover:scale-108 transition-transform duration-700">
                            
                            <!-- Category Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1.5 bg-white/90 backdrop-blur-sm text-[10px] font-black uppercase tracking-widest text-gray-800 rounded-full shadow-sm">
                                    <?php echo $product->category_name ?? 'Phổ biến'; ?>
                                </span>
                            </div>

                            <!-- Remove from wishlist -->
                            <button onclick="toggleWishlist(<?php echo $product->id; ?>, this.closest('.group'))" 
                                    class="absolute top-4 right-4 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all shadow-md"
                                    title="Xóa khỏi danh sách yêu thích">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>

                        <!-- Product Info & Actions -->
                        <div class="p-6 flex flex-col flex-1 justify-between gap-5 bg-white">
                            <div>
                                <h3 class="text-base font-bold text-gray-850 hover:text-primary transition-colors line-clamp-2 mb-2">
                                    <a href="<?php echo URLROOT; ?>/product/show/<?php echo $product->id; ?>">
                                        <?php echo $product->name; ?>
                                    </a>
                                </h3>
                                <p class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">
                                    <?php echo number_format($product->price, 0, ',', '.'); ?> <span class="text-xs">đ</span>
                                </p>
                            </div>
                            
                            <form action="<?php echo URLROOT; ?>/cart/add/<?php echo $product->id; ?>" method="POST" class="w-full">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-primary to-indigo-600 text-white rounded-2xl py-3.5 flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all font-black text-sm group/btn shadow-md">
                                    <i class="fa-solid fa-cart-plus group-hover/btn:animate-bounce"></i> Thêm vào giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    async function toggleWishlist(id, cardElem) {
        const url = `<?php echo URLROOT; ?>/wishlist/toggle/${id}`;
        const res = await fetch(url);
        const data = await res.json();
        
        const badge = document.getElementById('wishlist-badge');
        if(badge) {
            badge.innerText = data.count;
            if(data.count === 0) {
                badge.classList.add('hidden');
            } else {
                badge.classList.remove('hidden');
            }
        }

        if(data.status === 'removed' && cardElem) {
            cardElem.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
            cardElem.style.opacity = '0';
            cardElem.style.transform = 'scale(0.85)';
            setTimeout(() => {
                cardElem.remove();
                if(data.count === 0) {
                    window.location.reload(); // Tải lại để hiện thông báo chưa có sản phẩm
                }
            }, 500);
        }
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
