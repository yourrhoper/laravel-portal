<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Регистрация</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <b>Регистрация</b>
  </div>

  <div class="card">
    <div class="card-body register-card-body">

      {{-- сообщение об успешной регистрации (flash-данные из контроллера) --}}
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      {{-- ошибки валидации: общий список --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="list-unstyled mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('register.store') }}" method="post">
        @csrf

        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                 placeholder="Имя" value="{{ old('name') }}">
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                 placeholder="Email" value="{{ old('email') }}">
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                 placeholder="Пароль">
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        {{-- второе поле пароля: имя password_confirmation обязательно, иначе правило confirmed не сработает --}}
        <div class="input-group mb-3">
          <input type="password" name="password_confirmation" class="form-control"
                 placeholder="Повторите пароль">
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>

<script src="{{ asset('assets/admin/js/admin.js') }}"></script>
</body>
</html>
