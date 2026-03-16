<?php

namespace App\Domain\Shared\Filament\Resources\Customers\Pages;

use App\Domain\Shared\Filament\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
