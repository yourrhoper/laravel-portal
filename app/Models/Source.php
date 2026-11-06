<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    // типы источников: ключ хранится в БД, значение показывается пользователю
    public const TYPES = [
        'document' => 'Документ (суд, официальный)',
        'party'    => 'Заявление стороны',
        'media'    => 'СМИ',
        'social'   => 'Соцсети / Telegram',
        'other'    => 'Другое',
    ];

    protected $fillable = ['type', 'title', 'url', 'quote', 'file'];

    // источник -> один пост
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    // публичный адрес загруженного файла (или null, если файла нет)
    public function fileUrl(): ?string
    {
        return $this->file ? asset("uploads/{$this->file}") : null;
    }

    // файл — картинка (иначе, например, pdf)
    public function isImage(): bool
    {
        return $this->file && preg_match('/\.(jpe?g|png|webp|gif)$/i', $this->file);
    }
}
