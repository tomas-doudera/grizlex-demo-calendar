<?php

namespace App\Domain\Finance\Filament\Widgets;

use App\Domain\Booking\Enums\ReservationStatus;
use App\Domain\Booking\Models\Reservation;
use App\Domain\Finance\Enums\PaymentStatus;
use App\Domain\Finance\Models\Payment;
use App\Domain\Shared\Models\Customer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -3;

    protected function getStats(): array
    {
        $totalRevenue = Payment::query()->where('status', PaymentStatus::Paid)->sum('amount');
        $reservationCount = Reservation::query()->count();
        $customerCount = Customer::query()->count();
        $upcomingCount = Reservation::query()
            ->where('status', ReservationStatus::Confirmed)
            ->where('from_time', '>=', now())
            ->where('from_time', '<=', now()->addDays(7))
            ->count();

        return [
            Stat::make(__('filament/widgets.stats.total_revenue'), '$'.number_format($totalRevenue, 2))
                ->description(__('filament/widgets.stats.lifetime_revenue'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 3, 4, 5, 6, 3, 5, 8])
                ->color('success'),
            Stat::make(__('filament/widgets.stats.total_reservations'), (string) $reservationCount)
                ->description(__('filament/widgets.stats.all_time'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->chart([3, 5, 7, 6, 4, 8, 5, 7])
                ->color('info'),
            Stat::make(__('filament/widgets.stats.customers'), (string) $customerCount)
                ->description(__('filament/widgets.stats.active_customers'))
                ->descriptionIcon('heroicon-m-users')
                ->chart([5, 3, 6, 4, 7, 8, 5, 6])
                ->color('primary'),
            Stat::make(__('filament/widgets.stats.upcoming_reservations'), (string) $upcomingCount)
                ->description(__('filament/widgets.stats.next_7_days'))
                ->descriptionIcon('heroicon-m-clock')
                ->chart([8, 6, 4, 5, 7, 3, 5, 4])
                ->color($upcomingCount > 20 ? 'success' : 'warning'),
        ];
    }
}
