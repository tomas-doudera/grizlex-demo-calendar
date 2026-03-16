<?php

namespace App\Domain\Booking\Filament\Resources\Reservations\Tables;

use App\Domain\Booking\Enums\ReservationStatus;
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
                    ->label(__('filament/reservations.columns.date_time'))
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->description(fn ($record): string => $record->to_time ? 'to '.$record->to_time->format('H:i') : ''),
                TextColumn::make('company.title')
                    ->label(__('filament/reservations.columns.company'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('place.title')
                    ->label(__('filament/reservations.columns.venue'))
                    ->sortable(),
                TextColumn::make('service.name')
                    ->label(__('filament/reservations.columns.service'))
                    ->placeholder(__('filament/reservations.placeholders.na'))
                    ->toggleable(),
                TextColumn::make('staff.first_name')
                    ->label(__('filament/reservations.columns.staff'))
                    ->state(fn ($record): string => $record->staff ? "{$record->staff->first_name} {$record->staff->last_name}" : '')
                    ->placeholder(__('filament/reservations.placeholders.unassigned'))
                    ->toggleable(),
                TextColumn::make('status')
                    ->label(__('filament/reservations.fields.status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('guest_name')
                    ->label(__('filament/reservations.columns.guest'))
                    ->placeholder(__('filament/reservations.placeholders.na'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('total_price')
                    ->label(__('filament/reservations.fields.total_price'))
                    ->money('USD')
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('booked_count')
                    ->label(__('filament/reservations.columns.booked'))
                    ->sortable()
                    ->suffix(fn ($record): string => $record->capacity ? " / {$record->capacity}" : '')
                    ->toggleable(),
            ])
            ->defaultSort('from_time', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament/reservations.filters.status'))
                    ->options(ReservationStatus::class)
                    ->multiple(),
                SelectFilter::make('company')
                    ->label(__('filament/reservations.filters.company'))
                    ->relationship('company', 'title')
                    ->preload(),
                SelectFilter::make('place')
                    ->label(__('filament/reservations.filters.place'))
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
