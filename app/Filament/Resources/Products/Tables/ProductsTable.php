<?php

namespace App\Filament\Resources\Products\Tables;

use App\Enums\ProductStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament/products.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record): string => $record->sku),
                TextColumn::make('category.name')
                    ->label(__('filament/products.fields.category'))
                    ->sortable()
                    ->badge(),
                TextColumn::make('status')
                    ->label(__('filament/products.fields.status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('filament/products.fields.price'))
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('stock_quantity')
                    ->label(__('filament/products.columns.stock'))
                    ->sortable()
                    ->color(fn (int $state): string => $state <= 10 ? 'danger' : ($state <= 50 ? 'warning' : 'success')),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->label(__('filament/products.columns.featured')),
                IconColumn::make('is_visible')
                    ->boolean()
                    ->label(__('filament/products.columns.visible')),
                ColorColumn::make('color')
                    ->label('Color'),
                TextColumn::make('published_at')
                    ->label(__('filament/products.fields.published_at'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('filament/products.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament/products.filters.status'))
                    ->options(ProductStatus::class),
                SelectFilter::make('category')
                    ->label(__('filament/products.filters.category'))
                    ->relationship('category', 'name')
                    ->preload(),
                TernaryFilter::make('is_featured')
                    ->label(__('filament/products.columns.featured')),
                TernaryFilter::make('is_visible')
                    ->label(__('filament/products.columns.visible')),
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
