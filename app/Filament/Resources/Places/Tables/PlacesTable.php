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
                    ->label('Venue')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record): ?string => $record->short_title),
                TextColumn::make('company.title')
                    ->label('Company')
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('capacity')
                    ->sortable(),
                TextColumn::make('hourly_rate')
                    ->money('USD')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                TextColumn::make('reservations_count')
                    ->counts('reservations')
                    ->label('Bookings')
                    ->badge()
                    ->color('info')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('company')
                    ->relationship('company', 'title')
                    ->preload(),
                SelectFilter::make('type')
                    ->options([
                        'court' => 'Court',
                        'room' => 'Room',
                        'pool' => 'Pool',
                        'studio' => 'Studio',
                        'field' => 'Field',
                        'track' => 'Track',
                    ]),
                TernaryFilter::make('is_active'),
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
