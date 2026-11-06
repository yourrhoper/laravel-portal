{{-- Блок «Источники» в конце публичной страницы поста.
     Подключение: @include('partials.post-sources', ['post' => $post]) --}}
@if ($post->sources->isNotEmpty())
    <section class="post-sources mt-4 pt-3 border-top">
        <h4>Источники</h4>
        <ol class="pl-3">
            @foreach ($post->sources as $source)
                <li class="mb-3">
                    <strong>{{ $source->title }}</strong>
                    <span class="text-muted">({{ $source->typeLabel() }})</span>

                    @if ($source->url)
                        <div>
                            <a href="{{ $source->url }}" target="_blank" rel="noopener noreferrer">{{ $source->url }}</a>
                        </div>
                    @endif

                    @if ($source->quote)
                        <blockquote class="blockquote small mb-1">{{ $source->quote }}</blockquote>
                    @endif

                    @if ($source->fileUrl())
                        @if ($source->isImage())
                            <a href="{{ $source->fileUrl() }}" target="_blank">
                                <img src="{{ $source->fileUrl() }}" alt="" class="img-thumbnail" style="max-height: 160px;">
                            </a>
                        @else
                            <a href="{{ $source->fileUrl() }}" target="_blank">Открыть файл</a>
                        @endif
                    @endif
                </li>
            @endforeach
        </ol>
    </section>
@endif
