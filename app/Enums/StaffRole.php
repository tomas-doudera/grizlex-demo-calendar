<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StaffRole: string implements HasColor, HasIcon, HasLabel
{
    case Manager = 'manager';
    case Trainer = 'trainer';
    case Instructor = 'instructor';
    case Receptionist = 'receptionist';
    case Other = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Manager => __('Manager'),
            self::Trainer => __('Trainer'),
            self::Instructor => __('Instructor'),
            self::Receptionist => __('Receptionist'),
            self::Other => __('Other'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Manager => 'blue',
            self::Trainer => 'green',
            self::Instructor => 'purple',
            self::Receptionist => 'red',
            self::Other => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Manager => 'heroicon-o-briefcase',
            self::Trainer => 'heroicon-o-trophy',
            self::Instructor => 'heroicon-o-academic-cap',
            self::Receptionist => 'heroicon-o-phone',
            self::Other => 'heroicon-o-user',
        };
    }
}
