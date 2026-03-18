<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ReservationStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('Pending'),
            self::Confirmed => __('Confirmed'),
            self::InProgress => __('In Progress'),
            self::Completed => __('Completed'),
            self::Cancelled => __('Cancelled'),
            self::NoShow => __('No Show'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Confirmed => 'success',
            self::InProgress => 'info',
            self::Completed => 'gray',
            self::Cancelled => 'danger',
            self::NoShow => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Confirmed => 'heroicon-o-check-circle',
            self::InProgress => 'heroicon-o-play',
            self::Completed => 'heroicon-o-check-badge',
            self::Cancelled => 'heroicon-o-x-circle',
            self::NoShow => 'heroicon-o-exclamation-triangle',
        };
    }
}
