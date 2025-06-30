<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <title>Кабинет судьи</title>
    <meta name="theme-color" content="#ffffff">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
    <?php echo app('Illuminate\Foundation\Vite')("resources/css/app.css"); ?>
    <?php echo app('Illuminate\Foundation\Vite')("resources/js/qrSearch.js"); ?>
    <script src="https://cdn.jsdelivr.net/npm/jsqr"></script>
</head>
<body>
    <nav class="cubenavbar">
        <ul>
            <li class='logo'>
                <?php
                    $dashboardaddress = '';
                    if(auth()->user()->role=='judge') {
                        $dashboardaddress = "../judge/dashboard";
                        echo("<a href ='$dashboardaddress'>");
                        echo("<img src ='../media/logo.png' alt='IT-Куб'>");
                        echo("</a>");
                    }
                ?>
            </li>
            <li class='username'>
                <p><?php echo e(auth()->user()->name); ?></p>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="outtaherebtn">
                        <img src = "../media/exit.jpg" alt = "Выход">
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="qrscanner">
        <h2 id="qr-result">Сканирование QR-кода</h2>
        <div id="qr-link" style="display: none; margin-top: 20px;">
            <a id="edit-link" href="#" target="_blank">Изменить результаты</a>
        </div>
            <h4 id="orpicker" style="display: none;">или</h4>
        <div class="scannerbutton">
            <button id="start-scan-btn" class="btn">Начать сканирование</button>
        </div>
        <video id="qr-video" playsinline webkit-playsinline controlsList="nofullscreen" autoplay></video>
        <canvas id="qr-canvas"></canvas>

        <!-- Элемент для отображения ссылки -->
       


    </div>

    <script>
        const video = document.getElementById("qr-video");
        const canvas = document.getElementById("qr-canvas");
        const context = canvas.getContext("2d");
        const resultText = document.getElementById("qr-result");
        const startScanBtn = document.getElementById("start-scan-btn");
        const qrLink = document.getElementById("qr-link");
        const editLink = document.getElementById("edit-link");

        let isScanning = false;

        // Функция для запуска сканирования
        async function startScanner() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
                video.srcObject = stream;
                isScanning = true;
                requestAnimationFrame(scanQR);
            } catch (error) {
                console.error("Ошибка доступа к камере:", error);
                resultText.textContent = "Не удалось получить доступ к камере.";
            }
        }

        // Функция для сканирования QR-кода
        function scanQR() {
            if (isScanning && video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const qrCode = jsQR(imageData.data, imageData.width, imageData.height);

                const orPicker = document.getElementById('orpicker')
                
                if (qrCode) {
                    resultText.textContent = "Найдено: " + qrCode.data;

                    // Отправка данных на сервер для получения информации о команде
                    fetch('/handle-qr-scan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ team_name: qrCode.data }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Ответ сервера:", data); // Логируем ответ

                        if (data.success && data.redirect_url) {
                            console.log("Редирект URL:", data.redirect_url);

                            // Обновляем ссылку
                            editLink.href = data.redirect_url;
                            qrLink.style.display = "block"; // Показываем ссылку
                            qrLink.style.opacity = "1"; // Плавное появление
                            orPicker.style.display = 'block';
                        } else {
                            resultText.textContent = "Ошибка: " + (data.message || "Некорректные данные от сервера");
                        }
                    })
                    .catch(error => {
                        console.error("Ошибка при отправке данных:", error);
                        resultText.textContent = "Ошибка при обработке QR-кода.";
                    });

                    // Останавливаем сканирование после успешного распознавания
                    isScanning = false;
                    video.srcObject.getTracks().forEach(track => track.stop());
                    startScanBtn.textContent = "Начать сканирование";
                }
            }
            if (isScanning) {
                requestAnimationFrame(scanQR);
            }
        }

        // Обработчик нажатия на кнопку "Начать сканирование"
        startScanBtn.addEventListener('click', () => {
            if (!isScanning) {
                startScanner();
                startScanBtn.textContent = "Остановить сканирование";
                qrLink.style.display = "none"; // Скрываем ссылку при новом сканировании
                qrLink.style.opacity = "0"; // Скрываем плавно
            } else {
                isScanning = false;
                video.srcObject.getTracks().forEach(track => track.stop());
                startScanBtn.textContent = "Начать сканирование";
                resultText.textContent = "Сканирование остановлено.";
            }
        });

    </script>
</body>
</html><?php /**PATH C:\OSPanel\domains\eventserver3\resources\views/judge/dashboard.blade.php ENDPATH**/ ?>