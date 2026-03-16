<?php

namespace App\Domain\Booking\Filament\Pages;

use App\Domain\Booking\Filament\Widgets\CalendarWidget;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Calendar extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.pages.calendar';

    public static function getNavigationLabel(): string
    {
        return __('filament/navigation.pages.base_calendar');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.calendars');
    }

    protected function getFooterWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }
}
