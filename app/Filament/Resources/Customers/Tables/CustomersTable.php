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
                    ->label(__('filament/customers.columns.name'))
                    ->state(fn ($record): string => "{$record->first_name} {$record->last_name}")
                    ->searchable(['first_name', 'last_name'])
                    ->sortable('first_name')
                    ->weight('medium'),
                TextColumn::make('email')
                    ->label(__('filament/customers.fields.email'))
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                TextColumn::make('company_name')
                    ->label(__('filament/customers.fields.company_name'))
                    ->searchable()
                    ->toggleable()
                    ->placeholder(__('filament/customers.columns.no_company')),
                TextColumn::make('country')
                    ->label(__('filament/customers.fields.country'))
                    ->badge()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('filament/customers.columns.active')),
                IconColumn::make('is_vip')
                    ->boolean()
                    ->label(__('filament/customers.columns.vip')),
                TextColumn::make('lifetime_value')
                    ->label(__('filament/customers.fields.lifetime_value'))
                    ->money('USD')
                    ->sortable()
                    ->color(fn (string $state): string => ((float) $state) > 10000 ? 'success' : 'gray'),
                TextColumn::make('reservations_count')
                    ->counts('reservations')
                    ->label(__('filament/customers.columns.reservations'))
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament/customers.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('filament/customers.columns.active')),
                TernaryFilter::make('is_vip')
                    ->label(__('filament/customers.columns.vip')),
                SelectFilter::make('country')
                    ->label(__('filament/customers.filters.country'))
                    ->options([
                        'US' => __('filament/customers.countries.us'),
                        'UK' => __('filament/customers.countries.uk'),
                        'DE' => __('filament/customers.countries.de'),
                        'FR' => __('filament/customers.countries.fr'),
                        'CZ' => __('filament/customers.countries.cz'),
                        'CA' => __('filament/customers.countries.ca'),
                        'AU' => __('filament/customers.countries.au'),
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
