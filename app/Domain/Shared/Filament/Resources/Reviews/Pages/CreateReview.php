<?php

namespace App\Domain\Shared\Filament\Resources\Reviews\Pages;

use App\Domain\Shared\Filament\Resources\Reviews\ReviewResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReview extends CreateRecord
{
    protected static string $resource = ReviewResource::class;
}
