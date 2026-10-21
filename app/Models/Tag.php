<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Tag extends Model
{
    use Sluggable;

    protected $fillable = ['title']; // список полей, которые можно массово заполнять через Tag::create

    public function posts(){
        return $this->belongsToMany(Post::class); // описание связи
    }

    public function sluggable(): array{
        return ['slug' => ['source' => 'title']]; // возвращает slug по названию 
    }
}