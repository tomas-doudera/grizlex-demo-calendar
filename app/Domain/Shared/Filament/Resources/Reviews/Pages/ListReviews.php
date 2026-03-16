<?php

namespace App\Domain\Shared\Filament\Resources\Reviews\Pages;

use App\Domain\Shared\Filament\Resources\Reviews\ReviewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReviews extends ListRecords
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
