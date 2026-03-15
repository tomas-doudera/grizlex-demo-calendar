<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StaffRole: string implements HasColor, HasLabel
{
    case Manager = 'manager';
    case Instructor = 'instructor';
    case Receptionist = 'receptionist';
    case Trainer = 'trainer';
    case Maintenance = 'maintenance';

    public function getLabel(): string
    {
        return match ($this) {
            self::Manager => 'Manager',
            self::Instructor => 'Instructor',
            self::Receptionist => 'Receptionist',
            self::Trainer => 'Trainer',
            self::Maintenance => 'Maintenance',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Manager => 'danger',
            self::Instructor => 'info',
            self::Receptionist => 'success',
            self::Trainer => 'warning',
            self::Maintenance => 'gray',
        };
    }
}
