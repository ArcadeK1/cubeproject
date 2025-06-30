<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
    <title><?php echo e($competition->name); ?> | Система проведения мероприятий IT-Куб.Курган</title>
    <?php echo app('Illuminate\Foundation\Vite')("resources/css/app.css"); ?>
</head>
<body>

    <nav class="cubenavbar">
        <ul>
            <li class = 'logo'>
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
            <li class = 'username'>
                <p style=""><?php echo e(auth()->user()->name); ?></p>
                <form style="align-items: center" method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="outtaherebtn">
                        <img src = "../media/exit.jpg" alt = "Выход">
                    </button>
                </form>
            </li>
        </ul>
    </nav>



    <div class="eventinfo">

        <div class="basicname">  
            <div class="minidirections">

                <h4><a href="../admin/dashboard">Мероприятия</a> → <?php echo e($competition->name); ?></h4>

            </div>

            <div class="nameandedit">
                <h1><?php echo e($competition->name); ?></h1>

                <a href="<?php echo e(route('competitions.edit', $competition->id)); ?>" class="editbtn">
                    <img src = "../media/editicon.png" alt="Редактировать">
                </a>
            </div>
            <hr>
        </div>


        <div class="competitioninfo">
            
            <div class="challengetable-wrapper" style="overflow-x: auto; max-width: 100%;"> 
                
                <div class="resultsheader">
                    <h2>Результаты</h2>
                </div>
                <div class="challengetable">
                    <table>
                        <tbody>
                            <!-- Заголовок таблицы -->
                            <tr>
                                <th>Компетенция</th>
                                <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th><?php echo e($team->name); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        
                            <!-- Данные по компетенциям и результатам команд -->
                            
                            <?php $__currentLoopData = $challenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($challenge->title); ?> (<?php echo e($challenge->location); ?>)</td>
                                    <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td>
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


                <div class="competitionjudges">
                    <h3>Судейский состав</h3>
                    <ol>
                        <?php $__empty_1 = true; $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li><?php echo e($judge->name); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li>Судьи не назначены</li>
                        <?php endif; ?>
                    </ol>
                </div>

                <a href="<?php echo e(route('competitions.report', ['competitionID' => $competition->id])); ?>" class="pdfbtn" target="_blank">
                    Скачать отчёт PDF
                </a>

            </div>

        </div>

    </div>
</body>
</html><?php /**PATH C:\OSPanel\domains\eventserver3\resources\views/competitions/info.blade.php ENDPATH**/ ?>