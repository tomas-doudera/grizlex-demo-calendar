<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
