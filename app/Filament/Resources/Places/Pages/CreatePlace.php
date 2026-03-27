<?php

namespace App\Filament\Resources\Places\Pages;

use App\Filament\Resources\Places\PlaceResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlace extends CreateRecord
{
    protected static string $resource = PlaceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        foreach ($data['opening_hours'] as $day => $hours) {
            if (! is_array($hours) || count($hours) < 2) {
                continue;
            }

            [$from, $to] = array_map(intval(...), $hours);

            $data['opening_hours'][$day] = ($from === $to) ? [0, 0] : [$from, $to];
        }
        return $data;
    }
}
