@extends('admin.layouts.layout')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Посты</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">Главная</a>
                    </li>
                    <li class="breadcrumb-item active">Посты</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Список постов</h3>
            <div class="card-tools">
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Добавить пост
                </a>
            </div>
        </div>

        @if (count($posts))
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Название</th>
                                <th>Краткое описание</th> {{-- <- новый столбец --}}
                                <th>Категория</th>
                                <th>Теги</th>
                                <th>Просмотры</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td style="min-width: 220px; white-space: normal;">
                                        {{ \Illuminate\Support\Str::limit($post->description, 80) }} {{-- <- первые 80 символов --}}
                                    </td>
                                    <td>{{ $post->category?->title }}</td>
                                    <td>{{ $post->tags->pluck('title')->join(', ') }}</td>
                                    <td>{{ $post->views }}</td>
                                    <td>
                                        @if (!$post->published_at)
                                            <span class="badge badge-secondary">Черновик</span>
                                        @elseif ($post->published_at->isFuture())
                                            <span class="badge badge-warning">
                                                Запланирован на {{ $post->published_at->format('d.m.Y H:i') }}
                                            </span>
                                        @else
                                            <span class="badge badge-success">
                                                Опубликован {{ $post->published_at->format('d.m.Y') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('posts.edit', ['post' => $post->id]) }}"
                                           class="btn btn-info btn-sm float-left mr-1"
                                           title="Редактировать">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('posts.destroy', ['post' => $post->id]) }}"
                                              method="post" class="float-left">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Подтвердите удаление')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $posts->links() }}
            </div>
        @else
            <div class="card-body">
                <p class="text-center">Посты отсутствуют</p>
            </div>
        @endif
    </div>
</section>

@endsection