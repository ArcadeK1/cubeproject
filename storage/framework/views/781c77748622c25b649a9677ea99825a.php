<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание команды | Система проведения мероприятий IT-Куб.Курган</title>
    <meta name="theme-color" content="#fff">
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#fff">
    <?php echo app('Illuminate\Foundation\Vite')("resources/css/app.css"); ?>
    <?php echo app('Illuminate\Foundation\Vite')("resources/js/app.js"); ?>
</head>
<body>
    <nav class="cubenavbar">
        <ul>
            <li class="logo">
                <?php
                    $dashboardaddress = '';
                    if(auth()->user()->role=='admin')
                    {
                        $dashboardaddress = "../admin/dashboard";
                        echo("<a href ='$dashboardaddress'>");
                        echo("<img src ='../media/logo.png' alt='IT-Куб'>");
                        echo("</a>");
                    }
                ?>
            </li>
            <li class="username">
                <p><?php echo e(auth()->user()->name); ?></p>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="outtaherebtn">
                        <img src="../media/exit.jpg" alt="Выход">
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="basicname">  
        <div class="minidirections">
            <h4><a href="../admin/dashboard">События</a> -> Новая команда</h4>
        </div>
    </div>

    <div class="createacompetition">
        <h1>Новая команда</h1>

        <!-- Отображение сообщений об ошибках -->
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Отображение сообщения об успехе и данных для входа -->
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

                <p><strong>Команда:</strong> <?php echo e(session('name')); ?></p>
                <p><strong>Логин:</strong> <?php echo e(session('login')); ?></p>
                <p><strong>Пароль:</strong> <?php echo e(session('password')); ?></p>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('teams.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <!-- Поле для имени команды -->
            <div class="floating-label">
                <label for="name" class="form-label">Имя команды</label> <br>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <button type="submit" class="btn cubebtn">Создать команду</button>
        </form>
    </div>
</body>
</html><?php /**PATH C:\OSPanel\domains\eventserver3\resources\views/teams/create.blade.php ENDPATH**/ ?>