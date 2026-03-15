<?php

namespace App\Filament\Resources\Reviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.first_name')
                    ->label(__('filament/reviews.columns.customer'))
                    ->state(fn ($record): string => $record->customer ? "{$record->customer->first_name} {$record->customer->last_name}" : __('filament/reviews.columns.anonymous'))
                    ->sortable(),
                TextColumn::make('rating')
                    ->label(__('filament/reviews.fields.rating'))
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('comment')
                    ->label(__('filament/reviews.fields.comment'))
                    ->limit(60)
                    ->wrap()
                    ->toggleable(),
                IconColumn::make('is_published')
                    ->boolean()
                    ->label(__('filament/reviews.columns.published')),
                TextColumn::make('created_at')
                    ->label(__('filament/reviews.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('rating')
                    ->label(__('filament/reviews.filters.rating'))
                    ->options([
                        1 => __('filament/reviews.filters.1_star'),
                        2 => __('filament/reviews.filters.2_stars'),
                        3 => __('filament/reviews.filters.3_stars'),
                        4 => __('filament/reviews.filters.4_stars'),
                        5 => __('filament/reviews.filters.5_stars'),
                    ]),
                TernaryFilter::make('is_published')
                    ->label(__('filament/reviews.filters.is_published')),
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
