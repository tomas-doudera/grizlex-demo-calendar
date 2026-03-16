<?php

namespace App\Domain\Shared\Filament\Resources\Customers\Pages;

use App\Domain\Shared\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
