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
                                Section::make('Ticket Information')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('ticket_number')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->default(fn () => 'TKT-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT)),
                                        TextInput::make('subject')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        Select::make('customer_id')
                                            ->relationship('customer', 'first_name')
                                            ->searchable()
                                            ->preload(),
                                        Select::make('project_id')
                                            ->relationship('project', 'name')
                                            ->searchable()
                                            ->preload(),
                                        Select::make('assigned_to')
                                            ->relationship('assignedUser', 'name')
                                            ->searchable()
                                            ->preload(),
                                        Select::make('category')
                                            ->options([
                                                'Bug' => 'Bug',
                                                'Feature Request' => 'Feature Request',
                                                'Support' => 'Support',
                                                'Question' => 'Question',
                                                'Billing' => 'Billing',
                                            ]),
                                    ]),
                                Section::make('Description')
                                    ->schema([
                                        RichEditor::make('description')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Status & Priority')
                                    ->schema([
                                        ToggleButtons::make('status')
                                            ->options(TicketStatus::class)
                                            ->required()
                                            ->default('open')
                                            ->inline(),
                                        ToggleButtons::make('priority')
                                            ->options(TicketPriority::class)
                                            ->required()
                                            ->default('medium')
                                            ->inline(),
                                        Toggle::make('is_resolved')
                                            ->label('Resolved'),
                                    ]),
                                Section::make('Timestamps')
                                    ->schema([
                                        DateTimePicker::make('resolved_at'),
                                        DateTimePicker::make('first_response_at'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
