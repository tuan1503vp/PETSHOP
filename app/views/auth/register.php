<?php require APPROOT . '/views/inc/header.php'; ?>
<style>
/* Hiệu ứng Paws nổi lơ lửng */
.paw-bg { position: absolute; font-size: 2rem; color: rgba(99, 102, 241, 0.15); z-index: 0; animation: floatPaw 8s infinite ease-in-out; }
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

/* Hiệu ứng bé chó mèo ngó đầu */
.pet-head {
    width: 100%;
    height: 100%;
    transform-origin: bottom center;
    transition: transform 0.1s ease-out;
}
.pet-paw {
    box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
}
</style>

<div class="flex items-center justify-center min-h-[calc(100vh-64px)] py-12 px-4 sm:px-6 lg:px-8 bg-indigo-50 relative overflow-hidden">
    <!-- Những dấu chân lơ lửng -->
    <i class="fa-solid fa-paw paw-bg paw-1"></i>
    <i class="fa-solid fa-paw paw-bg paw-2"></i>
    <i class="fa-solid fa-paw paw-bg paw-3"></i>
    <i class="fa-solid fa-paw paw-bg paw-4"></i>
    <i class="fa-solid fa-paw paw-bg paw-5"></i>
    <i class="fa-solid fa-paw paw-bg paw-6"></i>

    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-indigo-100 relative z-10 mt-16">
        
        <!-- Thú cưng tương tác trên khung -->
        <div class="absolute top-0 left-0 w-full h-0 pointer-events-none z-20">
            <!-- Bé Cún bên trái -->
            <div class="absolute -top-[60px] left-[15%] w-16 h-16">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Animals/Dog%20Face.png" alt="Dog" class="pet-head" id="dog-head">
                <div class="pet-paw absolute -bottom-[6px] left-[6px] w-[18px] h-[22px] bg-[#d39f72] rounded-t-full border-[3px] border-white z-10"></div>
                <div class="pet-paw absolute -bottom-[6px] right-[6px] w-[18px] h-[22px] bg-[#d39f72] rounded-t-full border-[3px] border-white z-10"></div>
            </div>
            
            <!-- Bé Mèo bên phải -->
            <div class="absolute -top-[60px] right-[15%] w-16 h-16">
                <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Animals/Cat%20Face.png" alt="Cat" class="pet-head" id="cat-head">
                <div class="pet-paw absolute -bottom-[6px] left-[6px] w-[18px] h-[22px] bg-[#fac842] rounded-t-full border-[3px] border-white z-10"></div>
                <div class="pet-paw absolute -bottom-[6px] right-[6px] w-[18px] h-[22px] bg-[#fac842] rounded-t-full border-[3px] border-white z-10"></div>
            </div>
        </div>
        
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-indigo-100 text-primary">
                <i class="fa-solid fa-user-plus text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Đăng ký tài khoản
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Hoặc
                <a href="<?php echo URLROOT; ?>/auth/login" class="font-medium text-primary hover:text-indigo-500 transition">
                    đăng nhập nếu đã có tài khoản
                </a>
            </p>
        </div>
        <form class="mt-8 space-y-6" action="<?php echo URLROOT; ?>/auth/register" method="POST">
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="fullname" class="sr-only">Họ và tên</label>
                    <input id="fullname" name="fullname" type="text" value="<?php echo isset($data['fullname']) ? $data['fullname'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['fullname_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="Họ và tên">
                    <span class="text-red-500 text-xs italic"><?php echo $data['fullname_err']; ?></span>
                </div>
                <div>
                    <label for="email-address" class="sr-only">Địa chỉ Email</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['email_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="Địa chỉ Email">
                    <span id="email-error" class="text-red-500 text-xs italic"><?php echo $data['email_err']; ?></span>
                </div>
                <div>
                    <label for="phone" class="sr-only">Số điện thoại</label>
                    <input id="phone" name="phone" type="text" pattern="^(0|\+84)(3|5|7|8|9)[0-9]{8}$" title="Gồm 10 số, bắt đầu bằng 0 hoặc +84" value="<?php echo isset($data['phone']) ? $data['phone'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['phone_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="Số điện thoại">
                    <span id="phone-error" class="text-red-500 text-xs italic"><?php echo $data['phone_err']; ?></span>
                </div>
                <div class="relative">
                    <label for="password" class="sr-only">Mật khẩu</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" title="Ít nhất 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm pr-10" placeholder="Mật khẩu">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center z-20 top-0 bottom-0 mb-[45px]">
                        <i class="fa-regular fa-eye text-gray-400 hover:text-primary transition-colors cursor-pointer" id="eyeIcon"></i>
                    </button>
                    <!-- Password Strength Meter -->
                    <div class="mt-2 flex gap-1 h-1 w-full rounded-full overflow-hidden bg-gray-200">
                        <div id="str-1" class="h-full flex-1 transition-all duration-300"></div>
                        <div id="str-2" class="h-full flex-1 transition-all duration-300"></div>
                        <div id="str-3" class="h-full flex-1 transition-all duration-300"></div>
                        <div id="str-4" class="h-full flex-1 transition-all duration-300"></div>
                    </div>
                    <p id="password-strength-text" class="mt-1 text-xs text-gray-500">Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký đặc biệt.</p>
                    <span id="password-error" class="text-red-500 text-xs italic block mt-1"><?php echo $data['password_err']; ?></span>
                </div>
                <div class="relative">
                    <label for="confirm_password" class="sr-only">Xác nhận mật khẩu</label>
                    <input id="confirm_password" name="confirm_password" type="password" value="<?php echo isset($data['confirm_password']) ? $data['confirm_password'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm pr-10" placeholder="Xác nhận mật khẩu">
                    <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center z-20">
                        <i class="fa-regular fa-eye text-gray-400 hover:text-primary transition-colors cursor-pointer" id="eyeIconConfirm"></i>
                    </button>
                    <span id="confirm-password-error" class="text-red-500 text-xs italic block mt-1"><?php echo $data['confirm_password_err']; ?></span>
                </div>
            </div>

            <div>
                <button id="btn-register" type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-300 disabled:opacity-75 disabled:cursor-not-allowed">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i id="btn-icon" class="fa-solid fa-check text-indigo-500 group-hover:text-indigo-400"></i>
                    </span>
                    <span id="btn-text">Đăng ký</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const email = document.getElementById('email-address');
    const phone = document.getElementById('phone');
    const password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');

    function validateField(input, regex, errorMessage, errorSpanId) {
        const errorSpan = document.getElementById(errorSpanId);
        input.addEventListener('input', function() {
            if (this.value.trim() === '') {
                this.classList.remove('border-red-500', 'border-green-500', 'border-2');
                this.classList.add('border-gray-300');
                if(errorSpan) errorSpan.innerText = '';
                return;
            }
            if (regex.test(this.value)) {
                this.classList.remove('border-red-500', 'border-gray-300');
                this.classList.add('border-green-500', 'border-2');
                if(errorSpan) errorSpan.innerText = '';
            } else {
                this.classList.remove('border-green-500', 'border-gray-300');
                this.classList.add('border-red-500', 'border-2');
                if(errorSpan) errorSpan.innerText = errorMessage;
            }
            
            if (input.id === 'password') validateConfirmPassword();
        });
    }

    function validateConfirmPassword() {
        const errorSpan = document.getElementById('confirm-password-error');
        if (confirm_password.value.trim() === '') {
            confirm_password.classList.remove('border-red-500', 'border-green-500', 'border-2');
            confirm_password.classList.add('border-gray-300');
            if(errorSpan) errorSpan.innerText = '';
            return;
        }
        if (confirm_password.value === password.value) {
            confirm_password.classList.remove('border-red-500', 'border-gray-300');
            confirm_password.classList.add('border-green-500', 'border-2');
            if(errorSpan) errorSpan.innerText = '';
        } else {
            confirm_password.classList.remove('border-green-500', 'border-gray-300');
            confirm_password.classList.add('border-red-500', 'border-2');
            if(errorSpan) errorSpan.innerText = 'Mật khẩu xác nhận không khớp';
        }
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^(0|\+84)(3|5|7|8|9)[0-9]{8}$/;
    const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

    validateField(email, emailRegex, 'Định dạng email không hợp lệ', 'email-error');
    validateField(phone, phoneRegex, 'Số điện thoại không hợp lệ (Gồm 10 số, bắt đầu bằng 0 hoặc +84)', 'phone-error');
    validateField(password, passwordRegex, '', 'password-error');
    confirm_password.addEventListener('input', validateConfirmPassword);
    
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
    const strBars = [document.getElementById('str-1'), document.getElementById('str-2'), document.getElementById('str-3'), document.getElementById('str-4')];
    const strText = document.getElementById('password-strength-text');
    
    password.addEventListener('input', function() {
        const val = this.value;
        let score = 0;
        if(val.length >= 8) score++;
        if(/[A-Z]/.test(val)) score++;
        if(/[a-z]/.test(val) && /[0-9]/.test(val)) score++;
        if(/[\W_]/.test(val)) score++;

        strBars.forEach(bar => bar.className = 'h-full flex-1 transition-all duration-300');
        
        if (val.length === 0) {
            strText.innerText = 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.';
            strText.className = 'mt-1 text-xs text-gray-500';
        } else if (score <= 1) {
            strBars[0].classList.add('bg-red-500');
            strText.innerText = 'Yếu: Hãy thêm số và ký tự đặc biệt';
            strText.className = 'mt-1 text-xs text-red-500 font-semibold';
        } else if (score === 2 || score === 3) {
            strBars[0].classList.add('bg-yellow-500');
            strBars[1].classList.add('bg-yellow-500');
            if(score===3) strBars[2].classList.add('bg-yellow-500');
            strText.innerText = 'Khá: Gần đủ mạnh rồi!';
            strText.className = 'mt-1 text-xs text-yellow-600 font-semibold';
        } else if (score === 4) {
            strBars.forEach(bar => bar.classList.add('bg-green-500'));
            strText.innerText = 'Mạnh: Mật khẩu tuyệt vời!';
            strText.className = 'mt-1 text-xs text-green-600 font-semibold';
        }
    });

    // JS Logic: Loading Spinner on Submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        const btn = document.getElementById('btn-register');
        const icon = document.getElementById('btn-icon');
        const text = document.getElementById('btn-text');
        
        // Prevent double click
        if (btn.disabled) return false;
        
        btn.disabled = true;
        icon.className = 'fa-solid fa-spinner fa-spin text-indigo-200';
        text.innerText = 'Đang xử lý...';
    });
    
    // JS Logic: Thú cưng tương tác theo chuột
    document.addEventListener('mousemove', function(e) {
        const mouseX = e.clientX;
        const mouseY = e.clientY;
        
        function trackMouse(petId) {
            const pet = document.getElementById(petId);
            if (!pet) return;
            
            const rect = pet.getBoundingClientRect();
            const petX = rect.left + rect.width / 2;
            const petY = rect.top + rect.height / 2;
            
            const dx = mouseX - petX;
            const dy = mouseY - petY;
            
            const windowWidth = window.innerWidth;
            const windowHeight = window.innerHeight;
            
            // Tính toán mức độ di chuyển và xoay
            const maxMoveX = 12; // Di chuyển tối đa sang 2 bên
            const maxMoveY = 8;  // Di chuyển tối đa lên xuống
            const maxRotate = 30; // Góc xoay tối đa
            
            const moveX = (dx / windowWidth) * maxMoveX * 2;
            let moveY = (dy / windowHeight) * maxMoveY * 2;
            
            // Giới hạn để đầu không lún quá sâu vào viền (bị đè)
            if (moveY > 3) moveY = 3; 
            
            const rotate = (dx / windowWidth) * maxRotate * 2;
            
            pet.style.transform = `translate(${moveX}px, ${moveY}px) rotate(${rotate}deg)`;
        }
        
        // Cập nhật cả 2 bé
        trackMouse('dog-head');
        trackMouse('cat-head');
    });
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
