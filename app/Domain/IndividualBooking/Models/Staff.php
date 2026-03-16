<?php

namespace App\Domain\IndividualBooking\Models;

use App\Domain\Booking\Models\Reservation;
use App\Domain\IndividualBooking\Database\Factories\StaffFactory;
use App\Domain\IndividualBooking\Enums\StaffRole;
use App\Domain\IndividualBooking\Services\StaffAvailabilityService;
use App\Domain\Shared\Concerns\BelongsToCompany;
use App\Domain\Shared\Contracts\HasAvailability;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model implements HasAvailability
{
    use BelongsToCompany;

    /** @use HasFactory<StaffFactory> */
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'company_id', 'first_name', 'last_name', 'email', 'phone',
        'role', 'specialization', 'bio', 'is_active', 'avatar_url', 'color',
    ];

    protected static function newFactory(): StaffFactory
    {
        return StaffFactory::new();
    }

    protected function casts(): array
    {
        return [
            'role' => StaffRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(StaffSchedule::class);
    }

    public function breaks(): HasMany
    {
        return $this->hasMany(StaffBreak::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isAvailableAt(CarbonImmutable $dateTime, int $durationMinutes): bool
    {
        return app(StaffAvailabilityService::class)->isAvailable($this, $dateTime, $durationMinutes);
    }
}
