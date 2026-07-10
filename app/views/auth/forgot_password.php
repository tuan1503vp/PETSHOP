<?php 
$remaining_cooldown = 0;
if (isset($_SESSION['last_otp_time'])) {
    $time_passed = time() - $_SESSION['last_otp_time'];
    if ($time_passed < 30) {
        $remaining_cooldown = 30 - $time_passed;
    }
}
require APPROOT . '/views/inc/header.php'; 
?>
<style>
/* Hiệu ứng Paws nổi lơ lửng giống trang đăng nhập */
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
                <i class="fa-solid fa-key text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Quên mật khẩu
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Vui lòng nhập email đã đăng ký tài khoản để lấy mã khôi phục.
            </p>
        </div>

        <form class="mt-8 space-y-6" action="<?php echo URLROOT; ?>/auth/forgot_password" method="POST">
            <?php echo csrf_field(); ?>
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email-address" class="block text-sm font-medium text-gray-700">Địa chỉ Email</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" class="mt-1 appearance-none relative block w-full px-3 py-2 border <?php echo (!empty($data['email_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-secondary focus:border-secondary focus:z-10 sm:text-sm" placeholder="Nhập email của bạn">
                    <span class="text-red-500 text-xs italic block mt-1"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <div class="text-sm">
                    <a href="<?php echo URLROOT; ?>/auth/login" class="font-medium text-gray-500 hover:text-secondary flex items-center transition">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại đăng nhập
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" id="btn-submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-secondary hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition duration-300">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i id="btn-icon" class="fa-solid fa-envelope text-pink-400 group-hover:text-pink-300"></i>
                    </span>
                    <span id="btn-text">Gửi mã khôi phục</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let remaining = <?php echo $remaining_cooldown; ?>;
    const btnSubmit = document.getElementById('btn-submit');
    const icon = document.getElementById('btn-icon');
    const text = document.getElementById('btn-text');
    let timer = null;

    function startTimer(duration) {
        let seconds = duration;
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-75', 'cursor-not-allowed');
        icon.className = 'fa-solid fa-hourglass-start text-pink-300';
        text.innerText = `Vui lòng đợi ${seconds}s`;

        timer = setInterval(function() {
            seconds--;
            if (seconds <= 0) {
                clearInterval(timer);
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-75', 'cursor-not-allowed');
                icon.className = 'fa-solid fa-envelope text-pink-400';
                text.innerText = 'Gửi mã khôi phục';
            } else {
                text.innerText = `Vui lòng đợi ${seconds}s`;
            }
        }, 1000);
    }

    if (remaining > 0) {
        startTimer(remaining);
    }

    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        if (btnSubmit.disabled) return false;
        
        btnSubmit.disabled = true;
        icon.className = 'fa-solid fa-spinner fa-spin text-pink-200';
        text.innerText = 'Đang gửi mã...';
    });
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
