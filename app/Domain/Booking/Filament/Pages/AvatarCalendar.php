<?php

namespace App\Domain\Booking\Filament\Pages;

use App\Domain\Booking\Filament\Widgets\AvatarCalendarWidget;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class AvatarCalendar extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.avatar-calendar';

    public static function getNavigationLabel(): string
    {
        return __('filament/navigation.pages.avatar_calendar');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.calendars');
    }

    protected function getFooterWidgets(): array
    {
        return [
            AvatarCalendarWidget::class,
        ];
    }
}
