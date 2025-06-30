<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// На расширение функционала следующему, кто будет проходить практику или делать диплом для Куба!

// Добавляем API-маршрут для получения данных команды и соревнования
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/team/dashboard', [TeamController::class, 'apiDashboard']);
});



