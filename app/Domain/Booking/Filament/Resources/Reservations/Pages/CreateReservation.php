<?php

namespace App\Domain\Booking\Filament\Resources\Reservations\Pages;

use App\Domain\Booking\Filament\Resources\Reservations\ReservationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;
}
