@extends('admin.layouts.layout')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Категории</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">Главная</a>
                    </li>
                    <li class="breadcrumb-item active">Категории</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Список категорий</h3>
            <div class="card-tools">
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Добавить категорию
                </a>
            </div>
        </div>

        @if (count($categories))
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Название</th>
                                <th>Slug</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->title }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                           class="btn btn-info btn-sm"
                                           title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}"
                                              method="POST"
                                              style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Подтвердите удаление')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card-body">
                <p class="text-center">Категории отсутствуют</p>
            </div>
        @endif
    </div>
</section>

@endsection