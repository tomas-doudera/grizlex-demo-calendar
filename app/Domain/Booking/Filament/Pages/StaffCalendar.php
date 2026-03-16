<?php

namespace App\Domain\Booking\Filament\Pages;

use App\Domain\Booking\Filament\Widgets\StaffCalendarWidget;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class StaffCalendar extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.staff-calendar';

    public static function getNavigationLabel(): string
    {
        return __('filament/navigation.pages.staff_calendar');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.calendars');
    }

    protected function getFooterWidgets(): array
    {
        return [
            StaffCalendarWidget::class,
        ];
    }
}
