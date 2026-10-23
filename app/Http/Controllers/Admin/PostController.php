<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller

{
    public function index()
    {
        $posts = Post::paginate(20);
       // dd($categories);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view ('admin.posts.create');
    }

public function store(StoreCategory $request)
{
    $request->validate([
        'title' => 'required',
    ]);

    return redirect()->route('posts.index')->with('success', 'Пост добавлен');
}
    

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        return view('admin.posts.edit');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required'
        ]);

        return redirect()->route('posts.index')->with('success', 'Изменения сохранены');
    }

    public function destroy(string $id)
    {
        //        $category = Category::find($id);
        //        $category->delete();
        return redirect()->route('posts.index')->with('success', 'Пост удален');
    }
}