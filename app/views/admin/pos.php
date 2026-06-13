<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="h-[calc(100vh-64px)] flex flex-col lg:flex-row overflow-hidden" x-data="posSystem()">
    <!-- Left Column: Product List -->
    <div class="flex-1 flex flex-col h-full overflow-hidden border-r border-gray-200">
        <!-- Search and Filter -->
        <div class="bg-white p-4 border-b border-gray-200 shadow-sm z-10 flex flex-col gap-3">
            <!-- Row 1: Search Input -->
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-barcode text-gray-400"></i>
                </div>
                <input type="text" x-model="searchQuery" class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-primary focus:ring-1 focus:ring-primary text-sm transition duration-150 ease-in-out" placeholder="Quét mã vạch hoặc tìm tên sản phẩm...">
            </div>
            <!-- Row 2: Category Filter Menu (Hamburger for Products, Buttons for Services) -->
            <div class="flex items-center space-x-2">
                <!-- Hamburger Dropdown for Products -->
                <div class="relative">
                    <button @click.stop="openCat = !openCat" 
                            :class="{'bg-primary text-white border-primary': activeCategory !== 'Danh mục Dịch vụ' && activeCategory !== 'Dịch vụ' && activeCategory !== 'all', 'bg-gray-50 border-gray-200 text-gray-700 hover:bg-gray-100': activeCategory === 'Danh mục Dịch vụ' || activeCategory === 'Dịch vụ' || activeCategory === 'all'}"
                            class="px-4 py-2 border rounded-xl text-xs font-bold whitespace-nowrap transition cursor-pointer flex items-center gap-2 shadow-sm focus:outline-none">
                        <i class="fa-solid fa-bars text-gray-500" :class="{'text-white': activeCategory !== 'Danh mục Dịch vụ' && activeCategory !== 'Dịch vụ' && activeCategory !== 'all'}"></i>
                        <span>Sản phẩm: <strong class="ml-1 font-extrabold" :class="{'text-white': activeCategory !== 'Danh mục Dịch vụ' && activeCategory !== 'Dịch vụ' && activeCategory !== 'all', 'text-primary': activeCategory === 'all' || activeCategory === 'Danh mục Dịch vụ' || activeCategory === 'Dịch vụ'}" x-text="activeCategory === 'Danh mục Dịch vụ' || activeCategory === 'Dịch vụ' ? 'Tất cả sản phẩm' : getCategoryLabel(activeCategory)"></strong></span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition duration-200" :class="{'rotate-180 text-white': openCat, 'text-white': activeCategory !== 'Danh mục Dịch vụ' && activeCategory !== 'Dịch vụ' && activeCategory !== 'all'}"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="openCat" 
                         @click.away="openCat = false" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                         class="absolute z-30 left-0 mt-1.5 bg-white border border-gray-200 shadow-xl rounded-xl py-2 w-60 border-t-2 border-t-primary"
                         x-cloak>
                         
                         <!-- Option: Tất cả sản phẩm -->
                         <button @click="filterCategory('all'); openCat = false" 
                                 :class="{'bg-primary/10 text-primary font-bold': activeCategory === 'all', 'text-gray-700 hover:bg-gray-50': activeCategory !== 'all'}" 
                                 class="w-full text-left px-4 py-2 text-xs transition cursor-pointer flex items-center justify-between font-bold">
                             <span class="flex items-center gap-2"><i class="fa-solid fa-border-all text-gray-400"></i> Tất cả sản phẩm</span>
                             <i x-show="activeCategory === 'all'" class="fa-solid fa-check text-[10px] text-primary"></i>
                         </button>
                         
                         <div class="border-t border-gray-100 my-1.5"></div>
                         
                         <!-- Dynamic Product Categories from DB -->
                         <div class="px-3 py-1 text-[9px] font-black uppercase text-gray-400 tracking-wider">Sản phẩm cửa hàng</div>
                         <template x-for="cat in productCategories" :key="cat.id">
                             <button @click="filterCategory(cat.name); openCat = false" 
                                     :class="{'bg-primary/10 text-primary font-bold': activeCategory === cat.name, 'text-gray-700 hover:bg-gray-50': activeCategory !== cat.name}" 
                                     class="w-full text-left px-4 py-2 text-xs transition cursor-pointer flex items-center justify-between">
                                 <span class="flex items-center gap-2"><i class="fa-solid fa-tags text-gray-300"></i> <span x-text="cat.name"></span></span>
                                 <i x-show="activeCategory === cat.name" class="fa-solid fa-check text-[10px] text-primary"></i>
                             </button>
                         </template>
                    </div>
                </div>

                <!-- Book Service Button -->
                <button @click="filterCategory('Danh mục Dịch vụ')" 
                        :class="{'bg-primary text-white shadow-md shadow-primary/20': activeCategory === 'Danh mục Dịch vụ', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeCategory !== 'Danh mục Dịch vụ'}" 
                        class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition border border-transparent cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-notes-medical" :class="{'text-white': activeCategory === 'Danh mục Dịch vụ', 'text-primary': activeCategory !== 'Danh mục Dịch vụ'}"></i>
                    Đặt lịch Dịch vụ
                </button>

                <!-- Waiting Appointments Button -->
                <button @click="filterCategory('Dịch vụ')" 
                        :class="{'bg-primary text-white shadow-md shadow-primary/20': activeCategory === 'Dịch vụ', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeCategory !== 'Dịch vụ'}" 
                        class="relative inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold hover:bg-gray-200 transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-bell text-yellow-500 animate-swing" :class="{'text-white': activeCategory === 'Dịch vụ'}"></i>
                    Dịch vụ chờ thanh toán
                    <template x-if="waitingCount > 0">
                        <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[8px] font-black text-white ring-1 ring-white animate-pulse" x-text="waitingCount"></span>
                    </template>
                </button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1 overflow-y-auto p-4 bg-gray-100">
            <div class="grid grid-cols-[repeat(auto-fill,minmax(180px,1fr))] gap-4">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div @click="addToCart(product)" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden cursor-pointer hover:shadow-md transition hover:border-primary flex flex-col h-full">
                        <div class="h-32 bg-gray-50 w-full relative flex items-center justify-center border-b border-gray-100/50">
                            <template x-if="product.is_appointment">
                                <i class="fa-solid fa-notes-medical text-4xl text-gray-400"></i>
                            </template>
                            <template x-if="!product.is_appointment">
                                <img :src="product.image ? '<?php echo URLROOT; ?>/public/images/' + product.image : 'https://placehold.co/200x200?text=No+Img'" class="w-full h-full object-contain p-1.5 transition duration-300 hover:scale-105">
                            </template>
                            <div x-show="!product.is_appointment" class="absolute bottom-0 right-0 bg-white/95 border border-gray-100/50 px-2 py-0.5 m-1 rounded-md text-[10px] font-extrabold shadow-sm text-gray-600">
                                Tồn: <span x-text="product.stock" class="text-gray-900"></span>
                            </div>
                            <div x-show="product.is_appointment" class="absolute bottom-0 right-0 bg-red-500 text-white px-2 py-0.5 m-1 rounded-md text-[10px] font-extrabold shadow-sm">
                                Dịch vụ
                            </div>
                        </div>
                        <div class="p-3 flex-1 flex flex-col justify-between">
                            <h3 class="text-xs text-gray-800 font-bold line-clamp-2 mb-1.5 leading-snug" x-text="product.name"></h3>
                            <div>
                                <template x-if="product.is_service_catalog && ((product.category || '').toLowerCase().includes('khám') || (product.category || '').toLowerCase().includes('chữa'))">
                                    <p class="text-gray-500 italic text-[11px] font-bold mt-1">Bác sĩ báo giá sau</p>
                                </template>
                                <template x-if="!(product.is_service_catalog && ((product.category || '').toLowerCase().includes('khám') || (product.category || '').toLowerCase().includes('chữa')))">
                                    <p class="text-primary font-black text-xs" x-text="formatMoney(product.price)"></p>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="filteredProducts.length === 0" class="col-span-full py-12 text-center text-gray-500">
                    Không tìm thấy sản phẩm phù hợp
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Cart / Checkout -->
    <div class="w-full lg:w-96 bg-white flex flex-col h-full overflow-hidden shadow-lg z-20">
        <!-- Customer Info / Membership (Moved to top) -->
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-user text-primary text-xs"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <div class="text-sm font-black text-gray-800" x-text="customerName || 'Khách lẻ'"></div>
                            <template x-if="customerLevel">
                                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter" 
                                      :class="getBadgeClass(customerLevel)" x-text="customerLevel"></span>
                            </template>
                        </div>
                        <div class="text-[9px] font-bold text-gray-400 uppercase leading-none mt-0.5" x-text="customerPhone || 'Khách vãng lai'"></div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <template x-if="customerName">
                        <button @click="resetCustomer()" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition flex items-center justify-center">
                            <i class="fa-solid fa-user-minus text-xs"></i>
                        </button>
                    </template>
                    <button @click="showCustomerSearch = !showCustomerSearch" class="w-8 h-8 rounded-lg bg-primary text-white hover:bg-primary/90 transition flex items-center justify-center shadow-sm shadow-primary/20">
                        <i class="fa-solid" :class="showCustomerSearch ? 'fa-times' : 'fa-user-plus' + (customerName ? '' : ' text-xs')"></i>
                    </button>
                </div>
            </div>

            <!-- Membership Search Panel -->
            <div x-show="showCustomerSearch" x-transition class="mt-4 relative">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" x-model="customerSearch" 
                           placeholder="Tìm tên hoặc SĐT hội viên..." 
                           class="w-full pl-8 pr-3 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition shadow-inner">
                </div>
                
                <!-- Search Results Suggestions -->
                <div x-show="customerSearch.length > 0" 
                     class="absolute z-50 left-0 right-0 mt-2 bg-white border border-gray-100 shadow-2xl rounded-2xl overflow-hidden max-h-60 overflow-y-auto border-t-4 border-t-primary">
                    <template x-for="c in filteredCustomers" :key="c.id">
                        <div @click="selectCustomer(c)" class="px-4 py-3 hover:bg-primary/5 cursor-pointer border-b border-gray-50 last:border-0 transition-colors flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <div class="text-sm font-bold text-gray-800" x-text="c.fullname"></div>
                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase" 
                                          :class="getBadgeClass(c.membership_level || 'Đồng')" x-text="c.membership_level || 'Đồng'"></span>
                                </div>
                                <div class="text-[10px] text-gray-500 flex items-center gap-1">
                                    <i class="fa-solid fa-phone"></i>
                                    <span x-text="c.phone || 'Chưa cập nhật SĐT'"></span>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
                        </div>
                    </template>
                    <template x-if="filteredCustomers.length === 0">
                        <div class="mt-2 text-xs text-center py-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-500 mb-2">Không tìm thấy hội viên nào.</p>
                            <button @click="setAsGuest(customerSearch)" class="px-3 py-1.5 bg-indigo-50 text-primary font-bold rounded-lg hover:bg-indigo-100 transition inline-flex items-center">
                                <i class="fa-solid fa-user-check mr-2"></i> Đặt làm Khách lẻ (Không lưu)
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- AI Pet Health Advisory Panel (Interactive Pet Profile) -->
            <template x-if="customerAiHistory.length > 0">
                <div class="mt-3 bg-gradient-to-br from-indigo-50 via-purple-50/50 to-white border border-indigo-100 rounded-xl p-3 shadow-sm relative overflow-hidden transition-all duration-300">
                    <div class="absolute -right-4 -bottom-4 text-indigo-500/5 text-5xl font-black rotate-12 pointer-events-none"><i class="fa-solid fa-brain"></i></div>
                    
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-1.5 text-[10px] font-black text-indigo-700 uppercase tracking-wider">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                            </span>
                            <i class="fa-solid fa-wand-magic-sparkles"></i> AI Doctor Khuyên Dùng
                        </div>
                        <button @click="showAiHistoryPanel = !showAiHistoryPanel" class="text-[9px] font-extrabold text-indigo-600 hover:text-indigo-800 flex items-center gap-0.5">
                            <span x-text="showAiHistoryPanel ? 'Thu gọn' : 'Xem chi tiết'"></span>
                            <i class="fa-solid" :class="showAiHistoryPanel ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                    </div>
                    
                    <div x-show="showAiHistoryPanel" x-transition class="text-xs space-y-2 mt-1">
                        <div class="bg-white/80 p-2 rounded-lg border border-indigo-50/50">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wide leading-none mb-1">Triệu chứng thú cưng (AI ghi nhận):</p>
                            <p class="text-[11px] text-gray-700 font-medium italic" x-text="customerAiHistory[0].symptoms"></p>
                        </div>
                        
                        <!-- Dynamic Recommended Products/Services based on AI analysis -->
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <template x-if="customerAiHistory[0].symptoms.toLowerCase().includes('nôn') || customerAiHistory[0].symptoms.toLowerCase().includes('ăn') || customerAiHistory[0].symptoms.toLowerCase().includes('tiêu')">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-amber-50 border border-amber-100 rounded-lg text-[9px] font-black text-amber-700 uppercase">
                                    💊 Men tiêu hóa / Pate nhạy cảm
                                </span>
                            </template>
                            <template x-if="customerAiHistory[0].symptoms.toLowerCase().includes('lông') || customerAiHistory[0].symptoms.toLowerCase().includes('ngứa') || customerAiHistory[0].symptoms.toLowerCase().includes('da')">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-pink-50 border border-pink-100 rounded-lg text-[9px] font-black text-pink-700 uppercase">
                                    🛁 Spa Tắm dưỡng da / Dưỡng lông
                                </span>
                            </template>
                            <template x-if="customerAiHistory[0].symptoms.toLowerCase().includes('mũi') || customerAiHistory[0].symptoms.toLowerCase().includes('thở') || customerAiHistory[0].symptoms.toLowerCase().includes('hắt')">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 border border-blue-100 rounded-lg text-[9px] font-black text-blue-700 uppercase">
                                    🩺 Đặt lịch Khám hô hấp
                                </span>
                            </template>
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-50 border border-indigo-100 rounded-lg text-[9px] font-black text-indigo-700 uppercase">
                                🏥 Tư vấn khám tổng quát
                            </span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-2">
            <template x-if="cart.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-gray-400 p-8 text-center">
                    <i class="fa-solid fa-basket-shopping text-6xl mb-4 text-gray-200"></i>
                    <p>Chưa có sản phẩm nào trong giỏ hàng</p>
                </div>
            </template>
            <ul class="divide-y divide-gray-100">
                <template x-for="(item, index) in cart" :key="item.id">
                    <li class="py-3 px-2 hover:bg-gray-50 rounded transition">
                        <div class="flex justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-800 line-clamp-1" x-text="item.name"></h4>
                                <div class="text-xs text-gray-500 mt-1" x-text="formatMoney(item.price)"></div>
                            </div>
                            <div class="ml-2 flex items-center flex-col items-end">
                                <div class="text-sm font-bold text-gray-800" x-text="formatMoney(item.price * item.quantity)"></div>
                                <div class="flex items-center mt-2 border rounded border-gray-300 bg-white" x-show="!item.is_appointment || item.category.toLowerCase().includes('trông giữ')">
                                    <button @click="decreaseQuantity(index)" class="px-2 py-1 text-gray-500 hover:bg-gray-100 rounded-l"><i class="fa-solid fa-minus text-xs"></i></button>
                                    <span class="px-2 py-1 text-sm text-center min-w-[32px] font-medium" 
                                          x-text="item.quantity + (item.category.toLowerCase().includes('trông giữ') ? (item.name.toLowerCase().includes('ngắn hạn') ? ' tiếng' : ' ngày') : '')"></span>
                                    <button @click="increaseQuantity(index)" class="px-2 py-1 text-gray-500 hover:bg-gray-100 rounded-r"><i class="fa-solid fa-plus text-xs"></i></button>
                                </div>
                                <div class="mt-2" x-show="item.is_appointment && !item.category.toLowerCase().includes('trông giữ')">
                                    <button @click="decreaseQuantity(index)" class="text-xs text-red-500 hover:text-red-700 font-medium px-2 py-1 bg-red-50 rounded transition"><i class="fa-solid fa-trash mr-1"></i> Bỏ dịch vụ</button>
                                </div>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>



        <!-- Checkout Summary -->
        <div class="border-t border-gray-200 p-4 bg-gray-50">
            <div class="flex justify-between mb-2 text-sm text-gray-600">
                <span>Tổng số lượng:</span>
                <span class="font-medium" x-text="totalItems"></span>
            </div>
            <div class="flex justify-between mb-2 text-sm text-gray-600">
                <span>Tạm tính:</span>
                <span class="font-medium" x-text="formatMoney(totalPrice)"></span>
            </div>
            <div class="flex justify-between mb-2 text-sm text-secondary font-bold" x-show="discountAmount > 0">
                <span class="flex items-center"><i class="fa-solid fa-tag mr-1"></i>Ưu đãi hội viên (<span x-text="customerLevel"></span>):</span>
                <span x-text="'- ' + formatMoney(discountAmount)"></span>
            </div>
            <div class="flex justify-between items-end mb-4 border-t border-gray-200 pt-2">
                <span class="text-base font-medium text-gray-800">Khách phải trả:</span>
                <span class="text-2xl font-bold text-primary" x-text="formatMoney(finalPrice)"></span>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <button @click="clearCart()" class="w-full py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                    <i class="fa-solid fa-trash mr-1 text-red-500"></i> Hủy
                </button>
                <button @click="checkout()" :disabled="cart.length === 0" :class="{'opacity-50 cursor-not-allowed': cart.length === 0}" class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700 transition flex items-center justify-center">
                    <i class="fa-solid fa-money-bill-wave mr-2"></i> Thanh toán
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal Đặt lịch Dịch vụ -->
    <div x-show="showBookModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" @click.away="showBookModal = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all" x-transition>
            <div class="bg-gradient-to-r from-primary to-indigo-600 p-4 flex justify-between items-center text-white">
                <h3 class="font-black text-lg">Đặt Lịch & Giao Việc</h3>
                <button @click="showBookModal = false" class="text-white/80 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <div class="p-6">
                <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <p class="text-sm font-bold text-gray-800" x-text="bookForm.service?.name"></p>
                    <template x-if="bookForm.service && ((bookForm.service.category || '').toLowerCase().includes('khám') || (bookForm.service.category || '').toLowerCase().includes('chữa'))">
                        <p class="text-xs text-gray-500 italic mt-1 font-medium">Bác sĩ báo giá sau</p>
                    </template>
                    <template x-if="bookForm.service && !((bookForm.service.category || '').toLowerCase().includes('khám') || (bookForm.service.category || '').toLowerCase().includes('chữa'))">
                        <p class="text-xs text-primary font-bold mt-1" x-text="formatMoney(bookForm.service.price)"></p>
                    </template>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-2">Loại khách hàng</label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" x-model="bookForm.is_guest" value="false" class="mr-2 text-primary focus:ring-primary"> Khách hàng / Hội viên
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" x-model="bookForm.is_guest" value="true" class="mr-2 text-primary focus:ring-primary"> Khách lẻ (không tích điểm)
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4 relative">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tên khách hàng <span class="text-red-500">*</span></label>
                        <input type="text" 
                               x-model="bookForm.customer_name" 
                               @input="bookForm.hide_suggestions = false; bookForm.customer_id = null"
                               class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary/20" 
                               placeholder="Tên khách" required>
                    </div>
                    <div x-show="bookForm.is_guest !== 'true'">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                        <input type="text" 
                               x-model="bookForm.customer_phone" 
                               @input="bookForm.hide_suggestions = false; bookForm.customer_id = null"
                               class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary/20" 
                               placeholder="SĐT" :required="bookForm.is_guest !== 'true'">
                    </div>

                    <!-- Suggestions Dropdown -->
                    <div x-show="bookForm.is_guest !== 'true' && !bookForm.hide_suggestions && bookFormCustomers.length > 0"
                         class="absolute z-[110] left-0 right-0 top-full mt-1 bg-white border border-gray-200 shadow-xl rounded-xl overflow-hidden max-h-48 overflow-y-auto border-t-2 border-t-primary"
                         x-cloak>
                        <template x-for="c in bookFormCustomers" :key="c.id">
                            <div @click="selectBookCustomer(c)" 
                                 class="px-4 py-2 hover:bg-primary/5 cursor-pointer border-b border-gray-50 last:border-0 transition-colors flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <div class="text-xs font-bold text-gray-800" x-text="c.fullname"></div>
                                        <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase" 
                                              :class="getBadgeClass(c.membership_level || 'Đồng')" x-text="c.membership_level || 'Đồng'"></span>
                                    </div>
                                    <div class="text-[10px] text-gray-500 flex items-center gap-1">
                                        <i class="fa-solid fa-phone"></i>
                                        <span x-text="c.phone || 'Chưa cập nhật SĐT'"></span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Ngày <span class="text-red-500">*</span></label>
                        <input type="date" x-model="bookForm.date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Giờ <span class="text-red-500">*</span></label>
                        <input type="time" x-model="bookForm.time" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 mb-2">Phân công nhân sự <span class="text-red-500">*</span></label>
                    <div class="max-h-48 overflow-y-auto space-y-2 pr-2 border border-gray-200 rounded-lg p-2 bg-gray-50">
                        <template x-for="assignee in assignees" :key="assignee.id">
                            <label class="flex items-center p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-primary hover:shadow-sm transition"
                                   :class="{'border-primary bg-primary/5 ring-1 ring-primary': bookForm.doctor_id === assignee.id}">
                                <input type="radio" x-model="bookForm.doctor_id" :value="assignee.id" class="hidden">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-gray-200 to-gray-300 flex items-center justify-center mr-3 text-gray-600 font-bold shrink-0">
                                    <span x-text="assignee.fullname.substring(0,1).toUpperCase()"></span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-800" x-text="assignee.fullname"></p>
                                    <p class="text-[10px] uppercase font-black" :class="assignee.role === 'doctor' ? 'text-blue-500' : 'text-green-500'" x-text="assignee.role === 'doctor' ? 'Bác sĩ' : 'Nhân viên'"></p>
                                </div>
                                <!-- Availability Badge -->
                                <div class="shrink-0 text-right">
                                    <template x-if="isAssigneeBusy(assignee.id)">
                                        <span class="text-[10px] font-bold px-2 py-1 rounded bg-red-100 text-red-500">Bận</span>
                                    </template>
                                    <template x-if="!isAssigneeBusy(assignee.id)">
                                        <span class="text-[10px] font-bold px-2 py-1 rounded bg-green-100 text-green-600">Sẵn sàng</span>
                                    </template>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="showBookModal = false" class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Hủy</button>
                    <button @click="submitBooking()" :disabled="!bookForm.doctor_id || !bookForm.customer_name || !bookForm.customer_phone" :class="{'opacity-50 cursor-not-allowed': !bookForm.doctor_id || !bookForm.customer_name || !bookForm.customer_phone}" class="px-5 py-2.5 text-sm font-bold text-white bg-primary rounded-xl hover:bg-indigo-700 shadow-md shadow-primary/30 transition flex items-center gap-2">
                        <i class="fa-solid fa-check"></i> Xác nhận Đặt & Giao
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- 1. MODAL CHỌN PHƯƠNG THỨC THANH TOÁN (Đề xuất 1 - VietQR & Invoice) -->
<div x-show="showPaymentModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" @click.away="showPaymentModal = false">
    <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl border border-gray-100 flex flex-col max-h-[90vh] transform scale-100 transition duration-300">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-dark to-darker text-white p-5 flex justify-between items-center relative">
            <div class="absolute top-0 right-0 w-[150px] h-[150px] bg-primary/10 rounded-full blur-2xl pointer-events-none"></div>
            <h3 class="text-base font-black flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-primary animate-pulse"></i> PHƯƠNG THỨC THANH TOÁN
            </h3>
            <button @click="showPaymentModal = false" class="text-white/80 hover:text-white transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto space-y-6">
            <!-- Grand Total Display -->
            <div class="bg-gray-50 border border-gray-150 rounded-2xl p-4 text-center">
                <p class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Tổng tiền thanh toán</p>
                <p class="text-2xl font-black text-primary mt-1" x-text="formatMoney(finalPrice)"></p>
            </div>

            <!-- Payment Selection Cards -->
            <div class="grid grid-cols-2 gap-4">
                <!-- Cash Option -->
                <div @click="paymentMethod = 'cash'" 
                     class="cursor-pointer border-2 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 transition hover:shadow-md relative overflow-hidden"
                     :class="paymentMethod === 'cash' ? 'border-primary bg-indigo-50/30' : 'border-gray-200 bg-white'">
                    <div x-show="paymentMethod === 'cash'" class="absolute top-2 right-2 text-primary"><i class="fa-solid fa-circle-check"></i></div>
                    <i class="fa-solid fa-money-bill-wave text-3xl" :class="paymentMethod === 'cash' ? 'text-primary' : 'text-gray-400'"></i>
                    <span class="text-xs font-black uppercase tracking-wider text-gray-800">Tiền mặt</span>
                </div>

                <!-- VietQR Option -->
                <div @click="paymentMethod = 'vietqr'" 
                     class="cursor-pointer border-2 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 transition hover:shadow-md relative overflow-hidden"
                     :class="paymentMethod === 'vietqr' ? 'border-primary bg-indigo-50/30' : 'border-gray-200 bg-white'">
                    <div x-show="paymentMethod === 'vietqr'" class="absolute top-2 right-2 text-primary"><i class="fa-solid fa-circle-check"></i></div>
                    <i class="fa-solid fa-qrcode text-3xl" :class="paymentMethod === 'vietqr' ? 'text-primary' : 'text-gray-400'"></i>
                    <span class="text-xs font-black uppercase tracking-wider text-gray-800">Quét VietQR</span>
                </div>
            </div>

            <!-- Cash details -->
            <div x-show="paymentMethod === 'cash'" x-transition class="space-y-4 pt-2">
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-500 tracking-wider mb-1.5">Số tiền khách đưa</label>
                    <input type="number" x-model.number="cashReceived" 
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition font-bold"
                           placeholder="VD: 500000">
                </div>
                <div class="bg-gray-50 border border-gray-150 rounded-xl p-3 flex justify-between items-center text-xs">
                    <span class="text-gray-500 font-bold">Tiền thối lại cho khách:</span>
                    <span class="text-sm font-black text-gray-800" x-text="formatMoney(Math.max(0, (cashReceived || 0) - finalPrice))"></span>
                </div>
            </div>

            <!-- VietQR details -->
            <div x-show="paymentMethod === 'vietqr'" x-transition class="flex flex-col items-center justify-center gap-4 py-2">
                <div class="bg-white border-2 border-indigo-100 p-3 rounded-2xl shadow-inner relative group">
                    <img :src="`https://img.vietqr.io/image/VCB-1047429167-compact2.png?amount=${finalPrice}&addInfo=${encodeURIComponent((customerName || 'Khach le') + ' - THANH TOAN PETSHOP TOI NGUYEN MINH TUAN')}&accountName=NGUYEN%20MINH%20TUAN`" 
                         class="w-48 h-48 object-contain rounded-xl" alt="Mã VietQR Thanh Toán">
                </div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider text-center leading-relaxed">
                    Chuyển khoản chính xác số tiền hiển thị ở trên.<br>
                    Nội dung: <span class="text-primary font-black" x-text="`${customerName || 'Khach le'} - THANH TOAN PETSHOP TOI NGUYEN MINH TUAN`"></span>
                </p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 bg-gray-50 border-t border-gray-150 flex gap-3">
            <button @click="showPaymentModal = false" class="flex-1 py-2.5 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                Hủy bỏ
            </button>
            <button @click="confirmCheckout()" class="flex-1 py-2.5 text-xs font-bold text-white bg-primary rounded-xl hover:bg-indigo-700 shadow-md shadow-primary/30 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-circle-check"></i> Xác nhận thanh toán
            </button>
        </div>
    </div>
