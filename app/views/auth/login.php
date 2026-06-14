<?php require APPROOT . '/views/inc/header.php'; ?>
<style>
/* Hiệu ứng Paws nổi lơ lửng */
.paw-bg { position: absolute; font-size: 2rem; color: rgba(236, 72, 153, 0.15); z-index: 0; animation: floatPaw 8s infinite ease-in-out; }
.paw-bg.paw-1 { top: 10%; left: 10%; animation-delay: 0s; transform: rotate(-20deg); }
.paw-bg.paw-2 { top: 70%; left: 15%; animation-delay: 2s; transform: rotate(15deg); font-size: 3rem; }
.paw-bg.paw-3 { top: 20%; right: 15%; animation-delay: 4s; transform: rotate(25deg); }
.paw-bg.paw-4 { top: 80%; right: 10%; animation-delay: 1s; transform: rotate(-15deg); font-size: 2.5rem; }
.paw-bg.paw-5 { top: 40%; left: 5%; animation-delay: 5s; transform: rotate(45deg); font-size: 1.5rem; }
.paw-bg.paw-6 { top: 50%; right: 5%; animation-delay: 3s; transform: rotate(-45deg); font-size: 1.8rem; }
@keyframes floatPaw {
    0%, 100% { transform: translateY(0) scale(1) rotate(0deg); opacity: 0.5; }
    50% { transform: translateY(-20px) scale(1.1) rotate(10deg); opacity: 1; }
}
</style>

<div class="flex items-center justify-center min-h-[calc(100vh-64px)] py-12 px-4 sm:px-6 lg:px-8 bg-pink-50 relative overflow-hidden">
    <!-- Những dấu chân lơ lửng -->
    <i class="fa-solid fa-paw paw-bg paw-1"></i>
    <i class="fa-solid fa-paw paw-bg paw-2"></i>
    <i class="fa-solid fa-paw paw-bg paw-3"></i>
    <i class="fa-solid fa-paw paw-bg paw-4"></i>
    <i class="fa-solid fa-paw paw-bg paw-5"></i>
    <i class="fa-solid fa-paw paw-bg paw-6"></i>

    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-pink-100 relative z-10">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-pink-100 text-secondary">
                <i class="fa-solid fa-lock text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Đăng nhập hệ thống
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Hoặc
                <a href="<?php echo URLROOT; ?>/auth/register" class="font-medium text-secondary hover:text-pink-500 transition">
                    tạo tài khoản mới
                </a>
            </p>
        </div>
        
        <?php flash('register_success'); ?>

        <form class="mt-8 space-y-6" action="<?php echo URLROOT; ?>/auth/login" method="POST">
            <?php echo csrf_field(); ?>
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email-address" class="sr-only">Địa chỉ Email</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['email_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-secondary focus:border-secondary focus:z-10 sm:text-sm" placeholder="Địa chỉ Email">
                    <span class="text-red-500 text-xs italic"><?php echo $data['email_err']; ?></span>
                </div>
                <div class="relative">
                    <label for="password" class="sr-only">Mật khẩu</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-secondary focus:border-secondary focus:z-10 sm:text-sm pr-10" placeholder="Mật khẩu">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center z-20 h-full">
                        <i class="fa-regular fa-eye text-gray-400 hover:text-secondary transition-colors cursor-pointer" id="eyeIcon"></i>
                    </button>
                    <span class="text-red-500 text-xs italic block mt-1"><?php echo $data['password_err']; ?></span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Nhớ mật khẩu
                    </label>
                </div>

                <div class="text-sm">
                    <a href="<?php echo URLROOT; ?>/auth/forgot_password" class="font-medium text-secondary hover:text-pink-500 transition">
                        Quên mật khẩu?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-secondary hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition duration-300">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fa-solid fa-arrow-right-to-bracket text-pink-400 group-hover:text-pink-300"></i>
                    </span>
                    Đăng nhập
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // JS Logic: Show/Hide Password
    const toggleBtn = document.getElementById('togglePassword');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
