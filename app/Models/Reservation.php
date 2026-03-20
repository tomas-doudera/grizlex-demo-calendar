<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'place_id',
        'user_id',
        'staff_id',
        'from_time',
        'to_time',
        'capacity',
        'booked_count',
        'status',
        'notes',
        'guest_name',
        'guest_email',
        'guest_phone',
        'confirmed_at',
        'cancelled_at',
        'cancellation_reason',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'from_time' => 'immutable_datetime',
            'to_time' => 'immutable_datetime',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
            'status' => ReservationStatus::class,
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function getGuestNameAttribute(): ?string
    {
        if ($this->staff) {
            return $this->staff->first_name . ' ' . $this->staff->last_name;
        }
        return $this->attributes['guest_name'] ?? null;
    }

    public function getColorAttribute(): ?string
    {
        return $this->place?->color;
    }

    public function getStyleAttribute(): ?string
    {
        $color = $this->color ?? '#3b82f6';

        return "--cal-event-color: {$color}";
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->place?->image_url;
    }
}
