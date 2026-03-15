<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersByStatusChart extends ChartWidget
{
    protected static ?int $sort = -1;

    protected ?string $maxHeight = '300px';

    public function getHeading(): ?string
    {
        return __('filament/widgets.charts.orders_by_status');
    }

    protected function getData(): array
    {
        $data = collect(OrderStatus::cases())->map(function (OrderStatus $status) {
            return [
                'label' => $status->getLabel(),
                'count' => Order::query()->where('status', $status->value)->count(),
                'color' => match ($status) {
                    OrderStatus::Pending => '#a1a1aa',
                    OrderStatus::Processing => '#38bdf8',
                    OrderStatus::Shipped => '#fbbf24',
                    OrderStatus::Delivered => '#34d399',
                    OrderStatus::Cancelled => '#fb7185',
                    OrderStatus::Refunded => '#d4d4d8',
                },
            ];
        });

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => $data->pluck('color')->toArray(),
                ],
            ],
            'labels' => $data->pluck('label')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
