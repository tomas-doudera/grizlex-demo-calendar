<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Enums\ProjectStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('color')
                    ->label(''),
                TextColumn::make('name')
                    ->label(__('filament/projects.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record): ?string => $record->customer?->first_name ? "{$record->customer->first_name} {$record->customer->last_name}" : null),
                TextColumn::make('status')
                    ->label(__('filament/projects.fields.status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('progress')
                    ->label(__('filament/projects.columns.progress'))
                    ->suffix('%')
                    ->sortable()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 40 => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('budget')
                    ->label(__('filament/projects.columns.budget'))
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('spent')
                    ->label(__('filament/projects.columns.spent'))
                    ->money('USD')
                    ->sortable()
                    ->color(fn ($record): string => $record->budget && $record->spent > $record->budget ? 'danger' : 'gray'),
                IconColumn::make('is_pinned')
                    ->boolean()
                    ->label('Pinned'),
                TextColumn::make('start_date')
                    ->label(__('filament/projects.fields.start_date'))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('due_date')
                    ->label(__('filament/projects.columns.deadline'))
                    ->date()
                    ->sortable()
                    ->color(fn ($record): string => $record->due_date && $record->due_date->isPast() && $record->status !== ProjectStatus::Completed ? 'danger' : 'gray'),
                TextColumn::make('tickets_count')
                    ->counts('tickets')
                    ->label('Tickets')
                    ->badge()
                    ->color('info')
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament/projects.filters.status'))
                    ->options(ProjectStatus::class)
                    ->multiple(),
                TernaryFilter::make('is_pinned')
                    ->label('Pinned'),
                SelectFilter::make('customer')
                    ->label(__('filament/projects.filters.customer'))
                    ->relationship('customer', 'first_name')
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
