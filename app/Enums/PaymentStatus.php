<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case Paid = 'paid';
    case PartiallyPaid = 'partially_paid';
    case Refunded = 'refunded';
    case Failed = 'failed';

    public function getLabel(): string
    {
        return __('filament/enums.payment_status.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Paid => 'success',
            self::PartiallyPaid => 'info',
            self::Refunded => 'gray',
            self::Failed => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Paid => 'heroicon-o-check-circle',
            self::PartiallyPaid => 'heroicon-o-minus-circle',
            self::Refunded => 'heroicon-o-arrow-uturn-left',
            self::Failed => 'heroicon-o-x-circle',
        };
    }
}
