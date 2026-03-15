<?php

namespace App\Filament\Resources\Reservations\Tables;

use App\Enums\ReservationStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('from_time')
                    ->label('Date & Time')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->description(fn ($record): string => $record->to_time ? 'to '.$record->to_time->format('H:i') : ''),
                TextColumn::make('company.title')
                    ->label('Company')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('place.title')
                    ->label('Venue')
                    ->sortable(),
                TextColumn::make('service.name')
                    ->label('Service')
                    ->placeholder('N/A')
                    ->toggleable(),
                TextColumn::make('staff.first_name')
                    ->label('Staff')
                    ->state(fn ($record): string => $record->staff ? "{$record->staff->first_name} {$record->staff->last_name}" : '')
                    ->placeholder('Unassigned')
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('guest_name')
                    ->label('Guest')
                    ->placeholder('N/A')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('booked_count')
                    ->label('Booked')
                    ->sortable()
                    ->suffix(fn ($record): string => $record->capacity ? " / {$record->capacity}" : '')
                    ->toggleable(),
            ])
            ->defaultSort('from_time', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(ReservationStatus::class)
                    ->multiple(),
                SelectFilter::make('company')
                    ->relationship('company', 'title')
                    ->preload(),
                SelectFilter::make('place')
                    ->relationship('place', 'title')
                    ->searchable()
                    ->preload(),
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
