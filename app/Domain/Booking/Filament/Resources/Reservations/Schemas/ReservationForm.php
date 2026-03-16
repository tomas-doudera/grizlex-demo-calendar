<?php

namespace App\Domain\Booking\Filament\Resources\Reservations\Schemas;

use App\Domain\Booking\Enums\ReservationStatus;
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
                                Section::make(__('filament/reservations.sections.booking_details'))
                                    ->columns(2)
                                    ->schema([
                                        Select::make('company_id')
                                            ->label(__('filament/reservations.fields.company'))
                                            ->relationship('company', 'title')
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        Select::make('place_id')
                                            ->label(__('filament/reservations.fields.place'))
                                            ->relationship('place', 'title')
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        Select::make('service_id')
                                            ->label(__('filament/reservations.fields.service'))
                                            ->relationship('service', 'name')
                                            ->searchable()
                                            ->preload(),
                                        Select::make('staff_id')
                                            ->label(__('filament/reservations.fields.staff'))
                                            ->relationship('staff', 'first_name')
                                            ->searchable()
                                            ->preload(),
                                        DateTimePicker::make('from_time')
                                            ->label(__('filament/reservations.fields.from_time'))
                                            ->required()
                                            ->seconds(false),
                                        DateTimePicker::make('to_time')
                                            ->label(__('filament/reservations.fields.to_time'))
                                            ->required()
                                            ->seconds(false),
                                        TextInput::make('capacity')
                                            ->label(__('filament/reservations.fields.capacity'))
                                            ->numeric()
                                            ->default(1),
                                        TextInput::make('booked_count')
                                            ->label(__('filament/reservations.fields.booked_count'))
                                            ->numeric()
                                            ->default(0),
                                    ]),
                                Section::make(__('filament/reservations.sections.guest_information'))
                                    ->columns(2)
                                    ->schema([
                                        Select::make('customer_id')
                                            ->label(__('filament/reservations.fields.customer'))
                                            ->relationship('customer', 'first_name')
                                            ->searchable()
                                            ->preload(),
                                        TextInput::make('guest_name')
                                            ->label(__('filament/reservations.fields.guest_name')),
                                        TextInput::make('guest_email')
                                            ->label(__('filament/reservations.fields.guest_email'))
                                            ->email(),
                                        TextInput::make('guest_phone')
                                            ->label(__('filament/reservations.fields.guest_phone'))
                                            ->tel(),
                                        Textarea::make('notes')
                                            ->label(__('filament/reservations.fields.notes'))
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make(__('filament/reservations.sections.status_pricing'))
                                    ->schema([
                                        ToggleButtons::make('status')
                                            ->label(__('filament/reservations.fields.status'))
                                            ->options(ReservationStatus::class)
                                            ->required()
                                            ->default('pending')
                                            ->inline(),
                                        TextInput::make('total_price')
                                            ->label(__('filament/reservations.fields.total_price'))
                                            ->numeric()
                                            ->prefix('$'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
