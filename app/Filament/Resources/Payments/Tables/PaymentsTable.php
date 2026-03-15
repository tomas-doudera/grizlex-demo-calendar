<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payment_number')
                    ->label(__('filament/payments.fields.payment_number'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->copyable(),
                TextColumn::make('customer.first_name')
                    ->label(__('filament/payments.columns.customer'))
                    ->state(fn ($record): string => $record->customer ? "{$record->customer->first_name} {$record->customer->last_name}" : 'N/A')
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('filament/payments.fields.amount'))
                    ->money('USD')
                    ->sortable()
                    ->summarize([
                        Sum::make()
                            ->money('USD'),
                    ]),
                TextColumn::make('status')
                    ->label(__('filament/payments.fields.status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('method')
                    ->label(__('filament/payments.fields.method'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('paid_at')
                    ->label(__('filament/payments.fields.paid_at'))
                    ->dateTime()
                    ->sortable()
                    ->placeholder(__('filament/payments.columns.not_paid'))
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/payments.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament/payments.filters.status'))
                    ->options(PaymentStatus::class)
                    ->multiple(),
                SelectFilter::make('method')
                    ->label(__('filament/payments.filters.method'))
                    ->options(PaymentMethod::class),
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
