<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#ffffff>
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
    <title>Вход</title>
    <?php echo app('Illuminate\Foundation\Vite')("resources/css/app.css"); ?>
</head>
<body>
    <div class="cubelogin">

        <div class="cubelogo">
            <a href="../">
                <img src ="media/logosmall.png" height="200" alt="IT-Куб">
            </a>
        </div>

        <div class="loginform">
            <h2>Вход</h2>
            <?php if(session('error')): ?>
                <p style="color: red;"><?php echo e(session('error')); ?></p>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <div class="login_field">
                    <label for="login">Логин</label><br>
                    <input type="text" id="login" name="login" required>
                </div>
                <div class="password_field">
                    <label for="password">Пароль</label><br>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="formbutton">
                    <button class="loginbtn" type="submit">Войти</button>
                </form>
                </div>
                

        </div>
    </div>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\eventserver3\resources\views/auth/login.blade.php ENDPATH**/ ?>