<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company_name',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'date_of_birth',
        'notes',
        'is_active',
        'is_vip',
        'lifetime_value',
        'avatar_url',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_vip' => 'boolean',
            'date_of_birth' => 'date',
            'lifetime_value' => 'decimal:2',
        ];
    }

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
