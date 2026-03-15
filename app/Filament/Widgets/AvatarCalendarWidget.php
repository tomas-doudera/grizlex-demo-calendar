<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\Storage;

class AvatarCalendarWidget extends CalendarWidget
{
    public bool $showStaffAvatarsInEvents = true;

    public function resolveStaffAvatarUrl(?string $avatarUrl): ?string
    {
        if (! $avatarUrl) {
            return null;
        }

        if (str_starts_with($avatarUrl, 'http://') || str_starts_with($avatarUrl, 'https://')) {
            return $avatarUrl;
        }

        return Storage::disk('public')->url($avatarUrl);
    }
}
