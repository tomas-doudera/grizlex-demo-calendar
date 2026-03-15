<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AvatarCalendarWidget;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class AvatarCalendar extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $navigationLabel = 'Avatar Calendar';

    protected static string|\UnitEnum|null $navigationGroup = 'Reservations';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.avatar-calendar';

    protected function getFooterWidgets(): array
    {
        return [
            AvatarCalendarWidget::class,
        ];
    }
}
