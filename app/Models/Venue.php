<?php

namespace App\Models;

use Database\Factories\VenueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    /** @use HasFactory<VenueFactory> */
    use HasFactory;

    protected $fillable = [
        'place_id',
        'title',
        'description',
        'type',
        'capacity',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'capacity' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function scopeWithActivePlace($query)
    {
        return $query
            ->where('venues.is_active', true)
            ->whereHas('place', fn ($q) => $q
                ->where('places.is_active', true)
                ->whereHas('company', fn ($c) => $c->where('companies.is_active', true)));
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
