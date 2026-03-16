<?php

namespace App\Domain\Finance\Models;

use App\Domain\Finance\Database\Factories\RefundFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'amount',
        'reason',
        'refund_number',
        'refunded_at',
    ];

    protected static function newFactory(): RefundFactory
    {
        return RefundFactory::new();
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'refunded_at' => 'datetime',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
