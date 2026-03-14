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

    protected static ?string $heading = 'Latest Orders';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Order::query()->with('customer')->latest()->limit(5))
            ->columns([
                TextColumn::make('order_number')
                    ->weight('medium')
                    ->searchable(),
                TextColumn::make('customer.first_name')
                    ->label('Customer')
                    ->state(fn (Order $record): string => $record->customer ? "{$record->customer->first_name} {$record->customer->last_name}" : 'N/A'),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('total')
                    ->money('USD'),
                IconColumn::make('is_paid')
                    ->boolean()
                    ->label('Paid'),
                TextColumn::make('created_at')
                    ->since(),
            ])
            ->paginated(false);
    }
}
