<?php

namespace App\Domain\Shared\Models;

use App\Domain\Shared\Concerns\BelongsToCompany;
use App\Domain\Shared\Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use BelongsToCompany;

    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'description', 'duration_minutes',
        'price', 'color', 'is_active', 'requires_payment', 'max_participants', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'requires_payment' => 'boolean',
        ];
    }

    protected static function newFactory(): ServiceFactory
    {
        return ServiceFactory::new();
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(\App\Domain\Booking\Models\Reservation::class);
    }
}
