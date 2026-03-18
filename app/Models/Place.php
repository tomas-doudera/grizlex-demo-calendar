<?php

namespace App\Models;

use Database\Factories\PlaceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    /** @use HasFactory<PlaceFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'short_title',
        'description',
        'type',
        'capacity',
        'color',
        'image_url',
        'is_active',
        'sort_order',
        'min_booking_minutes',
        'max_booking_minutes',
        'booking_interval_minutes',
        'advance_booking_days',
        'cancellation_hours',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'capacity' => 'integer',
            'sort_order' => 'integer',
            'min_booking_minutes' => 'integer',
            'max_booking_minutes' => 'integer',
            'booking_interval_minutes' => 'integer',
            'advance_booking_days' => 'integer',
            'cancellation_hours' => 'integer',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
