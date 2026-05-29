<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="bg-gradient-to-b from-indigo-50/40 via-white to-indigo-50/20 min-h-[calc(100vh-64px)] py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex items-center gap-3 border-b border-gray-100 pb-8 mb-12">
            <span class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner">
                <i class="fa-solid fa-basket-shopping text-xl"></i>
            </span>
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Giỏ Hàng Của Bạn</h1>
                <p class="text-gray-500 mt-1 font-medium">Quản lý và hoàn tất đặt mua các sản phẩm yêu thích.</p>
            </div>
        </div>

        <?php flash('cart_success'); ?>

        <?php if(empty($data['cart'])) : ?>
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-100 max-w-xl mx-auto p-12 text-center reveal">
                <div class="w-24 h-24 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-inner animate-bounce">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-2">Giỏ hàng trống</h2>
                <p class="text-gray-500 mb-8 font-medium leading-relaxed">Bạn chưa chọn sản phẩm nào để mua. Hãy dạo quanh cửa hàng và rinh về những món đồ ưng ý nhé!</p>
                <a href="<?php echo URLROOT; ?>/product" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary to-secondary text-white font-black px-8 py-4 rounded-2xl hover:shadow-xl hover:shadow-primary/20 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                    <i class="fa-solid fa-store"></i> Khám phá cửa hàng
                </a>
            </div>
        <?php else : ?>
            <div class="lg:grid lg:grid-cols-12 lg:gap-10 lg:items-start reveal">
                
                <!-- Left: Cart Items List -->
                <div class="lg:col-span-8">
                    <form action="<?php echo URLROOT; ?>/cart/update" method="POST">
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden p-6 md:p-8 space-y-6">
                            
                            <ul role="list" class="divide-y divide-gray-100">
                                <?php foreach($data['cart'] as $item) : ?>
                                    <li class="flex py-6 first:pt-0 last:pb-0 gap-4 sm:gap-6 relative group">
                                        
                                        <!-- Image Wrapper -->
                                        <div class="flex-shrink-0 w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border border-gray-100 overflow-hidden bg-slate-50 shadow-inner relative">
                                            <img src="<?php echo !empty($item['image']) ? URLROOT . '/public/images/' . $item['image'] : 'https://placehold.co/200x200?text=Image'; ?>" 
                                                 alt="<?php echo $item['name']; ?>" 
                                                 class="w-full h-full object-center object-cover group-hover:scale-105 transition-transform duration-500">
                                        </div>

                                        <!-- Details Block -->
                                        <div class="flex-1 flex flex-col justify-between">
                                            <div class="flex justify-between items-start gap-4">
                                                <div>
                                                    <h3 class="text-base sm:text-lg font-black text-gray-800 hover:text-primary transition-colors line-clamp-2">
                                                        <a href="<?php echo URLROOT; ?>/product/show/<?php echo $item['id']; ?>">
                                                            <?php echo $item['name']; ?>
                                                        </a>
                                                    </h3>
                                                    <p class="mt-1 text-base font-black text-primary"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</p>
                                                </div>
                                                
                                                <!-- Remove Button -->
                                                <a href="<?php echo URLROOT; ?>/cart/remove/<?php echo $item['id']; ?>" 
                                                   class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm flex-shrink-0"
                                                   title="Xóa sản phẩm">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </a>
                                            </div>

                                            <div class="flex items-center justify-between mt-4">
                                                <!-- Custom Quantity Input -->
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Số lượng:</span>
                                                    <input type="number" id="quantity-<?php echo $item['id']; ?>" name="quantity[<?php echo $item['id']; ?>]" 
                                                           value="<?php echo $item['quantity']; ?>" min="1" 
                                                           class="border border-gray-200 rounded-xl py-1.5 px-3 text-sm font-bold text-gray-800 text-center w-16 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition shadow-inner">
                                                </div>
                                                
                                                <!-- Line Subtotal -->
                                                <p class="text-sm font-bold text-gray-400">
                                                    Tổng: <span class="text-gray-850 font-black text-base"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ</span>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <!-- Actions Footer -->
                            <div class="border-t border-gray-100 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 border border-gray-200 text-gray-700 font-bold rounded-2xl hover:bg-gray-50 hover:border-gray-300 transition shadow-sm text-sm">
                                    <i class="fa-solid fa-rotate-right"></i> Cập nhật giỏ hàng
                                </button>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-400">Tạm tính giỏ hàng</p>
                                    <p class="text-2xl font-black text-primary mt-1"><?php echo number_format($data['total'], 0, ',', '.'); ?> đ</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Right: Order Summary Sidebar -->
                <div class="lg:col-span-4 mt-10 lg:mt-0 sticky top-24">
                    <div class="bg-slate-900 text-white rounded-3xl p-8 border border-slate-800 shadow-2xl space-y-6">
                        <h2 class="text-lg font-black tracking-tight border-b border-slate-800 pb-4">Tóm tắt đơn hàng</h2>

                        <dl class="space-y-4">
                            <div class="flex items-center justify-between text-sm">
                                <dt class="text-slate-400">Tổng tiền hàng</dt>
                                <dd class="font-bold text-slate-200"><?php echo number_format($data['total'], 0, ',', '.'); ?> đ</dd>
                            </div>
                            <div class="flex items-center justify-between text-sm border-t border-slate-800 pt-4">
                                <dt class="text-slate-400">Phí vận chuyển</dt>
                                <dd class="font-bold text-emerald-400 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check"></i> Miễn phí giao hàng
                                </dd>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-800 pt-4">
                                <dt class="text-base font-bold text-slate-300">Tổng thanh toán</dt>
                                <dd class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-orange-400 to-pink-400">
                                    <?php echo number_format($data['total'], 0, ',', '.'); ?> đ
                                </dd>
                            </div>
                        </dl>

                        <div class="pt-4">
                            <a href="<?php echo URLROOT; ?>/cart/checkout" 
                               class="w-full bg-gradient-to-r from-pink-500 to-secondary text-white font-black text-base py-4 rounded-2xl shadow-xl shadow-secondary/20 hover:shadow-secondary/40 hover:scale-[1.03] active:scale-[0.97] transition-all block text-center">
                                <i class="fa-solid fa-credit-card mr-2"></i> Tiến hành thanh toán
                            </a>
                        </div>

                        <!-- Trusted checkout badge -->
                        <div class="pt-4 border-t border-slate-800 flex items-center justify-center gap-2 text-[10px] text-slate-500 uppercase tracking-widest font-bold">
                            <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                            <span>Thanh toán an toàn bảo mật</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
