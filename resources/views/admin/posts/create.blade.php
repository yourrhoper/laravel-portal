@extends('admin.layouts.layout')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">

            <div class="col-sm-6">
                <h1>Создание поста</h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">
                            Главная
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.index') }}">
                            Категории
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Создание категории
                    </li>

                </ol>
            </div>

        </div>
    </div>
</section>


<!-- Main content -->
<section class="content">

    <div class="container-fluid">

            <!-- left column -->
            <div class="col-md-6">

                <!-- general form elements -->
                <div class="card card-primary">

                    <div class="card-header">
                        <h3 class="card-title">
                            Новая категория
                        </h3>
                    </div>

                    <!-- form start -->
                    <form method="POST" action="{{ route('categories.store') }}">

                        @csrf

                        <div class="card-body">

                            <div class="form-group">

                                <label for="title">
                                    Название
                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}"
                                    placeholder="Введите название"
                                >

                                @error('title')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">

                            <button type="submit" class="btn btn-primary">
                                Сохранить
                           </button>

                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                Отмена
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
</section>

@endsection