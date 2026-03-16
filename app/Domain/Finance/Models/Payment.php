<?php

namespace App\Domain\Finance\Models;

use App\Domain\Booking\Models\Reservation;
use App\Domain\Shared\Models\Customer;
use App\Domain\Finance\Enums\PaymentMethod;
use App\Domain\Finance\Enums\PaymentStatus;
use App\Domain\Finance\Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    protected static function newFactory(): PaymentFactory
    {
        return PaymentFactory::new();
    }

    protected $fillable = [
        'reservation_id', 'customer_id', 'payment_number', 'amount',
        'status', 'method', 'transaction_id', 'notes', 'stripe_session_id', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'status' => PaymentStatus::class,
            'method' => PaymentMethod::class,
            'paid_at' => 'datetime',
        ];
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }
}
