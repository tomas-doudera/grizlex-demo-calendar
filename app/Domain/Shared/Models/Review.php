<?php

namespace App\Domain\Shared\Models;

use App\Domain\Shared\Database\Factories\ReviewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    /** @use HasFactory<ReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'reservation_id', 'customer_id', 'rating', 'comment', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    protected static function newFactory(): ReviewFactory
    {
        return ReviewFactory::new();
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Booking\Models\Reservation::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
