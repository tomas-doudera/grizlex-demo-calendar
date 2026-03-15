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
        return __('filament/enums.staff_role.'.$this->value);
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
