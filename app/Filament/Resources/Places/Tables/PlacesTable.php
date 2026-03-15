<?php

namespace App\Filament\Resources\Places\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PlacesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('color')
                    ->label(''),
                TextColumn::make('title')
                    ->label(__('filament/places.columns.venue'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record): ?string => $record->short_title),
                TextColumn::make('company.title')
                    ->label(__('filament/places.columns.company'))
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('filament/places.fields.type'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('capacity')
                    ->label(__('filament/places.fields.capacity'))
                    ->sortable(),
                TextColumn::make('hourly_rate')
                    ->label(__('filament/places.fields.hourly_rate'))
                    ->money('USD')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('filament/places.columns.active')),
                TextColumn::make('reservations_count')
                    ->counts('reservations')
                    ->label(__('filament/places.columns.bookings'))
                    ->badge()
                    ->color('info')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('company')
                    ->label(__('filament/places.filters.company'))
                    ->relationship('company', 'title')
                    ->preload(),
                SelectFilter::make('type')
                    ->label(__('filament/places.filters.type'))
                    ->options([
                        'court' => __('filament/places.types.court'),
                        'room' => __('filament/places.types.room'),
                        'pool' => __('filament/places.types.pool'),
                        'studio' => __('filament/places.types.studio'),
                        'field' => __('filament/places.types.field'),
                        'track' => __('filament/places.types.track'),
                    ]),
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
