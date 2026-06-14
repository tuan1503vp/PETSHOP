<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="flex items-center justify-center min-h-[calc(100vh-64px)] py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-indigo-100 text-primary">
                <i class="fa-solid fa-envelope-circle-check text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Xác thực Email
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Chúng tôi đã gửi một mã gồm 6 chữ số đến email <br/>
                <span class="font-semibold text-gray-800"><?php echo isset($_SESSION['verify_email']) ? $_SESSION['verify_email'] : ''; ?></span>
            </p>
        </div>

        <?php flash('verify_msg'); ?>

        <form class="mt-8 space-y-6" action="<?php echo URLROOT; ?>/auth/verify" method="POST">
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="otp" class="sr-only">Mã xác thực (OTP)</label>
                    <input id="otp" name="otp" type="text" pattern="\d{6}" title="Mã OTP phải là 6 chữ số" required maxlength="6" class="appearance-none rounded-none relative block w-full px-3 py-4 border <?php echo (!empty($data['otp_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-lg text-center tracking-[0.5em] font-mono" placeholder="------">
                    <span class="text-red-500 text-xs italic text-center block mt-1"><?php echo isset($data['otp_err']) ? $data['otp_err'] : ''; ?></span>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-300">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fa-solid fa-check-double text-indigo-500 group-hover:text-indigo-400"></i>
                    </span>
                    Xác nhận
                </button>
            </div>
        </form>
        
        <form action="<?php echo URLROOT; ?>/auth/resend_otp" method="POST" class="mt-4">
            <p class="text-center text-sm text-gray-600">
                Không nhận được email? 
                <button type="submit" class="font-medium text-primary hover:text-indigo-500 bg-transparent border-0 cursor-pointer p-0">
                    Gửi lại mã
                </button>
            </p>
        </form>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
