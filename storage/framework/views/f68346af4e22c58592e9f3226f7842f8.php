<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кабинет судьи</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Кабинет судьи</h1>
        <p>Добро пожаловать, Судья!</p>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-danger">Выйти</button>
        </form>
    </div>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\eventserver\resources\views/judge/dashboard.blade.php ENDPATH**/ ?>