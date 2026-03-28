<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'postal_code',
        'country',
        'logo_url',
        'is_active',
        'timezone',
        'currency',
        'opening_time',
        'closing_time',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
    protected static function booted(): void
    {
        static::saved(function (Company $company) {
            if (! $company->is_active) {
                $company->places()->update(['places.is_active' => false]);
                $company->venues()->update(['venues.is_active' => false]);
            }
        });
    }


    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }

    /**
     * @return HasManyThrough<Venue, Place>
     */
    public function venues(): HasManyThrough
    {
        return $this->hasManyThrough(Venue::class, Place::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
