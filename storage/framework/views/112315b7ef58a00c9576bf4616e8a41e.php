<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
    <title>Мероприятие: <?php echo e($competition->name); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')("resources/css/app.css"); ?>
</head>
<body>
    <nav class="cubenavbar">
        <ul>
            <li class='logo'>
                <?php
                    $dashboardaddress = '';
                    if(auth()->user()->role=='judge') {
                        $dashboardaddress = "../../../judge/dashboard";
                        echo("<a href ='$dashboardaddress'>");
                        echo("<img src ='../../../media/logo.png' alt='IT-Куб'>");
                        echo("</a>");
                    }
                ?>
            </li>
            <li class='username'>
                <p><?php echo e(auth()->user()->name); ?></p>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="outtaherebtn">
                        <img src = "../../../media/exit.jpg" alt = "Выход">
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="scoreeditor">


        <div class="minidirections">
            <h4><a href="../../../judge/dashboard">← Назад к сканированию</a></h4>
        </div>

        <div class="eventandteams">
            <h1>Мероприятие: <?php echo e($competition->name); ?></h1>
            <h2>Команда: <?php echo e($team->name); ?></h2>
        </div>


        <div class="competition-list">
            <h3>Мероприятия команды:</h3>
            <ul>
                <?php $__currentLoopData = $competitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e(route('competitions.show', ['competition' => $comp->id, 'team' => $team->id])); ?>"
                           class="<?php echo e($comp->id == $competition->id ? 'active' : ''); ?>">
                            <?php echo e($comp->name); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        

        <!-- Форма для изменения баллов -->
        <form action="<?php echo e(route('scores.update', ['competition' => $competition->id, 'team' => $team->id])); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
        

            <div class="scoreupdatetable">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Испытание</th>
                            <th>Баллы</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $challenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($challenge->title); ?></td>
                                <td>
                                    <input type="number" class ='pointvalue' name="scores[<?php echo e($challenge->id); ?>]" 
                                           value="<?php echo e($scores[$challenge->id]->score ?? 0); ?>" 
                                           min="0" required>
                            
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            
                <button type="submit" class="btn cubebtn">Сохранить изменения →</button>
            </div>
            
        </form>
    </div>
</body>
</html><?php /**PATH C:\OSPanel\domains\eventserver3\resources\views/competitions/show.blade.php ENDPATH**/ ?>