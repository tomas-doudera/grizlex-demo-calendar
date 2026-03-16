<?php

namespace App\Domain\IndividualBooking\Models;

use App\Domain\IndividualBooking\Database\Factories\StaffBreakFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffBreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'date',
        'day_of_week',
        'start_time',
        'end_time',
        'reason',
    ];

    protected static function newFactory(): StaffBreakFactory
    {
        return StaffBreakFactory::new();
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'day_of_week' => 'integer',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Whether this break applies on a specific date (one-off) vs recurring weekly.
     */
    public function isOneOff(): bool
    {
        return $this->date !== null;
    }

    public function isRecurring(): bool
    {
        return $this->day_of_week !== null && $this->date === null;
    }
}
