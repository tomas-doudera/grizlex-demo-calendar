<?php

namespace App\Filament\Resources\Tickets\Schemas;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make(__('filament/tickets.sections.ticket_info'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('ticket_number')
                                            ->label(__('filament/tickets.fields.ticket_number'))
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->default(fn () => 'TKT-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT)),
                                        TextInput::make('subject')
                                            ->label(__('filament/tickets.fields.subject'))
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        Select::make('customer_id')
                                            ->label(__('filament/tickets.fields.customer'))
                                            ->relationship('customer', 'first_name')
                                            ->searchable()
                                            ->preload(),
                                        Select::make('project_id')
                                            ->label(__('filament/tickets.fields.project'))
                                            ->relationship('project', 'name')
                                            ->searchable()
                                            ->preload(),
                                        Select::make('assigned_to')
                                            ->label(__('filament/tickets.fields.assigned_to'))
                                            ->relationship('assignedUser', 'name')
                                            ->searchable()
                                            ->preload(),
                                        Select::make('category')
                                            ->label(__('filament/tickets.fields.category'))
                                            ->options([
                                                'Bug' => __('filament/tickets.categories.bug'),
                                                'Feature Request' => __('filament/tickets.categories.feature_request'),
                                                'Support' => __('filament/tickets.categories.support'),
                                                'Question' => __('filament/tickets.categories.question'),
                                                'Billing' => __('filament/tickets.categories.billing'),
                                            ]),
                                    ]),
                                Section::make(__('filament/tickets.sections.description'))
                                    ->schema([
                                        RichEditor::make('description')
                                            ->label(__('filament/tickets.fields.description'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make(__('filament/tickets.sections.status_priority'))
                                    ->schema([
                                        ToggleButtons::make('status')
                                            ->label(__('filament/tickets.fields.status'))
                                            ->options(TicketStatus::class)
                                            ->required()
                                            ->default('open')
                                            ->inline(),
                                        ToggleButtons::make('priority')
                                            ->label(__('filament/tickets.fields.priority'))
                                            ->options(TicketPriority::class)
                                            ->required()
                                            ->default('medium')
                                            ->inline(),
                                        Toggle::make('is_resolved')
                                            ->label('Resolved'),
                                    ]),
                                Section::make(__('filament/tickets.sections.timestamps'))
                                    ->schema([
                                        DateTimePicker::make('resolved_at')
                                            ->label(__('filament/tickets.fields.resolved_at')),
                                        DateTimePicker::make('first_response_at')
                                            ->label(__('filament/tickets.fields.first_response_at')),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
