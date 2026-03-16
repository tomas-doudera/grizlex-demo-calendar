<?php

namespace App\Domain\PlaceBooking\Filament\Resources\Places\Pages;

use App\Domain\PlaceBooking\Filament\Resources\Places\PlaceResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlace extends CreateRecord
{
    protected static string $resource = PlaceResource::class;
}
