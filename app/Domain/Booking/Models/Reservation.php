<?php

namespace App\Domain\Booking\Models;

use App\Domain\Booking\Database\Factories\ReservationFactory;
use App\Domain\Booking\Enums\ReservationStatus;
use App\Domain\Finance\Models\Payment;
use App\Domain\IndividualBooking\Models\Staff;
use App\Domain\PlaceBooking\Models\Place;
use App\Domain\Shared\Concerns\BelongsToCompany;
use App\Domain\Shared\Models\Customer;
use App\Domain\Shared\Models\Review;
use App\Domain\Shared\Models\Service;
use App\Domain\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use BelongsToCompany;

    /** @use HasFactory<ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id', 'user_id', 'place_id', 'customer_id', 'service_id', 'staff_id',
        'from_time', 'to_time', 'capacity', 'booked_count', 'status',
        'total_price', 'notes', 'guest_name', 'guest_email', 'guest_phone',
        'type', 'reservable_type', 'reservable_id',
        'checked_in_at', 'cancelled_at', 'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'from_time' => 'immutable_datetime',
            'to_time' => 'immutable_datetime',
            'checked_in_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'status' => ReservationStatus::class,
            'total_price' => 'decimal:2',
        ];
    }

    protected static function newFactory(): ReservationFactory
    {
        return ReservationFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getTitleAttribute(): string
    {
        $parts = [];

        if ($this->relationLoaded('service') && $this->service) {
            $parts[] = $this->service->name;
        }

        if ($this->relationLoaded('staff') && $this->staff) {
            $parts[] = $this->staff->first_name;
        }

        if (empty($parts) && $this->guest_name) {
            $parts[] = $this->guest_name;
        }

        if (empty($parts)) {
            $parts[] = $this->status?->getLabel() ?? 'Available';
        }

        return implode(' · ', $parts);
    }

    public function getColorAttribute(): ?string
    {
        if ($this->relationLoaded('service') && $this->service?->color) {
            return $this->service->color;
        }

        if ($this->relationLoaded('place') && $this->place?->color) {
            return $this->place->color;
        }

        return null;
    }

    public function getStyleAttribute(): string
    {
        $color = $this->color ?? '#3b82f6';

        return "--cal-event-color: {$color}";
    }
}
