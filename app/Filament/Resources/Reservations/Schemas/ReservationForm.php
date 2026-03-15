<?php

namespace App\Filament\Resources\Reservations\Schemas;

use App\Enums\ReservationStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReservationForm
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
                                Section::make('Booking Details')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('company_id')
                                            ->relationship('company', 'title')
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        Select::make('place_id')
                                            ->relationship('place', 'title')
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        Select::make('service_id')
                                            ->relationship('service', 'name')
                                            ->searchable()
                                            ->preload(),
                                        Select::make('staff_id')
                                            ->relationship('staff', 'first_name')
                                            ->searchable()
                                            ->preload(),
                                        DateTimePicker::make('from_time')
                                            ->required()
                                            ->seconds(false),
                                        DateTimePicker::make('to_time')
                                            ->required()
                                            ->seconds(false),
                                        TextInput::make('capacity')
                                            ->numeric()
                                            ->default(1),
                                        TextInput::make('booked_count')
                                            ->numeric()
                                            ->default(0),
                                    ]),
                                Section::make('Guest Information')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('customer_id')
                                            ->relationship('customer', 'first_name')
                                            ->searchable()
                                            ->preload(),
                                        TextInput::make('guest_name'),
                                        TextInput::make('guest_email')
                                            ->email(),
                                        TextInput::make('guest_phone')
                                            ->tel(),
                                        Textarea::make('notes')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Status & Pricing')
                                    ->schema([
                                        ToggleButtons::make('status')
                                            ->options(ReservationStatus::class)
                                            ->required()
                                            ->default('pending')
                                            ->inline(),
                                        TextInput::make('total_price')
                                            ->numeric()
                                            ->prefix('$'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
