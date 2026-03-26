<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CalendarSettings extends Settings
{
    public int $step_width = 30;

    public int $row_height = 75;

    public static function group(): string
    {
        return 'calendarSettings';
    }
}
