<?php

namespace App\Domain\Shared\Filament\Resources\Services\Pages;

use App\Domain\Shared\Filament\Resources\Services\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;
}
