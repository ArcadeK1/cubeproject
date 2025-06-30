<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кабинет администратора</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <?php echo app('Illuminate\Foundation\Vite')("resources/css/app.css"); ?>
</head>
<body>

    <nav class="cubenavbar">
        <ul>
            <li>
                <img src ="../media/logo.png" alt="IT-Куб">
            </li>
        </ul>
    </nav>

    <div class="adminroom">
        <div class="fioandlougout">
            <h2><?php echo e(auth()->user()->name); ?></h2>

            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn loginbtn">Выход</button>
            </form>

            <hr>
        </div>

        <div class="adminevents">
            <h2>События</h2>

            <!-- Вывод всех соревнований -->
    
                    <?php $__currentLoopData = $competitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $competition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="compcard">
                            <div class="competitionname">
                                <h2>Состязание</h2>
                                <a href="<?php echo e(route('competitions.edit', $competition->id)); ?>">
                                        <?php echo e($competition->id); ?>&#41; <?php echo e($competition->name); ?>

                                </a>
                            </div>

                            <table>
                            <?php $__currentLoopData = $competition->challenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $competition->participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><p><?php echo e($challenge->title); ?></p></td>
                                        <td><p><?php echo e($challenge->location); ?></p></td>
                                        <td>
                                            
                                            <?php echo e(optional($participant->scores()->where('challenge_id', $challenge->id)->where('competition_id', $competition->id)->first())->score ?? 0); ?>

                                        
                                        </td>
                                    <tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                        </table>


                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                

        </div>
    </div>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\eventserver3\resources\views/competitions/index.blade.php ENDPATH**/ ?>