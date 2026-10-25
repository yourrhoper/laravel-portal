<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sluggable;

    // поля, которые разрешено записывать через create() и update()
    protected $fillable = [
        'title', 'description', 'content',
        'category_id', 'user_id', 'thumbnail', 'published_at',
    ];

    // published_at становится датой (работают ->format(), ->isFuture())
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps(); // <- withTimestamps()
    }

    public function category() {
        return $this->belongsTo(Category::class);
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

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}