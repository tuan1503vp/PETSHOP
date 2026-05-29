<?php if(!isset($_GET['ajax'])) require APPROOT . '/views/inc/header.php'; ?>

<?php
if (!function_exists('buildProductUrl')) {
    function buildProductUrl($changes = [], $data) {
        $params = $data['params'];
        foreach ($changes as $key => $val) {
            $params[$key] = $val;
        }
        return URLROOT . '/product?' . http_build_query($params);
    }
}
?>

<div class="bg-gradient-to-b from-indigo-50/20 via-white to-indigo-50/10 min-h-screen">
    <!-- Premium Cyberpunk/Tech Inspired Banner -->
    <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-950 relative overflow-hidden py-20">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(99,102,241,0.15),transparent_40%)]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-primary/20 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-secondary/10 rounded-full blur-[100px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 via-pink-400 to-amber-300 tracking-tight mb-4 animate-pulse pb-2">Cửa Hàng Thú Cưng</h1>
            <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto font-medium leading-relaxed">Hàng ngàn sản phẩm chất lượng cao, thức ăn dinh dưỡng và phụ kiện thời thượng cho người bạn nhỏ của bạn.</p>
        </div>
    </div>

    <div id="catalog" class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Search & Filter Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-8 mb-8 gap-4 border-b border-gray-100">
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Duyệt sản phẩm</p>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight">Tìm Kiếm & Lọc</h2>
            </div>
            
            <form id="searchForm" action="<?php echo URLROOT; ?>/product" method="GET" class="flex w-full md:w-auto gap-3">
                <input type="hidden" name="category" id="filter_category" value="<?php echo htmlspecialchars($data['params']['category']); ?>">
                <input type="hidden" name="sort" id="filter_sort" value="<?php echo htmlspecialchars($data['params']['sort']); ?>">
                <input type="hidden" name="target_pet" id="filter_target_pet" value="<?php echo htmlspecialchars($data['params']['target_pet']); ?>">
                <input type="hidden" name="price_min" id="filter_price_min" value="<?php echo htmlspecialchars($data['params']['price_min']); ?>">
                <input type="hidden" name="price_max" id="filter_price_max" value="<?php echo htmlspecialchars($data['params']['price_max']); ?>">
                
                <div class="relative flex-1 md:w-72">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" id="searchInput" value="<?php echo htmlspecialchars($data['params']['search']); ?>"
                           class="block w-full pl-12 pr-12 py-3.5 border border-gray-200 rounded-2xl bg-white/80 backdrop-blur-sm text-sm text-gray-850 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-inner" 
                           placeholder="Tìm tên sản phẩm...">
                    
                    <?php if(!empty($data['params']['search'])): ?>
                        <a href="<?php echo URLROOT; ?>/product?category=<?php echo $data['params']['category']; ?>&sort=<?php echo $data['params']['sort']; ?>" 
                           class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-500 transition clear-search" title="Xóa tìm kiếm">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <button type="submit" class="bg-gradient-to-r from-primary to-indigo-600 text-white px-8 py-3.5 rounded-2xl hover:shadow-xl hover:shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all font-black text-sm">
                    Tìm kiếm
                </button>
            </form>
        </div>

        <script>
            // Advanced Seamless Catalog Filtering (AJAX / Fetch API)
            let searchTimer;
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');

            async function fetchCatalog(url) {
                const catalogContent = document.getElementById('catalog-content');
                if(!catalogContent) return;

                catalogContent.style.opacity = '0.4';
                catalogContent.style.pointerEvents = 'none';

                window.history.pushState({}, '', url);

                const fetchUrl = url.includes('?') ? `${url}&ajax=1` : `${url}?ajax=1`;
                try {
                    const res = await fetch(fetchUrl);
                    const html = await res.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('catalog-content');
                    
                    if(newContent) {
                        catalogContent.innerHTML = newContent.innerHTML;
                        if(typeof reveal === 'function') {
                            setTimeout(reveal, 50);
                        }
                    }
                } catch(err) {
                    window.location.href = url;
                } finally {
                    catalogContent.style.opacity = '1';
                    catalogContent.style.pointerEvents = 'auto';
                }
            }

            function submitFilters() {
                const search = encodeURIComponent(document.getElementById('searchInput').value);
                const category = document.getElementById('filter_category').value;
                const sort = document.getElementById('filter_sort').value;
                const target_pet = document.getElementById('filter_target_pet').value;
                const price_min = document.getElementById('filter_price_min').value;
                const price_max = document.getElementById('filter_price_max').value;
                
                const url = `<?php echo URLROOT; ?>/product?category=${category}&sort=${sort}&search=${search}&target_pet=${target_pet}&price_min=${price_min}&price_max=${price_max}`;
                fetchCatalog(url);
            }

            function applyCustomPrice() {
                const min = document.getElementById('custom_price_min').value;
                const max = document.getElementById('custom_price_max').value;
                
                document.getElementById('filter_price_min').value = min;
                document.getElementById('filter_price_max').value = max;
                submitFilters();
            }

            // Gõ tìm kiếm tự động load
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    submitFilters();
                }, 500);
            });

            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                submitFilters();
            });

            // Lắng nghe click danh mục, đối tượng, khoảng giá và nút xóa tìm kiếm
            document.addEventListener('click', (e) => {
                const catLink = e.target.closest('aside a');
                const clearBtn = e.target.closest('.clear-search');
                if(catLink) {
                    e.preventDefault();
                    fetchCatalog(catLink.href);
                    
                    // Cập nhật các hidden inputs từ URL mới để đồng bộ hóa
                    try {
                        const urlParams = new URLSearchParams(catLink.href.split('?')[1]);
                        document.getElementById('filter_category').value = urlParams.get('category') || '';
                        document.getElementById('filter_sort').value = urlParams.get('sort') || 'newest';
                        document.getElementById('filter_target_pet').value = urlParams.get('target_pet') || '';
                        document.getElementById('filter_price_min').value = urlParams.get('price_min') || '';
                        document.getElementById('filter_price_max').value = urlParams.get('price_max') || '';
                    } catch(err) {
                        console.error('Error syncing filter parameters:', err);
                    }
                } else if(clearBtn) {
                    e.preventDefault();
                    searchInput.value = '';
                    fetchCatalog(clearBtn.href);
                }
            });
        </script>

        <div id="catalog-content" class="flex flex-col lg:flex-row gap-10 transition-opacity duration-300">
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="space-y-6 sticky top-24 bg-white/60 backdrop-blur-md p-5 rounded-3xl border border-gray-100 shadow-sm overflow-y-auto" style="max-height: calc(100vh - 7rem);">
                    
                    <!-- Categories -->
                    <div>
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">
                            <i class="fa-solid fa-tags text-primary mr-1"></i> Danh Mục
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <a href="<?php echo buildProductUrl(['category' => ''], $data); ?>" 
                               class="px-3 py-1.5 rounded-full text-xs font-bold transition-all border <?php echo empty($data['params']['category']) ? 'bg-primary text-white border-primary shadow-md shadow-primary/30' : 'bg-white text-gray-600 hover:border-primary hover:text-primary border-gray-200'; ?>">
                                Tất cả
                            </a>
                            <?php foreach($data['categories'] as $cat): ?>
                                <a href="<?php echo buildProductUrl(['category' => $cat->id], $data); ?>" 
                                   class="px-3 py-1.5 rounded-full text-xs font-bold transition-all border <?php echo $data['params']['category'] == $cat->id ? 'bg-primary text-white border-primary shadow-md shadow-primary/30' : 'bg-white text-gray-600 hover:border-primary hover:text-primary border-gray-200'; ?>">
                                    <?php echo $cat->name; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>


                    <!-- Price Range (Khoảng Giá) -->
                    <div>
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">
                            <i class="fa-solid fa-coins text-primary mr-1"></i> Khoảng Giá
                        </h3>
                        <div class="flex flex-wrap lg:flex-col gap-2">
                            <!-- All Price -->
                            <?php 
                                $isAllPrices = empty($data['params']['price_min']) && empty($data['params']['price_max']);
                            ?>
                            <a href="<?php echo buildProductUrl(['price_min' => '', 'price_max' => ''], $data); ?>"
                               class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all flex items-center justify-between group <?php echo $isAllPrices ? 'bg-gradient-to-r from-primary to-indigo-600 text-white shadow-xl shadow-primary/20' : 'bg-white text-gray-600 hover:bg-indigo-50/50 hover:text-primary border border-gray-100 shadow-sm'; ?>">
                                <span>Mọi mức giá</span>
                                <?php if($isAllPrices): ?><i class="fa-solid fa-check text-[10px]"></i><?php endif; ?>
                            </a>
                            <!-- Under 100k -->
                            <?php 
                                $isUnder100k = $data['params']['price_min'] === '0' && $data['params']['price_max'] === '100000';
                            ?>
                            <a href="<?php echo buildProductUrl(['price_min' => '0', 'price_max' => '100000'], $data); ?>"
                               class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all flex items-center justify-between group <?php echo $isUnder100k ? 'bg-gradient-to-r from-primary to-indigo-600 text-white shadow-xl shadow-primary/20' : 'bg-white text-gray-600 hover:bg-indigo-50/50 hover:text-primary border border-gray-100 shadow-sm'; ?>">
                                <span>Dưới 100.000đ</span>
                                <?php if($isUnder100k): ?><i class="fa-solid fa-check text-[10px]"></i><?php endif; ?>
                            </a>
                            <!-- 100k - 500k -->
                            <?php 
                                $is100to500 = $data['params']['price_min'] === '100000' && $data['params']['price_max'] === '500000';
                            ?>
                            <a href="<?php echo buildProductUrl(['price_min' => '100000', 'price_max' => '500000'], $data); ?>"
                               class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all flex items-center justify-between group <?php echo $is100to500 ? 'bg-gradient-to-r from-primary to-indigo-600 text-white shadow-xl shadow-primary/20' : 'bg-white text-gray-600 hover:bg-indigo-50/50 hover:text-primary border border-gray-100 shadow-sm'; ?>">
                                <span>100.000đ - 500.000đ</span>
                                <?php if($is100to500): ?><i class="fa-solid fa-check text-[10px]"></i><?php endif; ?>
                            </a>
                            <!-- Over 500k -->
                            <?php 
                                $isOver500 = $data['params']['price_min'] === '500000' && empty($data['params']['price_max']);
                            ?>
                            <a href="<?php echo buildProductUrl(['price_min' => '500000', 'price_max' => ''], $data); ?>"
                               class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all flex items-center justify-between group <?php echo $isOver500 ? 'bg-gradient-to-r from-primary to-indigo-600 text-white shadow-xl shadow-primary/20' : 'bg-white text-gray-600 hover:bg-indigo-50/50 hover:text-primary border border-gray-100 shadow-sm'; ?>">
                                <span>Trên 500.000đ</span>
                                <?php if($isOver500): ?><i class="fa-solid fa-check text-[10px]"></i><?php endif; ?>
                            </a>
                        </div>

                        <!-- Custom price range form inputs -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Tự nhập khoảng giá (đ)</p>
                            <div class="flex items-center gap-1.5 mb-2.5">
                                <input type="number" id="custom_price_min" placeholder="Từ" 
                                       value="<?php echo htmlspecialchars($data['params']['price_min']); ?>"
                                       class="w-full px-2.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-primary/50 text-center shadow-inner placeholder:text-gray-300">
                                <span class="text-gray-400 text-xs">-</span>
                                <input type="number" id="custom_price_max" placeholder="Đến" 
                                       value="<?php echo htmlspecialchars($data['params']['price_max']); ?>"
                                       class="w-full px-2.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-primary/50 text-center shadow-inner placeholder:text-gray-300">
                            </div>
                            <button onclick="applyCustomPrice()" 
                                    class="w-full py-2.5 bg-slate-900 hover:bg-black text-white text-xs font-black rounded-xl transition hover:shadow-lg active:scale-98">
                                Áp Dụng
                            </button>
                        </div>
                    </div>

                    <!-- Sort -->
                    <div>
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">
                            <i class="fa-solid fa-arrow-down-wide-short text-primary mr-1"></i> Sắp Xếp
                        </h3>
                        <select onchange="document.getElementById('filter_sort').value = this.value; submitFilters();" class="block w-full pl-4 pr-10 py-3 text-sm font-black text-gray-700 bg-white border border-gray-100 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary cursor-pointer transition-all">
                            <option value="newest" <?php echo $data['params']['sort'] == 'newest' ? 'selected' : ''; ?>>✨ Mới nhất</option>
                            <option value="price_asc" <?php echo $data['params']['sort'] == 'price_asc' ? 'selected' : ''; ?>>📈 Giá: Thấp đến Cao</option>
                            <option value="price_desc" <?php echo $data['params']['sort'] == 'price_desc' ? 'selected' : ''; ?>>📉 Giá: Cao đến Thấp</option>
                            <option value="oldest" <?php echo $data['params']['sort'] == 'oldest' ? 'selected' : ''; ?>>⏳ Cũ nhất</option>
                        </select>
                    </div>
                </div>
            </aside>

            <!-- Product Grid -->
            <div class="flex-1">
                <!-- Dynamic Title -->
                <div class="mb-6 flex justify-between items-end">
                    <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">
                        <?php 
                            if(!empty($data['params']['category'])) {
                                foreach($data['categories'] as $cat) {
                                    if($cat->id == $data['params']['category']) {
                                        echo $cat->name;
                                        break;
                                    }
                                }
                            } elseif(!empty($data['params']['search'])) {
                                echo 'Kết quả tìm kiếm: "' . htmlspecialchars($data['params']['search']) . '"';
                            } else {
                                echo 'Tất cả sản phẩm';
                            }
                        ?>
                    </h2>
                    <p class="text-xs md:text-sm text-gray-500 font-bold bg-indigo-50/50 border border-indigo-100 px-3.5 py-1.5 rounded-full"><?php echo count($data['products']); ?> sản phẩm</p>
                </div>

                <?php if(empty($data['products'])): ?>
                    <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-xl max-w-lg mx-auto p-8">
                        <i class="fa-solid fa-box-open text-6xl text-gray-300 mb-4 animate-bounce"></i>
                        <h3 class="text-xl font-black text-gray-900">Không tìm thấy sản phẩm nào</h3>
                        <p class="mt-2 text-gray-500 font-medium">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
                        <a href="<?php echo URLROOT; ?>/product" class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-black text-sm rounded-xl hover:bg-indigo-75 transition-all">Xóa bộ lọc</a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <?php foreach($data['products'] as $product): ?>
                            <div class="group relative flex flex-col bg-white border border-gray-100/80 rounded-[32px] shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden reveal">
                                
                                <!-- Image Container -->
                                <div class="relative w-full aspect-square overflow-hidden bg-slate-50 border-b border-gray-50">
                                    <img src="<?php echo !empty($product->image) ? URLROOT . '/public/images/' . $product->image : 'https://placehold.co/400x400?text=' . urlencode($product->name); ?>" 
                                         alt="<?php echo $product->name; ?>" 
                                         class="w-full h-full object-center object-cover group-hover:scale-108 transition-transform duration-700">
                                    
                                    <!-- Badges -->
                                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                                        <span class="px-3.5 py-1.5 bg-white/90 backdrop-blur-sm text-[9px] font-black uppercase tracking-widest text-slate-800 rounded-full shadow-sm">
                                            <?php echo $product->category_name ?? 'Phổ biến'; ?>
                                        </span>
                                    </div>

                                    <!-- Quick Wishlist Button -->
                                    <?php if(isLoggedIn()): ?>
                                    <div class="absolute top-4 right-4 translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300">
                                        <?php 
                                            $isWished = isset($_SESSION['wishlist']) && in_array($product->id, $_SESSION['wishlist']);
                                        ?>
                                        <button onclick="toggleStoreWishlist(<?php echo $product->id; ?>, this)" 
                                                class="w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-md transition-transform hover:scale-110 active:scale-95 <?php echo $isWished ? 'text-red-500' : 'text-gray-400 hover:text-red-500'; ?>" title="Yêu thích">
                                            <i class="<?php echo $isWished ? 'fa-solid' : 'fa-regular'; ?> fa-heart text-base transition-all duration-300"></i>
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Hover Overlay Info -->
                                <div class="absolute inset-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md z-20 opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col p-6 translate-y-8 group-hover:translate-y-0 pointer-events-none group-hover:pointer-events-auto">
                                    <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2 line-clamp-2">
                                        <?php echo $product->name; ?>
                                    </h3>
                                    <div class="overflow-y-auto flex-1 mb-4 pr-2">
                                        <p class="text-sm text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
                                            <?php echo $product->description; ?>
                                        </p>
                                    </div>
                                    <div class="pt-4 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between mt-auto">
                                        <p class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">
                                            <?php echo number_format($product->price, 0, ',', '.'); ?> <span class="text-xs">đ</span>
                                        </p>
                                        <a href="<?php echo URLROOT; ?>/product/show/<?php echo $product->id; ?>" class="px-4 py-2 bg-primary text-white text-xs font-bold rounded-xl hover:bg-indigo-600 transition-colors shadow-lg shadow-primary/30">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="p-6 flex flex-col flex-1 justify-between gap-4 bg-white">
                                    <div class="space-y-2">
                                        <h3 class="text-base font-black text-gray-800 group-hover:text-primary transition-colors line-clamp-1">
                                            <a href="<?php echo URLROOT; ?>/product/show/<?php echo $product->id; ?>">
                                                <?php echo $product->name; ?>
                                            </a>
                                        </h3>
                                        <p class="text-xs text-slate-400 font-medium leading-relaxed line-clamp-2">
                                            <?php echo $product->description; ?>
                                        </p>
                                        <div class="flex items-center text-yellow-400 text-[10px] gap-0.5">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <span class="text-slate-400 font-bold ml-1.5">(4.8)</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-50 bg-white">
                                        <p class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">
                                            <?php echo number_format($product->price, 0, ',', '.'); ?> <span class="text-xs">đ</span>
                                        </p>
                                        
                                        <?php if(isLoggedIn()): ?>
                                            <form action="<?php echo URLROOT; ?>/cart/add/<?php echo $product->id; ?>" method="POST">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" 
                                                        class="w-12 h-12 bg-gradient-to-r from-primary to-indigo-600 text-white rounded-2xl flex items-center justify-center hover:scale-110 hover:-rotate-6 transition-all duration-300 shadow-md shadow-primary/10 group/cart hover:shadow-primary/30"
                                                        title="Thêm vào giỏ hàng">
                                                    <i class="fa-solid fa-cart-plus text-base group-hover/cart:animate-bounce"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <a href="<?php echo URLROOT; ?>/auth/login" class="text-xs font-black text-primary hover:underline flex items-center gap-1">
                                                <i class="fa-solid fa-right-to-bracket text-xs"></i> Đăng nhập mua
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    async function toggleStoreWishlist(id, btnElem) {
        const url = `<?php echo URLROOT; ?>/wishlist/toggle/${id}`;
        try {
            const res = await fetch(url);
            const data = await res.json();
            
            const badge = document.getElementById('wishlist-badge');
            if(badge) {
                badge.innerText = data.count;
                if(data.count === 0) {
                    badge.classList.add('hidden');
                } else {
                    badge.classList.remove('hidden');
                    badge.classList.add('animate-bounce');
                    setTimeout(() => badge.classList.remove('animate-bounce'), 1000);
                }
            }

            const icon = btnElem.querySelector('i');
            if(data.status === 'added') {
                btnElem.classList.remove('text-gray-400', 'hover:text-red-500');
                btnElem.classList.add('text-red-500');
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid', 'animate-pulse');
                setTimeout(() => icon.classList.remove('animate-pulse'), 1000);
            } else {
                btnElem.classList.remove('text-red-500');
                btnElem.classList.add('text-gray-400', 'hover:text-red-500');
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
            }
        } catch(err) {
            console.error(err);
        }
    }
</script>

<?php if(!isset($_GET['ajax'])) require APPROOT . '/views/inc/footer.php'; ?>
