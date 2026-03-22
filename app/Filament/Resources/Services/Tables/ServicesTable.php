<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('color')
                    ->label(''),
                TextColumn::make('title')
                    ->label(__('filament/services.columns.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('company.title')
                    ->label(__('filament/services.columns.company'))
                    ->sortable(),
                TextColumn::make('duration_minutes')
                    ->label(__('filament/services.columns.duration'))
                    ->suffix(' min')
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('filament/services.fields.price'))
                    ->money('USD')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('filament/services.columns.active')),
                TextColumn::make('reservations_count')
                    ->counts('reservations')
                    ->label(__('filament/services.columns.reservations'))
                    ->badge()
                    ->color('info')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('company')
                    ->label(__('filament/services.filters.company'))
                    ->relationship('company', 'title')
                    ->preload(),
                TernaryFilter::make('is_active')
                    ->label(__('filament/services.filters.is_active')),
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
