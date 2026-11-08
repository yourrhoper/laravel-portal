<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // --- регистрация ---

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed', // поле password_confirmation должно совпадать
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        session()->flash('success', 'Регистрация пройдена');
        Auth::login($user); // сразу входим под новым пользователем

        return redirect()->route('home');
    }

    // --- вход ---

    public function loginForm()
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // attempt() ищет пользователя по email и сверяет пароль с хешем в базе
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate(); // новый id сессии после входа

            session()->flash('success', 'Вы вошли в систему');

            if (Auth::user()->is_admin) {
                return redirect()->route('admin.index');
            } else {
                return redirect()->route('home');
            }
        }

        return redirect()->back()->with('error', 'Неверный логин или пароль');
    }

    // --- выход ---

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.create');
    }
}