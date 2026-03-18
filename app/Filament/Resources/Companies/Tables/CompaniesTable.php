<?php

namespace App\Filament\Resources\Companies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompaniesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament/companies.columns.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('email')
                    ->label(__('filament/companies.fields.email'))
                    ->searchable()
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),
                TextColumn::make('phone')
                    ->label(__('filament/companies.fields.phone'))
                    ->toggleable(),
                TextColumn::make('city')
                    ->label(__('filament/companies.fields.city'))
                    ->toggleable(),
                TextColumn::make('places_count')
                    ->counts('places')
                    ->label(__('filament/companies.columns.places'))
                    ->badge()
                    ->color('info'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('filament/companies.columns.active')),
                TextColumn::make('created_at')
                    ->label(__('filament/companies.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
