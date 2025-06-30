<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //Форма логина
    public function showLoginForm()
    {
        return view('auth.login');
    }


    //Вход
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role==='admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role==='judge') {
                return redirect()->route('judge.dashboard');
            } elseif ($user->role==='team') {
                return redirect()->route('team.dashboard');
            }
        }

        return back()->withErrors(['login' => 'Неверные учетные данные']);
    }

    //Выход
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}

