<?php

namespace App\Filament\Resources\Staff\Tables;

use App\Enums\StaffRole;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('color')
                    ->label(''),
                TextColumn::make('full_name')
                    ->label('Name')
                    ->state(fn ($record): string => "{$record->first_name} {$record->last_name}")
                    ->searchable(['first_name', 'last_name'])
                    ->sortable('first_name')
                    ->weight('medium'),
                TextColumn::make('email')
                    ->searchable()
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),
                TextColumn::make('company.title')
                    ->label('Company')
                    ->sortable(),
                TextColumn::make('role')
                    ->badge()
                    ->sortable(),
                TextColumn::make('specialization')
                    ->placeholder('N/A')
                    ->toggleable(),
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
                SelectFilter::make('role')
                    ->options(StaffRole::class),
                SelectFilter::make('company')
                    ->relationship('company', 'title')
                    ->preload(),
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
