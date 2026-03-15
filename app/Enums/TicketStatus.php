<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TicketStatus: string implements HasColor, HasIcon, HasLabel
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case WaitingOnCustomer = 'waiting_on_customer';
    case Resolved = 'resolved';
    case Closed = 'closed';

    public function getLabel(): string
    {
        return __('filament/enums.ticket_status.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Open => 'info',
            self::InProgress => 'warning',
            self::WaitingOnCustomer => 'gray',
            self::Resolved => 'success',
            self::Closed => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Open => 'heroicon-o-envelope-open',
            self::InProgress => 'heroicon-o-cog',
            self::WaitingOnCustomer => 'heroicon-o-clock',
            self::Resolved => 'heroicon-o-check-circle',
            self::Closed => 'heroicon-o-lock-closed',
        };
    }
}