</div>

<!-- 2. MODAL XUẤT HÓA ĐƠN NHIỆT CHUYÊN NGHIỆP (Đề xuất 1) -->
<div x-show="showReceiptModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center bg-black/70 backdrop-blur-sm">
    <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl border border-gray-100 flex flex-col max-h-[95vh] transform scale-100 transition duration-300">
        <!-- Header buttons -->
        <div class="bg-dark text-white p-4 flex justify-between items-center border-b border-white/5">
            <h3 class="text-xs font-black uppercase tracking-wider text-primary"><i class="fa-solid fa-file-invoice"></i> Hóa đơn</h3>
            <button @click="closeReceiptAndReload()" class="text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <!-- Receipt content (mimicking 80mm printer paper size) -->
        <div class="flex-1 overflow-y-auto p-4 bg-gray-100">
            <div id="thermal-receipt-container" class="bg-white p-4 shadow-sm mx-auto text-black font-mono text-[10px] leading-relaxed max-w-[80mm] border border-gray-200">
                <!-- Brand info -->
                <div class="text-center space-y-1 mb-4 border-b border-dashed border-gray-300 pb-3">
                    <h2 class="text-sm font-black tracking-widest uppercase flex items-center justify-center gap-1.5"><i class="fa-solid fa-paw text-primary"></i> PETSHOP</h2>
                    <p class="text-[8px] text-gray-500">123 Đường Thú Cưng, TP. Hồ Chí Minh</p>
                    <p class="text-[8px] text-gray-500">Hotline: 0947647052</p>
                </div>

                <!-- Invoice metadata -->
                <div class="space-y-1 border-b border-dashed border-gray-300 pb-3 mb-3 text-[8px] text-gray-600">
                    <div class="flex justify-between">
                        <span>HĐ số:</span>
                        <span class="font-bold text-black" x-text="completedOrder ? (completedOrder.order_id ? '#' + completedOrder.order_id.toString().padStart(5, '0') : 'DV') : ''"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ngày:</span>
                        <span class="text-black" x-text="new Date().toLocaleString('vi-VN')"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Thu ngân:</span>
                        <span class="text-black" x-text="completedOrder ? completedOrder.cashier_name : 'Thu Ngân'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Khách hàng:</span>
                        <span class="text-black font-bold" x-text="completedOrder ? completedOrder.customer_name : 'Khách lẻ'"></span>
                    </div>
                    <template x-if="completedOrder && completedOrder.customer_phone">
                        <div class="flex justify-between">
                            <span>SĐT:</span>
                            <span class="text-black" x-text="completedOrder.customer_phone"></span>
                        </div>
                    </template>
                </div>

                <!-- Cart items list -->
                <div class="space-y-2 border-b border-dashed border-gray-300 pb-3 mb-3">
                    <div class="flex justify-between text-[8px] font-bold text-gray-500 border-b border-gray-100 pb-1 uppercase">
                        <span>Tên mục</span>
                        <span>SL x ĐG</span>
                        <span>T.Tiền</span>
                    </div>
                    <template x-if="completedOrder && completedOrder.cart">
                        <template x-for="item in completedOrder.cart" :key="item.id">
                            <div class="border-b border-gray-50 pb-1.5 last:border-b-0">
                                <div class="flex justify-between items-start text-[9px] text-gray-800 font-medium">
                                    <div class="max-w-[130px] leading-tight">
                                        <span x-text="item.name"></span>
                                    </div>
                                    <div class="whitespace-nowrap" x-text="`${item.quantity} x ${Math.round(item.price).toLocaleString('vi-VN')}đ`"></div>
                                    <div class="font-bold text-black whitespace-nowrap text-right" x-text="`${(item.quantity * item.price).toLocaleString('vi-VN')}đ`"></div>
                                </div>
                                <template x-if="item.instruction">
                                    <div class="text-[7px] text-gray-500 italic mt-0.5 leading-tight pl-1 border-l border-primary/40">
                                        HD: <span x-text="item.instruction"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </template>
                </div>

                <!-- Totals and payment details -->
                <div class="space-y-1 mb-4 text-[9px]">
                    <div class="flex justify-between font-bold">
                        <span>TỔNG CỘNG:</span>
                        <span class="text-black text-xs font-black" x-text="completedOrder ? formatMoney(completedOrder.total_amount) : ''"></span>
                    </div>
                    <div class="flex justify-between text-[8px] text-gray-600">
                        <span>HT Thanh toán:</span>
                        <span class="text-black font-bold uppercase" x-text="completedOrder && completedOrder.payment_method === 'cash' ? 'Tiền mặt' : 'Chuyển khoản VietQR'"></span>
                    </div>
                </div>

                <!-- Bottom Thank You greeting -->
                <div class="text-center border-t border-dashed border-gray-300 pt-3 mt-4 space-y-1">
                    <p class="text-[8px] font-bold">Xin cảm ơn quý khách và hẹn gặp lại!</p>
                </div>
            </div>
        </div>

        <!-- Footer print and finish buttons -->
        <div class="p-4 bg-gray-50 border-t border-gray-150 flex gap-2.5">
            <button @click="printReceipt()" class="flex-1 py-2.5 bg-primary hover:bg-primary/95 text-white text-xs font-bold rounded-xl transition shadow-md shadow-primary/20 flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-print"></i> In Hóa Đơn (K80)
            </button>
            <button @click="closeReceiptAndReload()" class="flex-1 py-2.5 bg-dark hover:bg-darker text-white text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-circle-check"></i> Hoàn thành
            </button>
        </div>
    </div>
