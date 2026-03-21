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
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('General')
                            ->icon(Heroicon::OutlinedInformationCircle)
                            ->schema([
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
                                                        Select::make('staff_id')
                                                            ->label(__('filament/reservations.fields.staff'))
                                                            ->relationship('staff', 'first_name', fn ($query) => $query->bookable())
                                                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                                                            ->searchable()
                                                            ->preload(),
                                                        DateTimePicker::make('from_time')
                                                            ->label(__('filament/reservations.fields.from_time'))
                                                            ->required()
                                                            ->minutesStep(5)
                                                            ->seconds(false),
                                                        DateTimePicker::make('to_time')
                                                            ->label(__('filament/reservations.fields.to_time'))
                                                            ->required()
                                                            ->minutesStep(5)
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
                                                        Select::make('user_id')
                                                            ->label(__('filament/reservations.fields.user'))
                                                            ->relationship('user', 'name')
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
                                                Section::make(__('filament/reservations.sections.status'))
                                                    ->schema([
                                                        ToggleButtons::make('status')
                                                            ->label(__('filament/reservations.fields.status'))
                                                            ->options(ReservationStatus::class)
                                                            ->required()
                                                            ->default('pending')
                                                            ->inline(),
                                                        DateTimePicker::make('confirmed_at')
                                                            ->label(__('filament/reservations.fields.confirmed_at'))
                                                            ->seconds(false),
                                                        DateTimePicker::make('cancelled_at')
                                                            ->label(__('filament/reservations.fields.cancelled_at'))
                                                            ->seconds(false),
                                                        Textarea::make('cancellation_reason')
                                                            ->label(__('filament/reservations.fields.cancellation_reason'))
                                                            ->rows(2),
                                                    ]),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
