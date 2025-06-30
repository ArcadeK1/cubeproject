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
        try 
        {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
            video.srcObject = stream;
            isScanning = true;
            requestAnimationFrame(scanQR);
        } 
        catch (error) 
        {
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
