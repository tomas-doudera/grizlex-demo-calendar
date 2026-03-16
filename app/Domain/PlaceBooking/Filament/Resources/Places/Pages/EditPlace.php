<?php

namespace App\Domain\PlaceBooking\Filament\Resources\Places\Pages;

use App\Domain\PlaceBooking\Filament\Resources\Places\PlaceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlace extends EditRecord
{
    protected static string $resource = PlaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
