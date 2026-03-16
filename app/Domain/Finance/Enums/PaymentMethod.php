<?php

namespace App\Domain\Finance\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    case Cash = 'cash';
    case CreditCard = 'credit_card';
    case BankTransfer = 'bank_transfer';
    case Online = 'online';
    case Stripe = 'stripe';

    public function getLabel(): string
    {
        return __('filament/enums.payment_method.'.$this->value);
    }
}
