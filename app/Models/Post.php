<?php

namespace App\Models;

use App\Enums\PostType;
use App\Enums\Reliability;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use Sluggable;

    // поля, которые разрешено записывать через create() и update()
    protected $fillable = [
        'title', 'description', 'content',
        'category_id', 'user_id', 'thumbnail', 'published_at',
        'type', 'reliability', // <- тип поста и метка достоверности
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'type' => PostType::class,           // <- в коде это объект PostType, в БД строка
        'reliability' => Reliability::class, // <- то же для метки (может быть null)
    ];

    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    // <- у поста много источников
    public function sources() {
        return $this->hasMany(Source::class);
    }

        /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    // загрузка картинки: общий код для store() и update()
    // $remove — явный запрос на удаление текущей картинки без замены (чекбокс в форме)
    public static function uploadImage(Request $request, $image = null, bool $remove = false)
    {
        if ($request->hasFile('thumbnail')) {
            if ($image) {
                Storage::delete($image); // заменяем: старый файл удаляем
            }
            $folder = date('Y-m-d');
            return $request->file('thumbnail')->store("images/{$folder}");
        }

        if ($remove && $image) {
            Storage::delete($image); // <- явное удаление без замены
            return null;
        }

        return $image; // ни файла, ни флага удаления: оставляем как было
    }

    // адрес картинки для шаблонов
    public function getImage()
    {
        if (!$this->thumbnail) {
            return asset("no-image.png");
        }
        return asset("uploads/{$this->thumbnail}");
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
