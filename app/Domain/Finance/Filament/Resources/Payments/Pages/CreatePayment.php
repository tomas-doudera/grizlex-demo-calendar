<?php

namespace App\Domain\Finance\Filament\Resources\Payments\Pages;

use App\Domain\Finance\Filament\Resources\Payments\PaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
