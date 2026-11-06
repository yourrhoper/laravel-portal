{{-- Блок источников: подключается в create и edit. В edit доступна переменная $post --}}
@php
    $rows = old('sources');

    if ($rows === null) {
        // первое открытие формы: берём источники из БД (в create их нет)
        $rows = isset($post)
            ? $post->sources->map(fn ($s) => [
                'id' => $s->id, 'type' => $s->type, 'title' => $s->title,
                'url' => $s->url, 'quote' => $s->quote, 'file' => $s->file,
            ])->all()
            : [];
    } elseif (isset($post)) {
        // форма вернулась с ошибкой: файлы в old() не сохраняются, подтягиваем путь из БД
        foreach ($rows as $k => $r) {
            $rows[$k]['file'] = !empty($r['id']) ? $post->sources->firstWhere('id', $r['id'])?->file : null;
        }
    }

    if (!$rows) {
        $rows = [[]]; // всегда показываем хотя бы одну пустую строку
    }
    $nextIndex = max(array_keys($rows)) + 1;
@endphp

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Источники</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">
            Пост должен быть подкреплён источником. Для каждого источника достаточно одного из трёх:
            ссылки, цитаты или файла (фото, скан, PDF).
        </p>

        @error('sources')
            <div class="text-danger small mb-2">{{ $message }}</div>
        @enderror

        <div id="sources-list">
            @foreach($rows as $i => $row)
                @include('admin.posts._source_row', ['i' => $i, 'row' => $row])
            @endforeach
        </div>

        <button type="button" class="btn btn-outline-primary btn-sm" id="add-source">
            <i class="fas fa-plus"></i> Добавить источник
        </button>
    </div>
</div>

{{-- шаблон новой строки: браузер его не отображает и не отправляет, JS копирует его по кнопке --}}
<template id="source-template">
    @include('admin.posts._source_row', ['i' => '__INDEX__', 'row' => []])
</template>

@push('scripts')
<script>
$(function () {
    let nextIndex = {{ $nextIndex }};

    // номера «Источник №1, №2…» пересчитываются при добавлении и удалении
    function renumber() {
        $('#sources-list .source-row').each(function (n) {
            $(this).find('.source-number').text(n + 1);
        });
    }

    $('#add-source').on('click', function () {
        const html = $('#source-template').html().replace(/__INDEX__/g, nextIndex++);
        $('#sources-list').append(html);
        renumber();
    });

    $('#sources-list').on('click', '.js-remove-source', function () {
        const $row = $(this).closest('.source-row');

        if ($('#sources-list .source-row').length === 1) {
            // последний источник не удаляем, а очищаем: пост без источника сохранить нельзя
            $row.find('input[type=text], textarea, input[type=file]').val('');
            return;
        }
        $row.remove();
        renumber();
    });

    renumber();
});
</script>
@endpush
