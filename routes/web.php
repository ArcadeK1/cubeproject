<?php

use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\ViewerController;
use Illuminate\Support\Facades\Route;

// Главная страница
Route::get('/', function () {
    return view('index');
})->name('index');

// Авторизация
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Админ панель
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('competitions', CompetitionController::class);
    Route::resource('challenges', ChallengeController::class);

    //Информация и редактирование мероприятий
    Route::get('/competitions/{id}/edit', [CompetitionController::class, 'edit'])->name('competitions.edit');
    Route::put('/competitions/{id}', [CompetitionController::class, 'update'])->name('competitions.update');
    Route::get('/info/{competitionID}', [CompetitionController::class, 'info'])->name('competitions.info');

    //Создание, хранение и удаление мероприятий
    Route::get('/competitions/create', [CompetitionController::class, 'create'])->name('competitions.create');
    Route::post('/competitions', [CompetitionController::class, 'store'])->name('competitions.store');
    Route::delete('/competitions/{competition}', [CompetitionController::class, 'destroy'])->name('competitions.destroy');

    //Отчёт в формате pdf
    Route::get('/competitions/{competitionID}/report', [CompetitionController::class, 'generateReport'])->name('competitions.report');

    //Создание, хранение и удаление испытаний
    Route::get('/challenges/create', [ChallengeController::class, 'create'])->name('challenges.create');
    Route::post('/challenges', [ChallengeController::class, 'store'])->name('challenges.store');
    Route::delete('/challenges/{challenge}', [ChallengeController::class, 'destroy'])->name('challenges.destroy')->middleware('auth');
    
    

    // Создание, хранение и удаление команд
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy')->middleware('auth');


    //Создание, хранение и удаление судей
    Route::get('/judges/create', [JudgeController::class, 'create'])->name('judges.create');
    Route::post('/judges', [JudgeController::class, 'store'])->name('judges.store');
    Route::delete('/judges/{judge}', [JudgeController::class, 'destroy'])->name('judges.destroy');




    


    //Обновление очков для команды
    Route::put('/competitions/{competition}/scores/{team}', [ScoreController::class, 'update'])->name('scores.update');
});

// Судья
Route::middleware(['auth', 'role:judge'])->group(function () {
    Route::get('/judge/dashboard', [JudgeController::class, 'dashboard'])->name('judge.dashboard');


    //Обработка QR
    Route::post('/handle-qr-scan', [JudgeController::class, 'handleQRScan'])->name('handle.qr.scan');


    //Информация о команде и очках
    Route::get('/competitions/{competition}/show/{team}', [CompetitionController::class, 'show'])->name('competitions.show');
    Route::put('/competitions/{competition}/scores/{team}', [ScoreController::class, 'update'])->name('scores.update');

});

// Команда
Route::middleware(['auth', 'role:team'])->group(function () {

    //Кабинет команды
    Route::get('/team/dashboard', [TeamController::class, 'dashboard'])->name('team.dashboard');

    //Обновление очков
    Route::get('/refresh-scores', [TeamController::class, 'refreshScores']);

});



//Зрительский зал (Войти как зритель)
Route::get('/viewer', [ViewerController::class, 'index']);
Route::get('/viewer/competitions', [ViewerController::class, 'getCompetitionsByDate']);
Route::get('/viewer/data', [ViewerController::class, 'getCompetitionData']);