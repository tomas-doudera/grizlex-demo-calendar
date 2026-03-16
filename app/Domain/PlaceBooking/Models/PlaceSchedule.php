<?php

namespace App\Domain\PlaceBooking\Models;

use App\Domain\PlaceBooking\Database\Factories\PlaceScheduleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected static function newFactory(): PlaceScheduleFactory
    {
        return PlaceScheduleFactory::new();
    }

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
