<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?int $sort = -2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    public function getHeading(): ?string
    {
        return __('filament/widgets.charts.monthly_revenue');
    }

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(function (int $monthsAgo) {
            $date = Carbon::now()->subMonths($monthsAgo);

            return [
                'label' => $date->format('M Y'),
                'revenue' => Order::query()
                    ->where('is_paid', true)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total'),
                'orders' => Order::query()
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => __('filament/widgets.charts.revenue'),
                    'data' => $months->pluck('revenue')->map(fn ($v) => round((float) $v, 2))->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => __('filament/widgets.charts.orders'),
                    'data' => $months->pluck('orders')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $months->pluck('label')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
