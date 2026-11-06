<?php

namespace App\Enums;

enum Reliability: string
{
    case Document = 'document';     // подтверждено документом
    case Party = 'party';           // заявлено стороной
    case Media = 'media';           // сообщено СМИ
    case Distortion = 'distortion'; // искажение
    case Refuted = 'refuted';       // опровергнуто

    public function label(): string
    {
        return match ($this) {
            self::Document => 'Подтверждено документом',
            self::Party => 'Заявлено стороной',
            self::Media => 'Сообщено СМИ',
            self::Distortion => 'Искажение',
            self::Refuted => 'Опровергнуто',
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::Document => '🟢',
            self::Party => '🔵',
            self::Media => '🟡',
            self::Distortion => '🟠',
            self::Refuted => '🔴',
        };
    }

    // цвет плашки
    public function color(): string
    {
        return match ($this) {
            self::Document => '#28a745',
            self::Party => '#007bff',
            self::Media => '#ffc107',
            self::Distortion => '#fd7e14',
            self::Refuted => '#dc3545',
        };
    }

    // цвет текста на плашке (на жёлтом белый нечитаем)
    public function textColor(): string
    {
        return $this === self::Media ? '#212529' : '#ffffff';
    }
}
