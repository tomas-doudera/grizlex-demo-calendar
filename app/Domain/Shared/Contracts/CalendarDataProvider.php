<?php

namespace App\Domain\Shared\Contracts;

use App\Domain\Shared\Enums\ReservationType;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface CalendarDataProvider
{
    /** @return Collection<int, array{id: int, title: string, start: string, end: string, color: string}> */
    public function getEvents(Carbon $start, Carbon $end, array $filters = []): Collection;

    public function getEventColor(): string;

    public function getReservationType(): ReservationType;
}
