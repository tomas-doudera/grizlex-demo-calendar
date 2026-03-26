<?php

namespace App\Filament\Resources\Reservations\Tables;

use App\Enums\ReservationStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
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
                TextColumn::make('venue.title')
                    ->label(__('filament/reservations.columns.venue'))
                    ->description(fn ($record): ?string => $record->venue?->place?->title)
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament/reservations.fields.status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('guest_name')
                    ->label(__('filament/reservations.columns.guest'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('booked_count')
                    ->label(__('filament/reservations.columns.booked'))
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->booked_count < $record->capacity ? 'success' : 'danger')
                    ->suffix(fn ($record): string => $record->capacity ? " / {$record->capacity}" : '')
                    ->toggleable(),
                TextColumn::make('company.title')
                    ->label(__('filament/reservations.columns.company'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('filament/reservations.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('venue')
                    ->label(__('filament/reservations.filters.venue'))
                    ->relationship('venue', 'title')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
