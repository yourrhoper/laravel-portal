<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::paginate(20);
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required',]); // проверка валидации

        Tag::create(['title' => $request->title,]); // создание тега по названи.

        return redirect()->route('tags.index')->with('success', 'Тэг добавлен'); // переадресация с сообщением
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $tag = Tag::find($id); // поиск нужного id в бд
        return view('admin.tags.edit', compact('tag')); // переход на страницу редактирования по tag
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['title' => 'required',]); // проверка валидации
        $tag = Tag::find($id); // поиск нужного id в бд
        $tag->update($request->all()); // вызов метода, берёт вообще всё из запроса, 
        //лишнее отфильтруется через $fillable

        return redirect()->route('tags.index')->with('success', 'Изменения сохранены'); // переадресация с сообщением
    }

    public function destroy(string $id)
    {
        Tag::destroy($id); // поиск нужного id в бд
        return redirect()->route('tags.index')->with('success', 'Тэг удалён'); // переадресация с сообщением
    }
}