<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -3;

    protected function getStats(): array
    {
        $totalRevenue = Order::query()->where('is_paid', true)->sum('total');
        $orderCount = Order::query()->count();
        $customerCount = Customer::query()->count();
        $openTickets = Ticket::query()->whereIn('status', ['open', 'in_progress'])->count();

        return [
            Stat::make('Total Revenue', '$'.number_format($totalRevenue, 2))
                ->description('Lifetime revenue')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 3, 4, 5, 6, 3, 5, 8])
                ->color('success'),
            Stat::make('Total Orders', (string) $orderCount)
                ->description('All time')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([3, 5, 7, 6, 4, 8, 5, 7])
                ->color('info'),
            Stat::make('Customers', (string) $customerCount)
                ->description('Active customers')
                ->descriptionIcon('heroicon-m-users')
                ->chart([5, 3, 6, 4, 7, 8, 5, 6])
                ->color('primary'),
            Stat::make('Open Tickets', (string) $openTickets)
                ->description('Needs attention')
                ->descriptionIcon('heroicon-m-ticket')
                ->chart([8, 6, 4, 5, 7, 3, 5, 4])
                ->color($openTickets > 10 ? 'danger' : 'warning'),
        ];
    }
}
