<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller

{
    public function index()
    {
        $posts = Post::with(['category', 'tags'])->paginate(20); // with(): загрузка связей
       // dd($categories);
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
    $request->validate([
        'title' => 'required',
        'description' => 'required',
        'content' => 'required',
        'category_id' => 'required|integer',
        'thumbnail' => 'nullable|image',
        'published_at' => 'nullable|date',
    ]);

    $data = $request->except(['_token', 'tags']); // в видео: $request->all()

    if ($request->hasFile('thumbnail')) {
        $folder = date('Y-m-d');
        $data['thumbnail'] = $request->file('thumbnail')->store("images/{$folder}");
    }
    $data['user_id'] = auth()->id(); // пока нет входа, будет null

    $post = Post::create($data);
    $post->tags()->sync($request->input('tags', []));

    return redirect()->route('posts.index')->with('success', 'Пост добавлен');
}


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::pluck('title', 'id')->all();
        $tags = Tag::pluck('title', 'id')->all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'content' => 'required',
            'category_id' => 'required|integer',
            'thumbnail' => 'nullable|image',
            'published_at' => 'nullable|date',
        ]);

        $post = Post::findOrFail($id);
        $data = $request->except(['_token', '_method', 'tags']);

        if ($request->hasFile('thumbnail')) {
            $folder = date('Y-m-d');
            $data['thumbnail'] = $request->file('thumbnail')->store("images/{$folder}");
        }

        $post->update($data);
        $post->tags()->sync($request->input('tags', []));

        return redirect()->route('posts.index')->with('success', 'Изменения сохранены');
    }

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->tags()->sync([]); // отвязать теги
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Пост удален');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        $path = $request->file('image')->store('posts/content'); // диск по умолчанию public

        return response()->json(['url' => asset('uploads/' . $path)]); //было asset('storage/...')
    }
}