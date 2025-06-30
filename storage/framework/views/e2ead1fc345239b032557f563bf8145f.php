<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <title>Кабинет администратора | Система проведения мероприятий IT-Куб.Курган</title>
    <?php echo app('Illuminate\Foundation\Vite')("resources/css/app.css"); ?>
    <?php echo app('Illuminate\Foundation\Vite')("resources/js/app.js"); ?>
    <?php echo app('Illuminate\Foundation\Vite')("resources/js/trashButton.js"); ?>
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



    <div class="adminroom">
        <div class="adminevents">


            <div class="switcher">
                <p style = "margin-left:7px;">Мероприятия</p>
                <label class="switch">
                    <input type="checkbox" id="toggleactivities">
                    <span class="onoff"></span>
                </label>
                <p>Судьи</p>
            </div>

            <div id="allcompetitions">
                <h2>Мероприятия</h2>
                <!-- Вывод всех испытаний -->
         
                        <?php if($competitions->isEmpty()): ?>
                            <h5>Пока нет мероприятий.</h5>
                        <?php else: ?>
                                <?php $__currentLoopData = $competitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $competition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="cubecompetition">
                                        <!-- Добавьте кнопки для редактирования или удаления -->
                                        <div class="cubecompetition_title">
                                            <a href="<?php echo e(route('competitions.info', $competition->id)); ?>"><?php echo e($competition->name); ?></a>
                                        </div>
                                        <form action="<?php echo e(route('competitions.destroy', $competition->id)); ?>" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить это мероприятие?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="deletebtn">
                                                <img src="../media/trash.svg" 
                                                     data-default="../media/trash.svg" 
                                                     data-hover="../media/trashred.png" 
                                                     height="50px" alt="Удалить">
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        
                
                    <div class="neweventbutton">
                        <a href="<?php echo e(route('competitions.create')); ?>" class="cubebtn">Новое мероприятие</a>
                    </div>
                
            </div>



            <div id="alljudges" style="display: none">
                <h2>Судейский состав</h2>
                <!-- Вывод всех судей -->
         
                        <?php if($judges->isEmpty()): ?>
                            <h5>Пока нет мероприятий.</h5>
                        <?php else: ?>
                                <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="cubecompetition">
                                        <!-- Добавьте кнопки для редактирования или удаления -->
                                        <div class="cubecompetition_title">
                                            <p><?php echo e($judge->name); ?></p>
                                        </div>

                                        <form action="<?php echo e(route('judges.destroy', $judge->id)); ?>" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этого судью?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="deletebtn">
                                                <img src="../media/trash.svg" 
                                                     data-default="../media/trash.svg" 
                                                     data-hover="../media/trashred.png" 
                                                     height="50px" alt="Удалить">
                                            </button>
                                        </form>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        
                
                    <div class="neweventbutton">
                        <a href="<?php echo e(route('judges.create')); ?>" class="cubebtn">Добавить судью</a>
                    </div>
                
            </div>
            
                    
        </div>

    </div>
</body>



<script>
    document.addEventListener('DOMContentLoaded', () => {
    const toggleCheckbox = document.getElementById('toggleactivities');
    const allCompetitions = document.getElementById('allcompetitions');
    const allJudges = document.getElementById('alljudges');

    toggleCheckbox.addEventListener('change', () => {
        if (toggleCheckbox.checked) {
            allCompetitions.style.display = 'none';
            allJudges.style.display = 'block';

        } else {
            allCompetitions.style.display = 'block';
            allJudges.style.display = 'none';
            
        }
    });
});

</script>

</html>
<?php /**PATH C:\OSPanel\domains\eventserver3\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>