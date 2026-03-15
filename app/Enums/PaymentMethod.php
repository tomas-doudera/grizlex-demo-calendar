<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    case Cash = 'cash';
    case CreditCard = 'credit_card';
    case BankTransfer = 'bank_transfer';
    case Online = 'online';

    public function getLabel(): string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::CreditCard => 'Credit Card',
            self::BankTransfer => 'Bank Transfer',
            self::Online => 'Online',
        };
    }
}
