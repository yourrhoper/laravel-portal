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
                        <a href="{{ route('admin.index') }}">Главная</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('posts.index') }}">Посты</a>
                    </li>
                    <li class="breadcrumb-item active">Создание поста</li>
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
                    <h3 class="card-title">Новый пост</h3>
                </div>
                <!-- /.card-header -->

                <form role="form" method="post" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">

                        <div class="form-group">
                            <label for="title">Название</label>
                            <input type="text" name="title" id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   placeholder="Введите название">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Краткое описание</label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Короткий анонс для списка">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Текст</label>
                            <textarea name="content" id="content" rows="10"
                                      class="form-control @error('content') is-invalid @enderror"
                                      placeholder="Полный текст поста">{{ old('content') }}</textarea>
                            @error('content')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category_id">Категория</label>
                            <select class="form-control @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id">
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $k => $v)
                                    <option value="{{ $k }}" @selected(old('category_id') == $k)>{{ $v }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tags">Теги</label>
                            <select name="tags[]" id="tags" class="select2" multiple="multiple"
                                    data-placeholder="Выбор тегов" style="width: 100%;">
                                @foreach($tags as $k => $v)
                                    <option value="{{ $k }}" @selected(in_array($k, old('tags', [])))>{{ $v }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                                <span class="text-danger small d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="thumbnail">Изображение</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="thumbnail" id="thumbnail"
                                           class="custom-file-input" accept="image/*">
                                    <label class="custom-file-label" for="thumbnail">Выберите файл</label>
                                </div>
                            </div>
                            @error('thumbnail')
                                <span class="text-danger small d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="published_at">Дата публикации</label>
                            <input type="datetime-local" name="published_at" id="published_at"
                                   class="form-control @error('published_at') is-invalid @enderror"
                                   value="{{ old('published_at') }}">
                            <small class="text-muted">
                                Пусто — черновик. Дата в будущем — отложенная публикация.
                            </small>
                            @error('published_at')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Отмена</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</section>
@include('admin.posts._editor')
@endsection