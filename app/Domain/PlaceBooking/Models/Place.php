<?php

namespace App\Domain\PlaceBooking\Models;

use App\Domain\Booking\Models\Reservation;
use App\Domain\PlaceBooking\Database\Factories\PlaceFactory;
use App\Domain\PlaceBooking\Services\PlaceAvailabilityService;
use App\Domain\Shared\Concerns\BelongsToCompany;
use App\Domain\Shared\Contracts\HasAvailability;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model implements HasAvailability
{
    use BelongsToCompany;

    /** @use HasFactory<PlaceFactory> */
    use HasFactory;

    protected static function newFactory(): PlaceFactory
    {
        return PlaceFactory::new();
    }

    protected $fillable = [
        'company_id', 'title', 'short_title', 'description', 'type',
        'capacity', 'hourly_rate', 'color', 'is_active', 'amenities', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'hourly_rate' => 'decimal:2',
            'amenities' => 'array',
        ];
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(PlaceSchedule::class);
    }

    public function isAvailableAt(CarbonImmutable $dateTime, int $durationMinutes): bool
    {
        return app(PlaceAvailabilityService::class)->isAvailable($this, $dateTime, $durationMinutes);
    }
}
