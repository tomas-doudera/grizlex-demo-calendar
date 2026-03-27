<?php

namespace App\Models;

use Database\Factories\PlaceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Place extends Model
{
    /** @use HasFactory<PlaceFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'is_active',
        'sort_order',
        'opening_hours',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'opening_hours' => 'array',
        ];
    }

    /**
     * @return array<string, array{min: int, max: int}>
     */
    public function openingHoursForCalendarWeek(): array
    {
        $hours = $this->opening_hours ?? [];
        $dayKeys = [
            'monday'    => 'Monday',
            'tuesday'   => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday'  => 'Thursday',
            'friday'    => 'Friday',
            'saturday'  => 'Saturday',
            'sunday'    => 'Sunday',
        ];

        $out = [];
        foreach ($dayKeys as $jsonKey => $calendarKey) {
            $val = $hours[$jsonKey] ?? [0, 0];
            $out[$calendarKey] = [
                'min' => (int) ($val[0] ?? 0),
                'max' => (int) ($val[1] ?? 0),
            ];
        }
        return $out;
    }

    /**
     * @param  iterable<int, Place>  $places
     * @return array<string, array{min: int, max: int}>
     */
    public static function mergeOpeningHoursForCalendarWeek(iterable $places): array
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $weeks = [];
        foreach ($places as $place) {
            $weeks[] = $place->openingHoursForCalendarWeek();
        }

        $merged = [];
        foreach ($days as $day) {
            $intervals = [];
            foreach ($weeks as $week) {
                $h = $week[$day] ?? ['min' => 0, 'max' => 0];
                if (($h['max'] ?? 0) > 0) {
                    $intervals[] = $h;
                }
            }

            if ($intervals === []) {
                $merged[$day] = ['min' => 0, 'max' => 0];
            } else {
                $merged[$day] = [
                    'min' => min(array_column($intervals, 'min')),
                    'max' => max(array_column($intervals, 'max')),
                ];
            }
        }
        return $merged;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class);
    }

    /**
     * @return HasManyThrough<Reservation, Venue>
     */
    public function reservations(): HasManyThrough
    {
        return $this->hasManyThrough(Reservation::class, Venue::class);
    }
}
