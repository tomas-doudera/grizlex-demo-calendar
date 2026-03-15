<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id', 'place_id', 'customer_id', 'service_id', 'staff_id',
        'from_time', 'to_time', 'capacity', 'booked_count', 'status',
        'total_price', 'notes', 'guest_name', 'guest_email', 'guest_phone',
    ];

    protected function casts(): array
    {
        return [
            'from_time' => 'datetime',
            'to_time' => 'datetime',
            'status' => ReservationStatus::class,
            'total_price' => 'decimal:2',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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
