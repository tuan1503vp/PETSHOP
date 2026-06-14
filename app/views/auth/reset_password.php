<?php require APPROOT . '/views/inc/header.php'; ?>

<style>
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
    <i class="fa-solid fa-paw paw-bg paw-1"></i>
    <i class="fa-solid fa-paw paw-bg paw-2"></i>
    <i class="fa-solid fa-paw paw-bg paw-3"></i>
    <i class="fa-solid fa-paw paw-bg paw-4"></i>
    <i class="fa-solid fa-paw paw-bg paw-5"></i>
    <i class="fa-solid fa-paw paw-bg paw-6"></i>

    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-pink-100 relative z-10">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-pink-100 text-secondary">
                <i class="fa-solid fa-unlock-keyhole text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Tạo mật khẩu mới
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Mã xác nhận (OTP) đã được gửi đến email <span class="font-bold"><?php echo $_SESSION['reset_email'] ?? ''; ?></span>
            </p>
        </div>

        <form class="mt-8 space-y-6" action="<?php echo URLROOT; ?>/auth/reset_password" method="POST">
            <?php echo csrf_field(); ?>
            <div class="space-y-4">
                <!-- Nhập OTP -->
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700">Mã OTP</label>
                    <input id="otp" name="otp" type="text" maxlength="6" autocomplete="off" class="mt-1 appearance-none relative block w-full px-3 py-2 border <?php echo (!empty($data['otp_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm tracking-widest font-mono text-center text-lg" placeholder="123456">
                    <span class="text-red-500 text-xs italic block mt-1"><?php echo isset($data['otp_err']) ? $data['otp_err'] : ''; ?></span>
                </div>

                <!-- Mật khẩu mới -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
                    <div class="relative mt-1">
                        <input id="password" name="password" type="password" class="appearance-none block w-full px-3 py-2 border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm pr-10" placeholder="Ít nhất 8 ký tự">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center z-20">
                            <i class="fa-regular fa-eye text-gray-400 hover:text-secondary cursor-pointer" id="eyeIcon"></i>
                        </button>
                    </div>
                    <span class="text-red-500 text-xs italic block mt-1" id="password-error"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>
                    
                    <!-- Password Strength Meter -->
                    <div class="mt-2" id="password-strength-container" style="display: none;">
                        <div class="flex gap-1 h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-300 w-1/4" id="str-1"></div>
                            <div class="h-full transition-all duration-300 w-1/4" id="str-2"></div>
                            <div class="h-full transition-all duration-300 w-1/4" id="str-3"></div>
                            <div class="h-full transition-all duration-300 w-1/4" id="str-4"></div>
                        </div>
                        <p class="text-xs mt-1 font-medium text-right transition-colors duration-300" id="password-strength-text">Yếu</p>
                    </div>
                </div>

                <!-- Xác nhận mật khẩu -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
                    <div class="relative mt-1">
                        <input id="confirm_password" name="confirm_password" type="password" class="appearance-none block w-full px-3 py-2 border <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm pr-10" placeholder="Nhập lại mật khẩu">
                        <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center z-20">
                            <i class="fa-regular fa-eye text-gray-400 hover:text-secondary cursor-pointer" id="eyeIconConfirm"></i>
                        </button>
                    </div>
                    <span class="text-red-500 text-xs italic block mt-1" id="confirm-password-error"><?php echo isset($data['confirm_password_err']) ? $data['confirm_password_err'] : ''; ?></span>
                </div>
            </div>

            <div>
                <button type="submit" id="btn-submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-secondary hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition duration-300">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i id="btn-icon" class="fa-solid fa-check text-pink-400 group-hover:text-pink-300"></i>
                    </span>
                    <span id="btn-text">Đổi mật khẩu</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // JS Logic: Loading Spinner
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        const btn = document.getElementById('btn-submit');
        const icon = document.getElementById('btn-icon');
        const text = document.getElementById('btn-text');
        
        if (btn.disabled) return false;
        btn.disabled = true;
        icon.className = 'fa-solid fa-spinner fa-spin text-pink-200';
        text.innerText = 'Đang xử lý...';
    });

    // JS Logic: Show/Hide Password
    function setupTogglePassword(toggleBtnId, inputId, iconId) {
        document.getElementById(toggleBtnId).addEventListener('click', function() {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
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
    setupTogglePassword('togglePassword', 'password', 'eyeIcon');
    setupTogglePassword('toggleConfirmPassword', 'confirm_password', 'eyeIconConfirm');

    // JS Logic: Password Strength Meter
    const password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');
    const strBars = [document.getElementById('str-1'), document.getElementById('str-2'), document.getElementById('str-3'), document.getElementById('str-4')];
    const strText = document.getElementById('password-strength-text');
    const strContainer = document.getElementById('password-strength-container');
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;

    function calculateStrength(pw) {
        let score = 0;
        if (!pw) return score;
        if (pw.length >= 8) score++;
        if (/[A-Z]/.test(pw)) score++;
        if (/[0-9]/.test(pw)) score++;
        if (/[^A-Za-z0-9]/.test(pw)) score++;
        return score;
    }

    password.addEventListener('input', function() {
        const val = this.value;
        if (val.length > 0) {
            strContainer.style.display = 'block';
            const score = calculateStrength(val);
            
            strBars.forEach(bar => { bar.style.backgroundColor = ''; bar.className = 'h-full transition-all duration-300 w-1/4'; });
            
            let colorClass = '';
            let text = '';
            let textColor = '';

            if (score <= 1) {
                colorClass = 'bg-red-500'; text = 'Yếu'; textColor = 'text-red-500';
                strBars[0].classList.add(colorClass);
            } else if (score === 2) {
                colorClass = 'bg-yellow-500'; text = 'Trung bình'; textColor = 'text-yellow-500';
                strBars[0].classList.add(colorClass); strBars[1].classList.add(colorClass);
            } else if (score === 3) {
                colorClass = 'bg-blue-500'; text = 'Tốt'; textColor = 'text-blue-500';
                strBars[0].classList.add(colorClass); strBars[1].classList.add(colorClass); strBars[2].classList.add(colorClass);
            } else {
                colorClass = 'bg-green-500'; text = 'Rất mạnh'; textColor = 'text-green-500';
                strBars.forEach(b => b.classList.add(colorClass));
            }

            strText.innerText = text;
            strText.className = `text-xs mt-1 font-medium text-right transition-colors duration-300 ${textColor}`;
            
            validateField(this, passwordRegex, 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ và số', 'password-error');
            if (confirm_password.value) {
                validateConfirmPassword();
            }
        } else {
            strContainer.style.display = 'none';
            document.getElementById('password-error').innerText = '';
        }
    });

    function validateConfirmPassword() {
        if (confirm_password.value && confirm_password.value !== password.value) {
            document.getElementById('confirm-password-error').innerText = 'Mật khẩu xác nhận không khớp';
            confirm_password.classList.add('border-red-500');
        } else {
            document.getElementById('confirm-password-error').innerText = '';
            confirm_password.classList.remove('border-red-500');
        }
    }

    function validateField(input, regex, errorMessage, errorElementId) {
        if (input.value && !regex.test(input.value)) {
            document.getElementById(errorElementId).innerText = errorMessage;
            input.classList.add('border-red-500');
        } else {
            document.getElementById(errorElementId).innerText = '';
            input.classList.remove('border-red-500');
        }
    }

    confirm_password.addEventListener('input', validateConfirmPassword);
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
