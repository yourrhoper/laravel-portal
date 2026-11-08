<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Вход</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b>Вход</b>
  </div>

  <div class="card">
    <div class="card-body login-card-body">

      {{-- ошибки валидации (пустое поле, неверный формат email) --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="list-unstyled mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- ошибка авторизации: ->with('error', ...) в контроллере --}}
      @if (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <form action="{{ route('login') }}" method="post">
        @csrf

        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Пароль">
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Войти</button>
          </div>
        </div>
      </form>

      <a href="{{ route('register.create') }}" class="text-center d-block mt-3">Регистрация</a>

    </div>
  </div>
</div>

<script src="{{ asset('assets/admin/js/admin.js') }}"></script>
</body>
</html>
