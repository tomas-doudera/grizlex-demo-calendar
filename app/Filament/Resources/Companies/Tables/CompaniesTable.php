<?php

namespace App\Filament\Resources\Companies\Tables;

use App\Models\Company;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CompaniesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ToggleColumn::make('is_active')
                    ->label(__('filament/companies.columns.active')),
                TextColumn::make('title')
                    ->label(__('filament/companies.columns.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('places_count')
                    ->counts('places')
                    ->label(__('filament/companies.columns.places'))
                    ->badge()
                    ->color('info'),
                TextColumn::make('email')
                    ->label(__('filament/companies.fields.email'))
                    ->searchable()
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),
                TextColumn::make('phone')
                    ->label(__('filament/companies.fields.phone'))
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/companies.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->recordActions([
                Action::make('website')
                    ->hiddenLabel()
                    ->icon('heroicon-o-globe-alt')
                    ->visible(fn (Company $record) => filled($record->website))
                    ->url(fn (Company $record) => $record->website)
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
