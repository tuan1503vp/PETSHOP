<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl">Liên hệ với PETSHOP</h1>
            <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">Chúng tôi luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn về sản phẩm và dịch vụ.</p>
        </div>

        <?php if(isset($_SESSION['contact_success'])): ?>
            <div class="mb-8 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm max-w-3xl mx-auto">
                <div class="flex">
                    <i class="fa-solid fa-circle-check text-xl mr-3"></i>
                    <p><?php echo $_SESSION['contact_success']; unset($_SESSION['contact_success']); ?></p>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['contact_error'])): ?>
            <div class="mb-8 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm max-w-3xl mx-auto">
                <div class="flex">
                    <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
                    <p><?php echo $_SESSION['contact_error']; unset($_SESSION['contact_error']); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-4xl mx-auto flex flex-col md:flex-row">
            <!-- Contact Info -->
            <div class="bg-primary p-10 text-white md:w-1/3 flex flex-col justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-6">Thông tin liên hệ</h3>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <i class="fa-solid fa-location-dot mt-1 text-xl text-primary-light opacity-80 w-6"></i>
                            <span class="ml-4 text-sm">Số 3, Vũ Công Đán, P.Tứ Minh, Hải Phòng</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fa-solid fa-phone text-xl text-primary-light opacity-80 w-6"></i>
                            <span class="ml-4 text-sm font-semibold">0947.647.052</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fa-solid fa-envelope text-xl text-primary-light opacity-80 w-6"></i>
                            <span class="ml-4 text-sm">nmtvp11223311@gmail.com</span>
                        </div>
                    </div>
                </div>
                <div class="mt-10">
                    <h4 class="text-sm font-semibold mb-4 uppercase tracking-wider">Mạng xã hội</h4>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/nmtuan2004" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://zalo.me/0947647052" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                            <strong class="font-black text-xs">Z</strong>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="p-10 md:w-2/3">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Gửi tin nhắn cho chúng tôi</h3>
                <form action="<?php echo URLROOT; ?>/contact/submit" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Họ và tên</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>" required class="py-3 px-4 block w-full shadow-sm focus:ring-primary focus:border-primary border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>" required class="py-3 px-4 block w-full shadow-sm focus:ring-primary focus:border-primary border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Nội dung tin nhắn</label>
                        <div class="mt-1">
                            <textarea id="message" name="message" rows="4" required class="py-3 px-4 block w-full shadow-sm focus:ring-primary focus:border-primary border border-gray-300 rounded-md placeholder-gray-400" placeholder="Bạn cần hỗ trợ gì?"></textarea>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all">
                            Gửi phản hồi <i class="fa-solid fa-paper-plane ml-2 mt-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
