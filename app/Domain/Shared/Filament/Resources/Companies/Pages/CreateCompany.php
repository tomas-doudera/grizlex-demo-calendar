<?php

namespace App\Domain\Shared\Filament\Resources\Companies\Pages;

use App\Domain\Shared\Filament\Resources\Companies\CompanyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;
}
