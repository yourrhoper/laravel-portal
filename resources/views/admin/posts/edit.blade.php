@extends('admin.layouts.layout')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Редактирование поста</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">Главная</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('posts.index') }}">Посты</a>
                    </li>
                    <li class="breadcrumb-item active">Редактирование поста</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Пост «{{ $post->title }}»</h3>
                </div>

                <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        <div class="form-group">
                            <label for="title">Название</label>
                            <input type="text" name="title" id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $post->title) }}">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Краткое описание</label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $post->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Текст</label>
                            <textarea name="content" id="content" rows="10"
                                      class="form-control @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category_id">Категория</label>
                            <select name="category_id" id="category_id"
                                    class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $id => $title)
                                    <option value="{{ $id }}"
                                        @selected(old('category_id', $post->category_id) == $id)>
                                        {{ $title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tags">Теги</label>
                            <select name="tags[]" id="tags" multiple
                                    class="form-control @error('tags') is-invalid @enderror">
                                @foreach($tags as $id => $title)
                                    <option value="{{ $id }}"
                                        @selected(in_array($id, old('tags', $post->tags->pluck('id')->all())))>
                                        {{ $title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Несколько тегов: удерживайте Ctrl</small>
                            @error('tags')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="thumbnail">Изображение</label>
                            @if ($post->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $post->thumbnail) }}"
                                         alt="" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" name="thumbnail" id="thumbnail"
                                   class="form-control-file @error('thumbnail') is-invalid @enderror"
                                   accept="image/*">
                            <small class="text-muted">Оставьте пустым, чтобы сохранить текущее изображение</small>
                            @error('thumbnail')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="published_at">Дата публикации</label>
                            <input type="datetime-local" name="published_at" id="published_at"
                                   class="form-control @error('published_at') is-invalid @enderror"
                                   value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                            <small class="text-muted">
                                Пусто — черновик. Дата в будущем — отложенная публикация.
                            </small>
                            @error('published_at')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection