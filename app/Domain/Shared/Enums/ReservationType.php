<?php

namespace App\Domain\Shared\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ReservationType: string implements HasColor, HasIcon, HasLabel
{
    case Individual = 'individual';
    case Class_ = 'class';
    case Place = 'place';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Individual => __('filament/enums.reservation_type.individual'),
            self::Class_ => __('filament/enums.reservation_type.class'),
            self::Place => __('filament/enums.reservation_type.place'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Individual => 'info',
            self::Class_ => 'success',
            self::Place => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Individual => 'heroicon-o-user',
            self::Class_ => 'heroicon-o-user-group',
            self::Place => 'heroicon-o-building-office',
        };
    }
}
