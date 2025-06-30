<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зритель | Система проведения мероприятий IT-Куб.Курган</title>
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <meta name="theme-color" content="#fff">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#fff">
    <?php echo app('Illuminate\Foundation\Vite')("resources/css/app.css"); ?>
    <?php echo app('Illuminate\Foundation\Vite')("resources/js/app.js"); ?>
    <?php echo app('Illuminate\Foundation\Vite')("resources/js/viewerRefresh.js"); ?>
</head>
<body>
    <nav class="cubenavbar">
        <ul>
            <li class="logo">
                <?php
                   
                        $dashboardaddress = "../";
                        echo("<a href ='$dashboardaddress'>");
                        echo("<img src ='../media/logo.png' alt='IT-Куб'>");
                        echo("</a>");
                    
                ?>
            </li class='username'>
                
                <a class="leavebtn" href = "../">
                    <img src="media/exit.jpg" alt="Выход">
                </a>
            </li>
        </ul>
    </nav>

    <div class="eventinfo">


        <div class="viewerwelcome">
            <h1>Теперь вы - Зритель.</h1>
        </div>

        <div class="competitionselector">
            <div class="event-selection">
                <h2>Выберите мероприятие:</h2>
                <input type="date" id="event-date" max="<?php echo e(date('Y-m-d')); ?>">
            </div>
            
            <div class="event-list">
                
                <select id="competition-select">
                    <option value="">Мероприятие</option>
                </select>
            </div>
        </div>
       

            <div class="competitioninfo">
                
                <div class="resultsheader">
                    <h2>Результаты</h2>
                </div>
                
                <div class="challengetable">
                    <table>
                        

                        <tbody>
                            <!-- Заголовок таблицы -->
                            <tr>
                                <th>Испытание </th>
                                <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th><?php echo e($team->name); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        
                            <!-- Данные по компетенциям и результатам команд -->
                            
                            <?php $__currentLoopData = $challenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($challenge->title); ?> (<?php echo e($challenge->location); ?>)</td>
                                    <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td data-challenge-id="<?php echo e($challenge->id); ?>">
                                            <?php if(isset($scoresByChallenge[$challenge->id]['teams'][$team->id])): ?>
                                                <?php echo e($scoresByChallenge[$challenge->id]['teams'][$team->id]['total_score']); ?>/<?php echo e($challenge->points); ?>

                                            <?php else: ?>
                                                0
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>

                    </table>
                </div>


            </div>

        

    </div>

</body>




</html><?php /**PATH C:\OSPanel\domains\eventserver3\resources\views/viewer/index.blade.php ENDPATH**/ ?>