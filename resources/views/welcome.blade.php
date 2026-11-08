<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        html, body { background-color: #fff; color: #636b6f; font-family: 'Segoe UI', Arial, sans-serif; height: 100vh; margin: 0; }
        .full-height { height: 100vh; }
        .flex-center { align-items: center; display: flex; justify-content: center; }
        .position-ref { position: relative; }
        .top-right { position: absolute; right: 10px; top: 18px; }
        .content { text-align: center; }
        .title { font-size: 84px; }
        .links > a { color: #636b6f; padding: 0 25px; font-size: 13px; font-weight: 600; letter-spacing: .1rem; text-decoration: none; text-transform: uppercase; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px 16px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">

    {{-- ссылки в правом верхнем углу зависят от того, вошёл ли пользователь --}}
    <div class="top-right links">
        @auth
            @if (auth()->user()->is_admin)
                <a href="{{ route('admin.index') }}">Админка</a>
            @endif
            <a href="{{ route('logout') }}">Выйти</a>
        @else
            <a href="{{ route('login.create') }}">Войти</a>
            <a href="{{ route('register.create') }}">Регистрация</a>
        @endauth
    </div>

    <div class="content">
        {{-- сообщение из session()->flash('success', ...) --}}
        @if (session()->has('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="title">Laravel</div>
    </div>
</div>
</body>
</html>
