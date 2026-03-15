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
        return __('filament/enums.payment_method.'.$this->value);
    }
}
