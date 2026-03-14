<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Name')
                    ->state(fn ($record): string => "{$record->first_name} {$record->last_name}")
                    ->searchable(['first_name', 'last_name'])
                    ->sortable('first_name')
                    ->weight('medium'),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                TextColumn::make('company_name')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('No company'),
                TextColumn::make('country')
                    ->badge()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                IconColumn::make('is_vip')
                    ->boolean()
                    ->label('VIP'),
                TextColumn::make('lifetime_value')
                    ->money('USD')
                    ->sortable()
                    ->color(fn (string $state): string => ((float) $state) > 10000 ? 'success' : 'gray'),
                TextColumn::make('orders_count')
                    ->counts('orders')
                    ->label('Orders')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),
                TernaryFilter::make('is_vip')
                    ->label('VIP'),
                SelectFilter::make('country')
                    ->options([
                        'US' => 'United States',
                        'UK' => 'United Kingdom',
                        'DE' => 'Germany',
                        'FR' => 'France',
                        'CZ' => 'Czech Republic',
                        'CA' => 'Canada',
                        'AU' => 'Australia',
                    ])
                    ->multiple(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
