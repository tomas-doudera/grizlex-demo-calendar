<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StaffCalendarWidget;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class StaffCalendar extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Staff Calendar';

    protected static string|\UnitEnum|null $navigationGroup = 'Calendars';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.staff-calendar';

    protected function getFooterWidgets(): array
    {
        return [
            StaffCalendarWidget::class,
        ];
    }
}
