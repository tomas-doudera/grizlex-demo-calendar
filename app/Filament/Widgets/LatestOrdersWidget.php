<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrdersWidget extends TableWidget
{
    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    public static function getHeading(): ?string
    {
        return __('filament/widgets.tables.latest_orders');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Order::query()->with('customer')->latest()->limit(5))
            ->columns([
                TextColumn::make('order_number')
                    ->label(__('filament/widgets.tables.order_number'))
                    ->weight('medium')
                    ->searchable(),
                TextColumn::make('customer.first_name')
                    ->label(__('filament/widgets.tables.customer'))
                    ->state(fn (Order $record): string => $record->customer ? "{$record->customer->first_name} {$record->customer->last_name}" : 'N/A'),
                TextColumn::make('status')
                    ->label(__('filament/widgets.tables.status'))
                    ->badge(),
                TextColumn::make('total')
                    ->label(__('filament/widgets.tables.total'))
                    ->money('USD'),
                IconColumn::make('is_paid')
                    ->boolean()
                    ->label(__('filament/widgets.tables.paid')),
                TextColumn::make('created_at')
                    ->label(__('filament/widgets.tables.created_at'))
                    ->since(),
            ])
            ->paginated(false);
    }
}
