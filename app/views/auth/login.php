<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="flex items-center justify-center min-h-[calc(100vh-64px)] py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
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
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email-address" class="sr-only">Địa chỉ Email</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['email_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-secondary focus:border-secondary focus:z-10 sm:text-sm" placeholder="Địa chỉ Email">
                    <span class="text-red-500 text-xs italic"><?php echo $data['email_err']; ?></span>
                </div>
                <div>
                    <label for="password" class="sr-only">Mật khẩu</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-secondary focus:border-secondary focus:z-10 sm:text-sm" placeholder="Mật khẩu">
                    <span class="text-red-500 text-xs italic"><?php echo $data['password_err']; ?></span>
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
                    <a href="#" class="font-medium text-secondary hover:text-pink-500">
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
<?php require APPROOT . '/views/inc/footer.php'; ?>
