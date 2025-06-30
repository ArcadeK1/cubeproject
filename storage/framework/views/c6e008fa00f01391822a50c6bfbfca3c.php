<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="media/logosmall4.png" />
    <meta name="theme-color" content="#ffffff">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
    <title>Система проведения мероприятий IT-Куб.Курган</title>
    <?php echo app('Illuminate\Foundation\Vite')("resources/css/app.css"); ?>
    <link rel="manifest" href="/manifest.json">
    

</head>
<body>
    <div class="cuberedirector">
        <div class="cubelogo">
            <img src ="media/logosmall.png" height="200" alt="IT-Куб">
        </div>

        <div class="cuberedirect">
            <a class ="redirectbtn" href="/login">Вход в систему оценивания</a>
            <h5>или</h5>
        </div>

        <div class="cuberedirect">
            <a class ="beaviewerbtn" href="/viewer">Войти как зритель</a>
        </div>
        


        <div id='addmebanner' class="addme">
            <img src ="media/addme.jpg" height="100" alt="Добавь меня!">
            <p>Добавь меня на экран "Домой", и я <br> превращусь в веб-приложение!</p>
        </div>
        
    </div>
</body>
</html>



<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
            .then((reg) => console.log("Service Worker зарегистрирован!", reg))
            .catch((err) => console.log("Ошибка регистрации Service Worker", err));
    }



    if (window.matchMedia('(display-mode: standalone)').matches) 
    {
       document.getElementById('addmebanner').style.display = 'none';
    }

    if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) 
    {
        document.getElementById('addmebanner').style.display = 'none';
    }





    </script><?php /**PATH C:\OSPanel\domains\eventserver3\resources\views/index.blade.php ENDPATH**/ ?>