</div>

</div>

<script>
// Chuyển dữ liệu PHP sang JS
const rawProducts = <?php echo json_encode($data['products']); ?>;
const rawAppointments = <?php echo json_encode($data['appointments'] ?? []); ?>;
const rawCustomers = <?php echo json_encode($data['customers'] ?? []); ?>;
const rawServices = <?php echo json_encode($data['services'] ?? []); ?>;
const rawAssignees = <?php echo json_encode($data['assignees'] ?? []); ?>;
const staffSchedules = <?php echo json_encode($data['staff_schedules'] ?? []); ?>;
const rawProductCategories = <?php echo json_encode($data['product_categories'] ?? []); ?>;

document.addEventListener('alpine:init', () => {
    Alpine.data('posSystem', () => ({
        productCategories: [],
        
        getCategoryLabel(category) {
            if (category === 'all') return 'Tất cả sản phẩm';
            if (category === 'Dịch vụ') return 'Dịch vụ chờ thanh toán';
            if (category === 'Danh mục Dịch vụ') return 'Đặt lịch Dịch vụ';
            return category;
        },

        services: rawServices.map(s => ({
            id: 'svc_' + s.id,
            real_id: s.id,
            name: s.name,
            price: parseFloat(s.price),
            image: s.image,
            category: s.category_name,
            stock: 999,
            is_appointment: false,
            is_service_catalog: true,
            duration_minutes: parseInt(s.duration_minutes)
        })),
        assignees: rawAssignees,
        staffSchedules: staffSchedules,
        
        showBookModal: false,
        bookForm: {
            is_guest: 'false',
            service: null,
            customer_id: null,
            customer_name: '',
            customer_phone: '',
            date: '',
            time: '',
            doctor_id: '',
            duration_value: 1,
            duration_unit: 'none',
            hide_suggestions: true
        },

        products: rawProducts.map(p => ({
            id: p.id,
            name: p.name,
            price: parseFloat(p.price),
            image: p.image,
            category: p.category_name,
            stock: parseInt(p.stock_quantity),
            is_appointment: false,
            is_service_catalog: false
        })),
        appointments: rawAppointments.map(a => {
            const isShortBoarding = a.category_name.toLowerCase().includes('trông giữ') && a.service_name.toLowerCase().includes('ngắn hạn');
            const isLongBoarding = a.category_name.toLowerCase().includes('trông giữ') && a.service_name.toLowerCase().includes('dài hạn');
            let basePrice = parseFloat(a.final_price) || parseFloat(a.service_price) || 0;
            
            // Tự động áp dụng đơn giá theo yêu cầu nếu là trông giữ
            if (isShortBoarding) basePrice = 20000;
            if (isLongBoarding) basePrice = 50000;

            let finalDuration = parseInt(a.duration_value) || 1;
            // Quy đổi tháng sang ngày (1 tháng = 29 ngày) cho dịch vụ dài hạn
            if (a.duration_unit === 'month' && isLongBoarding) {
                finalDuration = finalDuration * 29;
            }

            return {
                id: 'app_' + a.id,
                real_id: a.id,
                name: `Dịch vụ: ${a.service_name} (KH: ${a.customer_name})`,
                price: basePrice,
                image: null,
                category: a.category_name,
                stock: 999,
                duration_value: finalDuration,
                duration_unit: a.duration_unit,
                customer_id: a.customer_id,
                customer_name: a.customer_name,
                customer_phone: a.customer_phone,
                is_appointment: true,
                prescriptions: a.prescriptions || []
            };
        }),
        benefits: <?php echo json_encode($data['benefits']); ?>,
        customers: rawCustomers,
        customerSearch: '',
        showCustomerSearch: false,
        searchQuery: '',
        activeCategory: 'all',
        openCat: false,
        cart: [],
        customerName: '',
        customerPhone: '',
        customerLevel: '',
        customerAiHistory: [],
        showAiHistoryPanel: false,
        showPaymentModal: false,
        showReceiptModal: false,
        paymentMethod: 'cash',
        completedOrder: null,
        cashReceived: 0,
        
        customerId: null,

        init() {
            // 0. Nạp danh mục từ DB và bổ sung từ sản phẩm thực tế
            this.productCategories = rawProductCategories;
            if (this.products && this.products.length > 0) {
                const existingNames = this.productCategories.map(c => c.name);
                this.products.forEach(p => {
                    if (p.category && !existingNames.includes(p.category)) {
                        this.productCategories.push({ id: p.category, name: p.category });
                        existingNames.push(p.category);
                    }
                });
            }
            this.productCategories.sort((a, b) => a.name.localeCompare(b.name));

            // 1. Tự động thêm dịch vụ vào giỏ hàng nếu có appointment_id trong URL
            const urlParams = new URLSearchParams(window.location.search);
            const apptId = urlParams.get('appointment_id');
            if (apptId) {
                setTimeout(() => {
                    const appt = this.appointments.find(a => String(a.real_id) === String(apptId));
                    if (appt) {
                        this.addToCart(appt);
                        this.customerName = appt.customer_name;
                        this.customerPhone = appt.customer_phone;
                        const cust = this.customers.find(c => c.fullname === appt.customer_name || c.phone === appt.customer_phone);
                        if (cust) this.customerLevel = cust.membership_level;
                        this.fetchCustomerAiHistory(appt.customer_phone, appt.customer_name);
                    }
                }, 100);
            }

            // 2. Thiết lập kết nối Server-Sent Events (SSE) thời gian thực cho lịch hẹn và lịch nhân sự
            const sseSource = new EventSource('<?php echo URLROOT; ?>/admin/pos_sse');
            sseSource.onmessage = (event) => {
                try {
                    const data = JSON.parse(event.data);
                    
                    // Cập nhật appointments
                    if (data.appointments) {
                        const newAppointments = data.appointments.map(a => {
                            const isShortBoarding = a.category_name.toLowerCase().includes('trông giữ') && a.service_name.toLowerCase().includes('ngắn hạn');
                            const isLongBoarding = a.category_name.toLowerCase().includes('trông giữ') && a.service_name.toLowerCase().includes('dài hạn');
                            let basePrice = parseFloat(a.final_price) || parseFloat(a.service_price) || 0;
                            if (isShortBoarding) basePrice = 20000;
                            if (isLongBoarding) basePrice = 50000;
                            let finalDuration = parseInt(a.duration_value) || 1;
                            if (a.duration_unit === 'month' && isLongBoarding) finalDuration = finalDuration * 29;

                            return {
                                id: 'app_' + a.id,
                                real_id: a.id,
                                name: `Dịch vụ: ${a.service_name} (KH: ${a.customer_name})`,
                                price: basePrice,
                                image: null,
                                category: a.category_name,
                                stock: 999,
                                duration_value: finalDuration,
                                duration_unit: a.duration_unit,
                                customer_id: a.customer_id,
                                customer_name: a.customer_name,
                                customer_phone: a.customer_phone,
                                is_appointment: true,
                                prescriptions: a.prescriptions || []
                            };
                        });
                        
                        this.appointments = newAppointments;
                    }
                    
                    // Cập nhật staff schedules
                    if (data.staff_schedules) {
                        this.staffSchedules = data.staff_schedules;
                    }
                } catch (err) {
                    console.error('SSE Error parsing data:', err);
                }
            };
            
            // Xử lý đóng kết nối khi unload trang để tránh rò rỉ tài nguyên trên server
            window.addEventListener('beforeunload', () => {
                sseSource.close();
            });
        },

        get filteredCustomers() {
            if (!this.customerSearch) return [];
            const q = this.customerSearch.toLowerCase();
            return this.customers.filter(c => 
                c.fullname.toLowerCase().includes(q) || 
                (c.phone && c.phone.includes(q))
            );
        },

        get bookFormCustomers() {
            const qName = (this.bookForm.customer_name || '').toLowerCase().trim();
            const qPhone = (this.bookForm.customer_phone || '').toLowerCase().trim();
            
            if (!qName && !qPhone) return [];
            
            return this.customers.filter(c => {
                const matchName = qName && c.fullname.toLowerCase().includes(qName);
                const matchPhone = qPhone && c.phone && c.phone.includes(qPhone);
                return matchName || matchPhone;
            }).slice(0, 5);
        },

        selectBookCustomer(c) {
            this.bookForm.customer_id = c.id;
            this.bookForm.customer_name = c.fullname;
            this.bookForm.customer_phone = c.phone || '';
            this.bookForm.hide_suggestions = true;
        },

        getBadgeClass(level) {
            const l = level || 'Đồng';
            if (l === 'Bạc') return 'bg-slate-100 text-slate-700';
            if (l === 'Vàng') return 'bg-yellow-100 text-yellow-700';
            if (l === 'Bạch kim') return 'bg-blue-100 text-blue-700';
            if (l === 'VIP') return 'bg-purple-100 text-purple-700 border border-purple-200 animate-pulse';
            return 'bg-orange-100 text-orange-700'; // Đồng
        },

        get discountInfo() {
            if (!this.customerLevel) return { percent: 0, freeService: false };
            const b = this.benefits.find(x => x.membership_level === this.customerLevel);
            return b ? { percent: parseInt(b.discount_percent), freeService: b.free_service == 1 } : { percent: 0, freeService: false };
        },

        get discountAmount() {
            const info = this.discountInfo;
            let productDiscount = 0;
            let serviceDiscount = 0;
            
            this.cart.forEach(item => {
                if (item.is_appointment) {
                    if (info.freeService) {
                        serviceDiscount += item.price * item.quantity;
                    } else if (info.percent > 0) {
                        serviceDiscount += (item.price * item.quantity) * (info.percent / 100);
                    }
                } else if (info.percent > 0) {
                    productDiscount += (item.price * item.quantity) * (info.percent / 100);
                }
            });
            return Math.round(productDiscount + serviceDiscount);
        },

        selectCustomer(c) {
            this.customerId = c.id;
            this.customerName = c.fullname;
            this.customerPhone = c.phone || '';
            this.customerLevel = c.membership_level || 'Đồng';
            
            // Tìm kiếm lại trong danh sách để chắc chắn có level mới nhất nếu vừa thay đổi
            const freshCust = this.customers.find(cust => cust.id == c.id);
            if (freshCust) this.customerLevel = freshCust.membership_level;
            
            this.showCustomerSearch = false;
            this.customerSearch = '';
            
            // Tải lịch sử chẩn đoán AI của khách hàng
            this.fetchCustomerAiHistory(this.customerPhone, this.customerName);
        },

        setAsGuest(name) {
            this.customerId = null;
            this.customerName = name + ' (Khách lẻ)';
            this.customerPhone = '';
            this.customerLevel = '';
            this.showCustomerSearch = false;
            this.customerSearch = '';
            this.customerAiHistory = [];
            this.showAiHistoryPanel = false;
        },

        resetCustomer() {
            this.customerId = null;
            this.customerName = '';
            this.customerPhone = '';
            this.customerLevel = '';
            this.showCustomerSearch = false;
            this.customerAiHistory = [];
            this.showAiHistoryPanel = false;
        },

        async fetchCustomerAiHistory(phone, name) {
            if (!phone && !name) {
                this.customerAiHistory = [];
                return;
            }
            try {
                const res = await fetch(`<?php echo URLROOT; ?>/admin/pos_customer_ai_history?phone=${encodeURIComponent(phone)}&name=${encodeURIComponent(name)}`);
                const data = await res.json();
                if (data.success) {
                    this.customerAiHistory = data.history || [];
                    // Tự động mở panel AI tư vấn nếu khách hàng có lịch sử sức khỏe nổi bật
                    if (this.customerAiHistory.length > 0) {
                        this.showAiHistoryPanel = true;
                    }
                } else {
                    this.customerAiHistory = [];
                }
            } catch (err) {
                console.error('Error fetching customer AI history:', err);
                this.customerAiHistory = [];
            }
        },

        get waitingCount() {
            return this.appointments.filter(a => !a.category.toLowerCase().includes('trông giữ')).length;
        },

        get filteredProducts() {
            if (this.activeCategory === 'Dịch vụ') {
                return this.appointments.filter(app => {
                    const isBoarding = app.category.toLowerCase().includes('trông giữ');
                    const matchesSearch = app.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    return !isBoarding && matchesSearch;
                });
            }
            if (this.activeCategory === 'Danh mục Dịch vụ') {
                return this.services.filter(s => {
                    return s.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                });
            }
            return this.products.filter(product => {
                const matchesSearch = product.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                const matchesCategory = this.activeCategory === 'all' || product.category === this.activeCategory;
                return matchesSearch && matchesCategory;
            });
        },

        get totalItems() {
            return this.cart.reduce((total, item) => total + item.quantity, 0);
        },

        get totalPrice() {
            return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        },

        get finalPrice() {
            return this.totalPrice - this.discountAmount;
        },

        filterCategory(category) {
            this.activeCategory = category;
        },

        addToCart(product) {
            if (product.is_service_catalog) {
                this.bookForm.service = product;
                // Nếu khách hàng đang được chọn ở POS, điền sẵn thông tin
                this.bookForm.customer_name = this.customerName || '';
                this.bookForm.customer_phone = this.customerPhone || '';
                this.bookForm.customer_id = null; // Backend xử lý tự do
                const tmr = new Date(); tmr.setDate(tmr.getDate() + 1);
                this.bookForm.date = tmr.toISOString().split('T')[0];
                this.bookForm.time = '09:00';
                this.bookForm.doctor_id = '';
                
                // Set default duration for boarding
                if (product.category.toLowerCase().includes('trông giữ')) {
                    this.bookForm.duration_value = 1;
                    this.bookForm.duration_unit = product.name.toLowerCase().includes('ngắn hạn') ? 'hour' : 'day';
                }

                this.showBookModal = true;
                return;
            }

            if (product.stock <= 0) {
                alert('Sản phẩm đã hết hàng!');
                return;
            }

            // Tự động chọn khách hàng nếu là dịch vụ
            if (product.is_appointment) {
                const cust = this.customers.find(c => c.id == product.customer_id);
                if (cust) {
                    this.selectCustomer(cust);
                } else if (product.customer_name) {
                    this.customerName = product.customer_name;
                    this.customerPhone = product.customer_phone || '';
                    this.customerLevel = 'Đồng';
                }
            }

            const existingItem = this.cart.find(item => item.id === product.id);
            if (existingItem) {
                if (product.is_appointment && !product.category.toLowerCase().includes('trông giữ')) {
                    alert('Dịch vụ này đã có trong giỏ hàng!');
                    return;
                }
                if (existingItem.quantity < product.stock) {
                    existingItem.quantity++;
                } else {
                    alert('Không đủ số lượng tồn kho!');
                }
            } else {
                this.cart.push({
                    ...product,
                    quantity: product.is_appointment ? (product.duration_value || 1) : 1
                });

                // Tự động thêm các sản phẩm trong đơn thuốc kèm theo của lịch hẹn
                if (product.is_appointment && product.prescriptions && product.prescriptions.length > 0) {
                    product.prescriptions.forEach(pres => {
                        const actualProduct = this.products.find(p => p.id === pres.product_id);
                        if (actualProduct) {
                            if (actualProduct.stock <= 0) {
                                alert('Cảnh báo: Sản phẩm "' + actualProduct.name + '" trong đơn thuốc đã hết hàng!');
                            } else if (actualProduct.stock < pres.quantity) {
                                alert('Cảnh báo: Sản phẩm "' + actualProduct.name + '" trong đơn thuốc không đủ tồn kho (Cần ' + pres.quantity + ', Còn ' + actualProduct.stock + ')!');
                            }
                            
                            const existingProd = this.cart.find(item => item.id === actualProduct.id);
                            const addQty = Math.min(parseInt(pres.quantity), actualProduct.stock > 0 ? actualProduct.stock : 1);
                            if (existingProd) {
                                existingProd.quantity = Math.min(existingProd.quantity + addQty, actualProduct.stock > 0 ? actualProduct.stock : 999);
                            } else {
                                this.cart.push({
                                    ...actualProduct,
                                    quantity: addQty,
                                    instruction: pres.instruction || ''
                                });
                            }
                        }
                    });
                }
            }
        },

        increaseQuantity(index) {
            const item = this.cart[index];
            if (item.is_appointment && !item.category.toLowerCase().includes('trông giữ')) return;
            const product = this.products.find(p => p.id === item.id) || this.appointments.find(a => a.id === item.id);
            if (item.quantity < (product ? product.stock : 999)) {
                item.quantity++;
            }
        },

        decreaseQuantity(index) {
            const item = this.cart[index];
            if ((item.is_appointment && !item.category.toLowerCase().includes('trông giữ')) || item.quantity <= 1) {
                this.cart.splice(index, 1);
            } else {
                item.quantity--;
            }
        },

        clearCart() {
            if(confirm('Bạn có chắc chắn muốn hủy giỏ hàng này?')) {
                this.cart = [];
            }
        },

        checkout() {
            if (this.cart.length === 0) return;
            // Mở modal chọn phương thức thanh toán thay vì thanh toán ngay lập tức
            this.paymentMethod = 'cash';
            this.showPaymentModal = true;
        },

        confirmCheckout() {
            if (this.cart.length === 0) return;
            
            // Áp dụng giảm giá vào từng item trước khi gửi lên server
            const info = this.discountInfo;
            const finalCart = this.cart.map(item => {
                let finalItemPrice = item.price;
                if (item.is_appointment) {
                    if (info.freeService) finalItemPrice = 0;
                    else if (info.percent > 0) finalItemPrice = item.price * (1 - info.percent / 100);
                } else if (info.percent > 0) {
                    finalItemPrice = item.price * (1 - info.percent / 100);
                }
                return { ...item, price: Math.round(finalItemPrice) };
            });

            fetch('<?php echo URLROOT; ?>/order/pos_checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    cart: finalCart,
                    customer_name: this.customerName || 'Khách lẻ',
                    customer_phone: this.customerPhone || '',
                    payment_method: this.paymentMethod
                })
            })
            .then(async response => {
                const text = await response.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response:', text);
                    throw new Error('Server returned invalid data');
                }
            })
            .then(data => {
                if (data.success) {
                    // Đóng modal thanh toán và hiển thị hóa đơn nhiệt
                    this.showPaymentModal = false;
                    this.completedOrder = data;
                    this.showReceiptModal = true;
                    
                    // Reset giỏ hàng và dữ liệu khách hàng
                    this.cart = [];
                    this.customerId = null;
                    this.customerName = '';
                    this.customerPhone = '';
                    this.customerLevel = '';
                    this.customerAiHistory = [];
                    this.showAiHistoryPanel = false;
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Checkout Error:', error);
                alert('Lỗi: ' + error.message);
            });
        },

        closeReceiptAndReload() {
            this.showReceiptModal = false;
            this.completedOrder = null;
            window.location.reload();
        },

        printReceipt() {
            const printContent = document.getElementById('thermal-receipt-container').innerHTML;
            const originalContent = document.body.innerHTML;
            
            // In bằng cửa sổ mới chuyên nghiệp
            const printWindow = window.open('', '_blank', 'width=800,height=900');
            printWindow.document.write(`
                <html>
                <head>
                    <title>In Hóa Đơn - PETSHOP</title>
                    <script src="https://cdn.tailwindcss.com"><\/script>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                    <style>
                        @media print {
                            body { margin: 0; padding: 10px; font-family: sans-serif; }
                            @page { size: 80mm auto; margin: 0; }
                        }
                    </style>
                </head>
                <body class="bg-white text-black p-4">
                    <div class="max-w-[80mm] mx-auto">
                        ${printContent}
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(() => { window.close(); }, 500);
                        }
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        },

        formatMoney(amount) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
        },

        submitBooking() {
            if (!this.bookForm.doctor_id || !this.bookForm.customer_name || !this.bookForm.customer_phone || !this.bookForm.date || !this.bookForm.time) {
                alert('Vui lòng điền đủ thông tin bắt buộc.');
                return;
            }
            if (this.isAssigneeBusy(this.bookForm.doctor_id)) {
                if (!confirm('Nhân sự này đã có lịch vào thời gian trên. Bạn có chắc chắn muốn phân công thêm không?')) {
                    return;
                }
            }
            
            const payload = {
                service_id: this.bookForm.service.real_id,
                customer_id: this.bookForm.customer_id,
                customer_name: this.bookForm.is_guest === 'true' ? this.bookForm.customer_name + ' - khách lẻ' : this.bookForm.customer_name,
                customer_phone: this.bookForm.is_guest === 'true' ? '' : this.bookForm.customer_phone,
                appointment_date: this.bookForm.date,
                appointment_time: this.bookForm.time,
                doctor_id: this.bookForm.doctor_id,
                duration_value: this.bookForm.duration_value,
                duration_unit: this.bookForm.duration_unit
            };

            fetch('<?php echo URLROOT; ?>/admin/pos_book_service', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('Đặt dịch vụ thành công!');
                    this.showBookModal = false;
                    
                    // Immediately fetch updated staff schedules to reflect availability instantly
                    fetch('<?php echo URLROOT; ?>/admin/pos_staff_schedules')
                        .then(res => res.json())
                        .then(schedules => {
                            this.staffSchedules = schedules;
                        })
                        .catch(err => console.error('Error updating schedules:', err));
                } else {
                    alert('Lỗi khi đặt dịch vụ');
                }
            })
            .catch(e => { console.error(e); alert('Lỗi máy chủ'); });
        },

        isAssigneeBusy(id) {
            if (!this.bookForm.date || !this.bookForm.time) return false;
            return this.staffSchedules.some(s => 
                s.doctor_id == id && 
                s.appointment_date === this.bookForm.date && 
                s.appointment_time && s.appointment_time.startsWith(this.bookForm.time)
            );
        }
    }));
});
</script>

<?php require APPROOT . '/views/admin/footer.php'; ?>
