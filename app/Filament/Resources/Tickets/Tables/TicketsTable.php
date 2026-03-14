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
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->copyable(),
                TextColumn::make('subject')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('customer.first_name')
                    ->label('Customer')
                    ->state(fn ($record): string => $record->customer ? "{$record->customer->first_name} {$record->customer->last_name}" : 'N/A')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('priority')
                    ->badge()
                    ->sortable(),
                TextColumn::make('category')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('assignedUser.name')
                    ->label('Assigned To')
                    ->placeholder('Unassigned')
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
                    ->options(TicketStatus::class)
                    ->multiple(),
                SelectFilter::make('priority')
                    ->options(TicketPriority::class)
                    ->multiple(),
                SelectFilter::make('category')
                    ->options([
                        'Bug' => 'Bug',
                        'Feature Request' => 'Feature Request',
                        'Support' => 'Support',
                        'Question' => 'Question',
                        'Billing' => 'Billing',
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
