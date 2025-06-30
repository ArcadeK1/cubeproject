<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отчёт о мероприятии</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Отчёт о мероприятии "<?php echo e($competition->name); ?>"</h2>
    

    <p>Команды</p>
    
    <table>
        <thead>
            <tr>
                <th>Название команды</th>
                <th>Логин</th>
                <th>Пароль</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($team->name); ?></td>
                    <td><?php echo e($team->login); ?></td>
                    <td><?php echo e($team->plain_password); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <br>
    <br>


    <p>Судейский состав</p>
    
    <table>
        <thead>
            <tr>
                <th>ФИО судьи</th>
                <th>Логин</th>
                <th>Пароль</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($judge->name); ?></td>
                    <td><?php echo e($judge->login); ?></td>
                    <td><?php echo e($judge->plain_password); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>


</body>
</html>
<?php /**PATH C:\OSPanel\domains\eventserver3\resources\views/pdf/report.blade.php ENDPATH**/ ?>