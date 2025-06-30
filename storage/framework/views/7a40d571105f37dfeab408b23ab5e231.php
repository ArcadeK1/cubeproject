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
    <title>Команда | Система проведения мероприятий IT-Куб.Курган</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <?php echo app('Illuminate\Foundation\Vite')("resources/css/app.css"); ?>
    <?php echo app('Illuminate\Foundation\Vite')("resources/js/app.js"); ?>
</head>
<body>
    <nav class="cubenavbar">
        <ul>
            <li class="logo">
                <?php
                    $dashboardaddress = '';
                    if(auth()->user()->role=='team') {
                        $dashboardaddress = "../team/dashboard";
                        echo("<a href ='$dashboardaddress'>");
                        echo("<img src ='../media/logo.png' alt='IT-Куб'>");
                        echo("</a>");
                    }
                ?>
            </li>
            <li class="username">
                <form style="align-items: center" method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="outtaherebtn">
                        <img src="../media/exit.jpg" alt="Выход">
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="teamroom">
        <!-- Заголовок -->
        <h1 class="teamname"><?php echo e(auth()->user()->name); ?></h1>

        <!-- QR-код с именем команды -->
        <div class="flex justify-center mb-8">
            <?php echo QrCode::size(180)->encoding('UTF-8')->generate(auth()->user()->name); ?>

        </div>

        <!-- Разделитель -->
        <hr class="border-t border-black my-8 w-4/5 mx-auto">

        <?php if($competition): ?>
            <!-- Название соревнования -->
            <h2 class="text-2xl text-center mb-4">Соревнование: <?php echo e($competition->name); ?></h2>

            <!-- Таблица с баллами -->
            <h3 class="text-2xl text-center mb-4">Критерии</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="teamstatheader text-white">
                        <tr>
                            <td class="criterion">Испытание</td>
                            <td class="criterion">Место</td>
                            <td class="criterion">Балл</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $challenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100" data-challenge-id="<?php echo e($challenge->id); ?>">
                                <td class="py-3 px-4 text-center"><?php echo e($challenge->title); ?></td>
                                <td class="py-3 px-4 text-center"><?php echo e($challenge->location); ?></td>
                                <td class="py-3 px-4 text-center"><?php echo e($scores[$challenge->id]->score ?? 0); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    
                </table>
            </div>
        <?php else: ?>
            <!-- Сообщение, если команда не участвует в мероприятиях -->
            <div class="text-center text-gray-600">
                <p>Команда не участвует ни в одном мероприятии.</p>
            </div>
        <?php endif; ?>
    </div>
</body>



<script>

function fetchScores() {
    fetch("/refresh-scores")
        .then(response => response.json())
        .then(scores => {
            console.log("Полученные баллы:", scores); // Проверяем, что пришло с сервера

            scores.forEach(score => {
                // Ищем строку испытания по атрибуту `data-challenge-id`
                const row = document.querySelector(`tr[data-challenge-id="${score.challenge_id}"]`);

                if (row) {
                    // Обновляем только колонку с баллами (третья колонка)
                    row.cells[2].textContent = score.score;
                }
            });
        })
        .catch(error => console.error("Ошибка загрузки:", error));
}

// Обновляем баллы каждые 5 секунд
setInterval(fetchScores, 4000);

</script>



</html><?php /**PATH C:\OSPanel\domains\eventserver3\resources\views/team/dashboard.blade.php ENDPATH**/ ?>