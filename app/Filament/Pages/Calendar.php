<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CalendarWidget;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Calendar extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static ?string $navigationLabel = 'Base Calendar';

    protected static string|\UnitEnum|null $navigationGroup = 'Calendars';

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.pages.calendar';

    protected function getFooterWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }
}
