<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ReservationStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case CheckedIn = 'checked_in';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';

    public function getLabel(): string
    {
        return __('filament/enums.reservation_status.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Confirmed => 'info',
            self::CheckedIn => 'success',
            self::Completed => 'gray',
            self::Cancelled => 'danger',
            self::NoShow => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Confirmed => 'heroicon-o-check',
            self::CheckedIn => 'heroicon-o-arrow-right-on-rectangle',
            self::Completed => 'heroicon-o-check-circle',
            self::Cancelled => 'heroicon-o-x-circle',
            self::NoShow => 'heroicon-o-exclamation-triangle',
        };
    }
}
