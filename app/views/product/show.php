<?php require APPROOT . '/views/inc/header.php'; ?>
<?php $product = $data['product']; ?>

<div class="bg-white">
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
                        <a href="<?php echo URLROOT; ?>/product" class="hover:text-primary">Cửa hàng</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-xs mx-2"></i>
                        <span class="text-gray-900"><?php echo $product->category_name; ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
            <!-- Image gallery -->
            <div class="flex flex-col-reverse">
                <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden sm:aspect-w-4 sm:aspect-h-3">
                    <img src="<?php echo !empty($product->image) ? URLROOT . '/public/images/' . $product->image : 'https://placehold.co/600x600?text=No+Image'; ?>" alt="<?php echo $product->name; ?>" class="w-full h-full object-center object-cover">
                </div>
            </div>

            <!-- Product info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900"><?php echo $product->name; ?></h1>
                
                <div class="mt-3">
                    <h2 class="sr-only">Thông tin sản phẩm</h2>
                    <p class="text-3xl text-primary font-bold"><?php echo number_format($product->price, 0, ',', '.'); ?> đ</p>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Mô tả</h3>
                    <div class="text-base text-gray-700 space-y-6">
                        <p><?php echo nl2br($product->description); ?></p>
                    </div>
                </div>

                <div class="mt-6 flex items-center">
                    <i class="fa-solid fa-check-circle text-green-500 mr-2"></i>
                    <p class="text-sm text-gray-500">Tình trạng: 
                        <?php if($product->stock_quantity > 0): ?>
                            <span class="font-medium text-green-600">Còn hàng (<?php echo $product->stock_quantity; ?>)</span>
                        <?php else: ?>
                            <span class="font-medium text-red-600">Hết hàng</span>
                        <?php endif; ?>
                    </p>
                </div>

                <?php if(isLoggedIn()): ?>
                <?php $isWished = isset($_SESSION['wishlist']) && in_array($product->id, $_SESSION['wishlist']); ?>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <!-- Add to Cart form -->
                    <form action="<?php echo URLROOT; ?>/cart/add/<?php echo $product->id; ?>" method="POST" class="flex items-center gap-3 flex-1">
                        <div class="flex items-center border border-gray-300 rounded-md">
                            <button type="button" onclick="document.getElementById('quantity').stepDown()" class="px-4 py-2 text-gray-600 hover:text-gray-900 focus:outline-none"><i class="fa-solid fa-minus"></i></button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product->stock_quantity; ?>" class="w-16 text-center border-none focus:ring-0 text-gray-900 font-medium">
                            <button type="button" onclick="document.getElementById('quantity').stepUp()" class="px-4 py-2 text-gray-600 hover:text-gray-900 focus:outline-none"><i class="fa-solid fa-plus"></i></button>
                        </div>
                        <button type="submit" class="flex-1 bg-primary border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-300 shadow-md hover:shadow-lg">
                            <i class="fa-solid fa-cart-plus mr-2"></i> Thêm vào giỏ
                        </button>
                    </form>

                    <!-- Wishlist Button -->
                    <button id="wishlist-btn-detail" onclick="toggleDetailWishlist(<?php echo $product->id; ?>)"
                            class="flex items-center justify-center gap-2 px-6 py-3 rounded-md border-2 font-bold text-base transition-all duration-300 <?php echo $isWished ? 'border-red-400 bg-red-50 text-red-500 hover:bg-red-100' : 'border-gray-300 text-gray-500 hover:border-red-400 hover:text-red-500 hover:bg-red-50'; ?>">
                        <i id="wishlist-icon-detail" class="<?php echo $isWished ? 'fa-solid' : 'fa-regular'; ?> fa-heart text-lg"></i>
                        <span id="wishlist-label-detail"><?php echo $isWished ? 'Đã yêu thích' : 'Yêu thích'; ?></span>
                    </button>
                </div>

                <?php else: ?>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo URLROOT; ?>/auth/login" class="flex-1 bg-primary border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 transition duration-300 shadow-md hover:shadow-lg">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Đăng nhập để mua hàng
                    </a>
                    <a href="<?php echo URLROOT; ?>/auth/login"
                       class="flex items-center justify-center gap-2 px-6 py-3 rounded-md border-2 border-gray-300 text-gray-500 font-bold hover:border-red-400 hover:text-red-500 hover:bg-red-50 transition-all duration-300">
                        <i class="fa-regular fa-heart text-lg"></i>
                        <span>Yêu thích</span>
                    </a>
                </div>
                <?php endif; ?>

                <script>
                async function toggleDetailWishlist(id) {
                    const btn  = document.getElementById('wishlist-btn-detail');
                    const icon = document.getElementById('wishlist-icon-detail');
                    const lbl  = document.getElementById('wishlist-label-detail');
                    try {
                        const res  = await fetch(`<?php echo URLROOT; ?>/wishlist/toggle/${id}`);
                        const data = await res.json();

                        // Update navbar badge
                        const badge = document.getElementById('wishlist-badge');
                        if (badge) {
                            badge.innerText = data.count;
                            data.count === 0 ? badge.classList.add('hidden') : badge.classList.remove('hidden');
                        }

                        if (data.status === 'added') {
                            icon.className = 'fa-solid fa-heart text-lg';
                            lbl.textContent = 'Đã yêu thích';
                            btn.className = btn.className.replace('border-gray-300 text-gray-500 hover:border-red-400 hover:text-red-500 hover:bg-red-50', 'border-red-400 bg-red-50 text-red-500 hover:bg-red-100');
                        } else {
                            icon.className = 'fa-regular fa-heart text-lg';
                            lbl.textContent = 'Yêu thích';
                            btn.className = btn.className.replace('border-red-400 bg-red-50 text-red-500 hover:bg-red-100', 'border-gray-300 text-gray-500 hover:border-red-400 hover:text-red-500 hover:bg-red-50');
                        }
                    } catch(e) { console.error(e); }
                }
                </script>

                <!-- Additional details -->
                <section aria-labelledby="details-heading" class="mt-12">
                    <h2 id="details-heading" class="sr-only">Chi tiết thêm</h2>
                    <div class="border-t divide-y divide-gray-200">
                        <div class="py-6">
                            <h3 class="text-sm font-medium text-gray-900">Cam kết từ PETSHOP</h3>
                            <ul class="mt-4 list-disc pl-5 space-y-2 text-sm text-gray-500">
                                <li>Sản phẩm chính hãng 100%</li>
                                <li>Giao hàng nhanh chóng trong 2h tại nội thành</li>
                                <li>Đổi trả miễn phí trong vòng 7 ngày nếu lỗi từ NSX</li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Product Reviews Section -->
        <div class="mt-16 border-t border-gray-200 pt-10">
            <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">Đánh giá khách hàng</h2>
            
            <div class="mt-6 flex items-center">
                <div class="flex items-center text-yellow-400 text-xl">
                    <?php 
                        $avg = $data['ratingInfo']['avg'];
                        for($i = 1; $i <= 5; $i++) {
                            if($i <= $avg) echo '<i class="fa-solid fa-star"></i>';
                            elseif($i - 0.5 <= $avg) echo '<i class="fa-solid fa-star-half-stroke"></i>';
                            else echo '<i class="fa-regular fa-star text-gray-300"></i>';
                        }
                    ?>
                </div>
                <p class="ml-3 text-lg font-bold text-gray-900"><?php echo $avg; ?> trên 5 sao</p>
                <p class="ml-3 text-sm text-gray-500">(Dựa trên <?php echo $data['ratingInfo']['count']; ?> đánh giá)</p>
            </div>

            <?php if(isset($_SESSION['review_success'])): ?>
                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <?php echo $_SESSION['review_success']; unset($_SESSION['review_success']); ?>
                </div>
            <?php endif; ?>

            <div class="mt-10 lg:grid lg:grid-cols-12 lg:gap-x-8">
                <div class="lg:col-span-4">
                    <h3 class="text-lg font-medium text-gray-900">Chia sẻ cảm nhận của bạn</h3>
                    <p class="mt-1 text-sm text-gray-600">Nếu bạn đã từng dùng sản phẩm này, hãy chia sẻ trải nghiệm với những người mua khác nhé.</p>
                    
                    <?php if(isLoggedIn()): ?>
                        <form action="<?php echo URLROOT; ?>/product/addReview/<?php echo $product->id; ?>" method="POST" class="mt-6">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Đánh giá của bạn</label>
                                <div class="flex items-center space-x-2 text-yellow-400 text-2xl" x-data="{ rating: 5, hoverRating: 0 }">
                                    <template x-for="i in 5">
                                        <i class="cursor-pointer transition-colors" 
                                           :class="i <= (hoverRating || rating) ? 'fa-solid fa-star' : 'fa-regular fa-star text-gray-300'"
                                           @mouseover="hoverRating = i"
                                           @mouseleave="hoverRating = 0"
                                           @click="rating = i"></i>
                                    </template>
                                    <input type="hidden" name="rating" x-model="rating">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Bình luận</label>
                                <textarea id="comment" name="comment" rows="4" class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md" required placeholder="Sản phẩm rất tuyệt vời..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-white border border-gray-300 rounded-md py-2 px-4 flex items-center justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                                Gửi đánh giá
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="mt-6 bg-gray-50 p-4 rounded-md text-center">
                            <p class="text-sm text-gray-600 mb-3">Vui lòng đăng nhập để viết đánh giá.</p>
                            <a href="<?php echo URLROOT; ?>/auth/login" class="inline-block bg-primary text-white font-medium px-4 py-2 rounded text-sm hover:bg-indigo-700">Đăng nhập ngay</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-16 lg:mt-0 lg:col-span-8 lg:col-start-5">
                    <div class="flow-root">
                        <div class="-my-12 divide-y divide-gray-200">
                            <?php if(empty($data['reviews'])): ?>
                                <p class="py-12 text-gray-500 italic text-center">Chưa có đánh giá nào cho sản phẩm này.</p>
                            <?php else: ?>
                                <?php foreach($data['reviews'] as $review): ?>
                                    <div class="py-12">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-tr from-primary to-blue-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                                <?php echo strtoupper(substr($review->user_name, 0, 1)); ?>
                                            </div>
                                            <div class="ml-4">
                                                <h4 class="text-sm font-bold text-gray-900"><?php echo $review->user_name; ?></h4>
                                                <div class="mt-1 flex items-center">
                                                    <div class="flex items-center text-yellow-400 text-sm">
                                                        <?php 
                                                            for($i = 1; $i <= 5; $i++) {
                                                                if($i <= $review->rating) echo '<i class="fa-solid fa-star"></i>';
                                                                else echo '<i class="fa-regular fa-star text-gray-300"></i>';
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 space-y-6 text-base italic text-gray-600">
                                            <p>"<?php echo nl2br(htmlspecialchars($review->comment)); ?>"</p>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-400">
                                            <?php echo date('d/m/Y H:i', strtotime($review->created_at)); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
