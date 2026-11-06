<?php

namespace App\Enums;

enum PostType: string
{
    case Site = 'site';         // пост, написанный на сайте
    case Telegram = 'telegram'; // пост из Telegram-канала

    public function label(): string
    {
        return match ($this) {
            self::Site => 'Пост с сайта',
            self::Telegram => 'Пост из Telegram',
        };
    }
}
