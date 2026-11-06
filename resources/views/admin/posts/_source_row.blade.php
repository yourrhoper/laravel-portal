{{-- Одна строка источника. $i — индекс строки, $row — значения (пустой массив для новой строки) --}}
<div class="source-row card card-outline card-secondary mb-3">
    <div class="card-header py-2">
        <h3 class="card-title">Источник №<span class="source-number"></span></h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool js-remove-source" title="Удалить источник">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div class="card-body pb-2">
        {{-- id нужен при редактировании: по нему находим уже сохранённый источник --}}
        <input type="hidden" name="sources[{{ $i }}][id]" value="{{ $row['id'] ?? '' }}">

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Тип источника</label>
                    <select name="sources[{{ $i }}][type]"
                            class="form-control @error('sources.' . $i . '.type') is-invalid @enderror">
                        @foreach(\App\Models\Source::TYPES as $key => $label)
                            <option value="{{ $key }}" @selected(($row['type'] ?? 'document') == $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('sources.' . $i . '.type')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                    <label>Название</label>
                    <input type="text" name="sources[{{ $i }}][title]"
                           class="form-control @error('sources.' . $i . '.title') is-invalid @enderror"
                           value="{{ $row['title'] ?? '' }}"
                           placeholder="Например: Решение суда от 01.01.2026">
                    @error('sources.' . $i . '.title')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Ссылка</label>
            <input type="text" name="sources[{{ $i }}][url]"
                   class="form-control @error('sources.' . $i . '.url') is-invalid @enderror"
                   value="{{ $row['url'] ?? '' }}"
                   placeholder="https://...">
            @error('sources.' . $i . '.url')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Цитата из источника</label>
            <textarea name="sources[{{ $i }}][quote]" rows="2"
                      class="form-control @error('sources.' . $i . '.quote') is-invalid @enderror"
                      placeholder="1–2 предложения">{{ $row['quote'] ?? '' }}</textarea>
            @error('sources.' . $i . '.quote')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Файл (скан, фото или PDF)</label>
            <input type="file" name="sources[{{ $i }}][file]"
                   class="form-control-file @error('sources.' . $i . '.file') is-invalid @enderror"
                   accept=".jpg,.jpeg,.png,.webp,.pdf">
            @if (!empty($row['file']))
                <small class="text-muted d-block mt-1">
                    Текущий файл:
                    <a href="{{ asset('uploads/' . $row['file']) }}" target="_blank">открыть</a>.
                    Выберите другой файл, чтобы заменить.
                </small>
            @endif
            @error('sources.' . $i . '.file')
                <span class="text-danger small d-block">{{ $message }}</span>
            @enderror
        </div>

        {{-- ошибка «нет подтверждения»: ни ссылки, ни цитаты, ни файла --}}
        @error('sources.' . $i . '.proof')
            <div class="text-danger small mb-2">{{ $message }}</div>
        @enderror
    </div>
</div>
