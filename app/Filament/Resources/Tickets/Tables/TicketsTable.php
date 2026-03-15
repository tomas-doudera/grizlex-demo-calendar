<?php

namespace App\Filament\Resources\Tickets\Tables;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_number')
                    ->label(__('filament/tickets.fields.ticket_number'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->copyable(),
                TextColumn::make('subject')
                    ->label(__('filament/tickets.fields.subject'))
                    ->searchable()
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('customer.first_name')
                    ->label(__('filament/tickets.columns.customer'))
                    ->state(fn ($record): string => $record->customer ? "{$record->customer->first_name} {$record->customer->last_name}" : __('filament/tickets.columns.no_customer'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament/tickets.fields.status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('priority')
                    ->label(__('filament/tickets.fields.priority'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('category')
                    ->label(__('filament/tickets.fields.category'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('assignedUser.name')
                    ->label(__('filament/tickets.columns.assigned'))
                    ->placeholder(__('filament/tickets.columns.unassigned'))
                    ->toggleable(),
                IconColumn::make('is_resolved')
                    ->boolean()
                    ->label('Resolved'),
                TextColumn::make('comments_count')
                    ->counts('comments')
                    ->label('Comments')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/tickets.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->groups([
                Group::make('status')
                    ->collapsible(),
                Group::make('priority')
                    ->collapsible(),
                Group::make('category')
                    ->collapsible(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament/tickets.filters.status'))
                    ->options(TicketStatus::class)
                    ->multiple(),
                SelectFilter::make('priority')
                    ->label(__('filament/tickets.filters.priority'))
                    ->options(TicketPriority::class)
                    ->multiple(),
                SelectFilter::make('category')
                    ->label(__('filament/tickets.filters.category'))
                    ->options([
                        'Bug' => __('filament/tickets.categories.bug'),
                        'Feature Request' => __('filament/tickets.categories.feature_request'),
                        'Support' => __('filament/tickets.categories.support'),
                        'Question' => __('filament/tickets.categories.question'),
                        'Billing' => __('filament/tickets.categories.billing'),
                    ]),
                TernaryFilter::make('is_resolved')
                    ->label('Resolved'),
                SelectFilter::make('assigned_to')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Assigned To'),
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
