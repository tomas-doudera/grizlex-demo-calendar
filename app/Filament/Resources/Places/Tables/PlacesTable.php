<?php

namespace App\Filament\Resources\Places\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\ToggleColumn;

class PlacesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ToggleColumn::make('is_active')
                    ->label(__('filament/places.columns.active')),
                TextColumn::make('title')
                    ->label(__('filament/places.columns.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('company.title')
                    ->label(__('filament/places.columns.company'))
                    ->sortable(),
                TextColumn::make('venues_count')
                    ->counts('venues')
                    ->label(__('filament/places.columns.venues'))
                    ->badge()
                    ->color('info')
                    ->toggleable(),
                TextColumn::make('city')
                    ->label(__('filament/places.columns.city'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/places.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

            ])
            ->filters([
                SelectFilter::make('company')
                    ->label(__('filament/places.filters.company'))
                    ->relationship('company', 'title')
                    ->preload(),
                TernaryFilter::make('is_active')
                    ->label(__('filament/places.filters.is_active')),
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
