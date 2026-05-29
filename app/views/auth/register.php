<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="flex items-center justify-center min-h-[calc(100vh-64px)] py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
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
                    <span class="text-red-500 text-xs italic"><?php echo $data['email_err']; ?></span>
                </div>
                <div>
                    <label for="phone" class="sr-only">Số điện thoại</label>
                    <input id="phone" name="phone" type="text" value="<?php echo isset($data['phone']) ? $data['phone'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['phone_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="Số điện thoại">
                    <span class="text-red-500 text-xs italic"><?php echo $data['phone_err']; ?></span>
                </div>
                <div>
                    <label for="password" class="sr-only">Mật khẩu</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="Mật khẩu">
                    <span class="text-red-500 text-xs italic"><?php echo $data['password_err']; ?></span>
                </div>
                <div>
                    <label for="confirm_password" class="sr-only">Xác nhận mật khẩu</label>
                    <input id="confirm_password" name="confirm_password" type="password" value="<?php echo isset($data['confirm_password']) ? $data['confirm_password'] : ''; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="Xác nhận mật khẩu">
                    <span class="text-red-500 text-xs italic"><?php echo $data['confirm_password_err']; ?></span>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-300">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fa-solid fa-check text-indigo-500 group-hover:text-indigo-400"></i>
                    </span>
                    Đăng ký
                </button>
            </div>
        </form>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
