<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competition;
use App\Models\User;

class AdminController extends Controller
{
    //Админ-панель
    public function dashboard()
    {
        $competitions = Competition::all(); // Получаем все события из базы данных

        $judges = User::where('role', 'judge')->get(); // Получаем всех судей из БД
        return view('admin.dashboard', compact('competitions', 'judges')); // Отправляем в представление
    }
}
