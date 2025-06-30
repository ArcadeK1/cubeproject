<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body>
    <div class="container">
        <h2>Вход в систему</h2>
        <?php if(session('error')): ?>
            <p style="color: red;"><?php echo e(session('error')); ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>
            <div>
                <label for="login">Логин:</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div>
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\eventserver\resources\views/auth/login.blade.php ENDPATH**/ ?>