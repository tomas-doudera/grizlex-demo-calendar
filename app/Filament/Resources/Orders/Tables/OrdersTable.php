<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label(__('filament/orders.fields.order_number'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->copyable(),
                TextColumn::make('customer.first_name')
                    ->label(__('filament/orders.columns.customer'))
                    ->state(fn ($record): string => $record->customer ? "{$record->customer->first_name} {$record->customer->last_name}" : 'N/A')
                    ->searchable(['customer.first_name', 'customer.last_name'])
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament/orders.fields.status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('total')
                    ->label(__('filament/orders.fields.total'))
                    ->money('USD')
                    ->sortable()
                    ->summarize([
                        Sum::make()
                            ->money('USD'),
                    ]),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label(__('filament/orders.columns.items'))
                    ->badge()
                    ->color('gray'),
                IconColumn::make('is_paid')
                    ->boolean()
                    ->label(__('filament/orders.columns.paid')),
                TextColumn::make('payment_method')
                    ->label(__('filament/orders.fields.payment_method'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/orders.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament/orders.filters.status'))
                    ->options(OrderStatus::class)
                    ->multiple(),
                TernaryFilter::make('is_paid')
                    ->label(__('filament/orders.columns.payment_status')),
                SelectFilter::make('payment_method')
                    ->label(__('filament/orders.filters.payment_method'))
                    ->options([
                        'credit_card' => __('filament/orders.payment_methods.credit_card'),
                        'paypal' => __('filament/orders.payment_methods.paypal'),
                        'bank_transfer' => __('filament/orders.payment_methods.bank_transfer'),
                        'stripe' => __('filament/orders.payment_methods.stripe'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
