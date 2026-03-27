<?php

namespace App\Filament\Resources\Venues\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VenuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament/venues.columns.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('place.title')
                    ->label(__('filament/venues.columns.place'))
                    ->sortable(),
                TextColumn::make('place.company.title')
                    ->label(__('filament/venues.columns.company'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('type')
                    ->label(__('filament/venues.fields.type'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('capacity')
                    ->label(__('filament/venues.fields.capacity'))
                    ->badge()
                    ->color('info')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label(__('filament/venues.columns.active')),
            ])
            ->filters([
                SelectFilter::make('place')
                    ->label(__('filament/venues.filters.place'))
                    ->relationship('place', 'title')
                    ->preload()
                    ->searchable(),
                TernaryFilter::make('is_active')
                    ->label(__('filament/venues.filters.is_active')),
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
