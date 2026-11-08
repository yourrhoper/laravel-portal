<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PostType;
use App\Enums\Reliability;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Source;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PostController extends Controller

{
    public function index()
    {
        // 10 постов на страницу; withCount('sources') считает источники для столбца таблицы
        $posts = Post::with(['category', 'tags'])->withCount('sources')->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::pluck('title', 'id')->all();
        $tags = Tag::pluck('title', 'id')->all();
        return view ('admin.posts.create', compact('categories', 'tags'));
    }

public function store(Request $request)
{
    $request->validate($this->rules(), $this->messages());
    $this->checkSources($request); // каждый источник должен быть чем-то подкреплён

    $data = $request->except(['_token', 'tags', 'sources']); // в видео: $request->all()
    $data['reliability'] = $request->input('reliability') ?: null; // пустая метка -> NULL
    $data['thumbnail'] = Post::uploadImage($request);
    $data['user_id'] = auth()->id(); // пока нет входа, будет null

    $post = Post::create($data);
    $post->tags()->sync($request->input('tags', []));
    $this->syncSources($post, $request);

    return redirect()->route('posts.index')->with('success', 'Пост добавлен');
}


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $post = Post::with('sources')->findOrFail($id);
        $categories = Category::pluck('title', 'id')->all();
        $tags = Tag::pluck('title', 'id')->all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, string $id)
    {
        $post = Post::with('sources')->findOrFail($id);

        $request->validate($this->rules(), $this->messages());
        $this->checkSources($request, $post);

        $data = $request->except(['_token', '_method', 'tags', 'sources', 'remove_thumbnail']); // <-
        $data['reliability'] = $request->input('reliability') ?: null;
        $data['thumbnail'] = Post::uploadImage(
            $request,
            $post->thumbnail,
            $request->boolean('remove_thumbnail') // <- чекбокс «Удалить изображение»
        );

        $post->update($data);
        $post->tags()->sync($request->input('tags', []));
        $this->syncSources($post, $request);

        return redirect()->route('posts.index')->with('success', 'Изменения сохранены');
    }

    public function destroy(string $id)
    {
        $post = Post::with('sources')->findOrFail($id);
        $post->tags()->sync([]); // отвязать теги

        foreach ($post->sources as $source) { // файлы источников (строки sources удалит сама БД)
            if ($source->file) {
                Storage::delete($source->file);
            }
        }
        if ($post->thumbnail) {
            Storage::delete($post->thumbnail);
        }
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Пост удален');
    }

    // загрузка картинок из редактора Summernote (не путать с Post::uploadImage)
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        $path = $request->file('image')->store('posts/content'); // диск по умолчанию public

        return response()->json(['url' => asset('uploads/' . $path)]);
    }

    // ---------- вспомогательные методы ----------

    // правила валидации
    private function rules(): array
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'content' => 'required',
            'category_id' => 'required|integer',
            'type' => ['required', Rule::enum(PostType::class)],
            'reliability' => ['nullable', Rule::enum(Reliability::class)], // метка необязательна
            'thumbnail' => 'nullable|image',
            'remove_thumbnail' => 'nullable|boolean', // <-
            'published_at' => 'nullable|date',

            // источники: минимум один
            'sources' => 'required|array|min:1',
            'sources.*.id' => 'nullable|integer',
            'sources.*.type' => ['required', Rule::in(array_keys(Source::TYPES))],
            'sources.*.title' => 'required|max:255',
            'sources.*.url' => 'nullable|url|max:500',
            'sources.*.quote' => 'nullable|max:500', // цитата короткая: 1-2 предложения
            'sources.*.file' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
        ];
    }

    private function messages(): array
    {
        return [
            'sources.required' => 'Добавьте хотя бы один источник: пост должен быть подкреплён.',
            'sources.min' => 'Добавьте хотя бы один источник: пост должен быть подкреплён.',
            'sources.*.type.required' => 'Выберите тип источника.',
            'sources.*.title.required' => 'Укажите название источника.',
            'sources.*.url.url' => 'Ссылка на источник указана некорректно.',
            'sources.*.quote.max' => 'Цитата не должна быть длиннее 500 символов.',
            'sources.*.file.mimes' => 'Файл источника должен быть jpg, png, webp или pdf.',
            'sources.*.file.max' => 'Файл источника не должен быть больше 10 МБ.',
        ];
    }

    // проверки, которые не выразить обычными правилами
    private function checkSources(Request $request, ?Post $post = null): void
    {
        $errors = [];
        $hasDocument = false;

        foreach ($request->input('sources', []) as $i => $row) {
            // источник, который уже есть в БД (при редактировании)
            $existing = (!empty($row['id']) && $post) ? $post->sources->firstWhere('id', $row['id']) : null;

            // подтверждение: ссылка, цитата или файл (любой вид)
            $hasProof = filled($row['url'] ?? null)
                || filled($row['quote'] ?? null)
                || $request->hasFile("sources.$i.file")
                || ($existing && $existing->file);

            if (!$hasProof) {
                $errors["sources.$i.proof"] = 'Добавьте подтверждение: ссылку, цитату или файл.';
            }
            if (($row['type'] ?? null) === 'document') {
                $hasDocument = true;
            }
        }

        // метки «подтверждено документом» и «опровергнуто» требуют источника-документа
        // (если правило не нужно, удалите этот блок)
        $label = $request->input('reliability');
        if (in_array($label, [Reliability::Document->value, Reliability::Refuted->value], true) && !$hasDocument) {
            $errors['reliability'] = 'Для этой метки нужен хотя бы один источник типа «Документ».';
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }
    }

    // привести источники поста в соответствие с формой: обновить, добавить, удалить лишние
    private function syncSources(Post $post, Request $request): void
    {
        $keepIds = [];

        foreach ($request->input('sources', []) as $i => $row) {
            $data = [
                'type' => $row['type'],
                'title' => $row['title'],
                'url' => $row['url'] ?? null,
                'quote' => $row['quote'] ?? null,
            ];

            // ищем только среди источников этого поста
            $source = !empty($row['id']) ? $post->sources()->find($row['id']) : null;

            if ($request->hasFile("sources.$i.file")) {
                if ($source && $source->file) {
                    Storage::delete($source->file); // заменяем файл: старый удаляем
                }
                $data['file'] = $request->file("sources.$i.file")->store('sources/' . date('Y-m-d'));
            }

            if ($source) {
                $source->update($data);
            } else {
                $source = $post->sources()->create($data);
            }
            $keepIds[] = $source->id;
        }

        foreach ($post->sources()->whereNotIn('id', $keepIds)->get() as $old) {
            if ($old->file) {
                Storage::delete($old->file);
            }
            $old->delete();
        }
    }
}
