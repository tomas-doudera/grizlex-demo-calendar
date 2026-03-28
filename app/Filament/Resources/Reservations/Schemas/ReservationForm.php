<?php

namespace App\Filament\Resources\Reservations\Schemas;

use App\Enums\ReservationStatus;
use App\Models\Service;
use App\Models\Venue;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
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
                                                    ->schema([
                                                        Section::make(__('filament/reservations.sections.location_and_staff'))
                                                            ->schema([
                                                                Select::make('company_id')
                                                                    ->label(__('filament/reservations.fields.company'))
                                                                    ->relationship('company', 'title')
                                                                    ->required()
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->live(),
                                                                Select::make('venue_id')
                                                                    ->label(__('filament/reservations.fields.venue'))
                                                                    ->options(function (Get $get) {
                                                                        $companyId = $get('company_id');
                                                                        if (! $companyId) {
                                                                            return [];
                                                                        }

                                                                        return Venue::query()
                                                                            ->with('place')
                                                                            ->whereHas('place', fn ($q) => $q->where('company_id', $companyId))
                                                                            ->orderBy('sort_order')
                                                                            ->orderBy('title')
                                                                            ->get()
                                                                            ->mapWithKeys(fn (Venue $v) => [$v->id => $v->place->title.' – '.$v->title]);
                                                                    })
                                                                    ->required()
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->live(),
                                                                Select::make('staff_id')
                                                                    ->label(__('filament/reservations.fields.staff'))
                                                                    ->relationship('staff', 'first_name', fn ($query) => $query->bookable())
                                                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                                                                    ->searchable()
                                                                    ->preload(),
                                                                Select::make('service_id')
                                                                    ->label(__('filament/reservations.fields.service'))
                                                                    ->options(function (Get $get) {
                                                                        $companyId = $get('company_id');
                                                                        if (! $companyId) {
                                                                            return [];
                                                                        }

                                                                        return Service::query()
                                                                            ->where('company_id', $companyId)
                                                                            ->where('is_active', true)
                                                                            ->orderBy('sort_order')
                                                                            ->orderBy('title')
                                                                            ->pluck('title', 'id');
                                                                    })
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->live(),
                                                            ])->columns(2),
                                                        Section::make(__('filament/reservations.sections.time'))
                                                            ->schema([
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
                                                            ])->columns(2),
                                                        Section::make(__('filament/reservations.sections.capacity'))
                                                            ->schema([
                                                                TextInput::make('capacity')
                                                                    ->label(__('filament/reservations.fields.capacity'))
                                                                    ->numeric()
                                                                    ->minValue(0)
                                                                    ->maxValue(function (Get $get): ?int {
                                                                        $venueId = $get('venue_id');
                                                                        if (! $venueId) {
                                                                            return null;
                                                                        }

                                                                        return Venue::query()->find($venueId)?->capacity;
                                                                    })
                                                                    ->placeholder(function (Get $get): ?string {
                                                                        $venueId = $get('venue_id');
                                                                        if (! $venueId) {
                                                                            return null;
                                                                        }

                                                                        $cap = Venue::query()->find($venueId)?->capacity;

                                                                        return $cap !== null ? 'Max: '.$cap : null;
                                                                    })
                                                                    ->live(),
                                                                TextInput::make('booked_count')
                                                                    ->label(__('filament/reservations.fields.booked_count'))
                                                                    ->numeric()
                                                                    ->minValue(0)
                                                                    ->maxValue(fn (Get $get) => $get('capacity'))
                                                                    ->default(0),
                                                            ])->columns(2),
                                                        Section::make(__('filament/reservations.sections.customer_information'))
                                                            ->schema([
                                                                Select::make('customers')
                                                                    ->label(__('filament/reservations.fields.customers'))
                                                                    ->relationship(
                                                                        'customers',
                                                                        'email',
                                                                        fn ($query) => $query->orderBy('last_name')->orderBy('first_name'),
                                                                    )
                                                                    ->multiple()
                                                                    ->getOptionLabelFromRecordUsing(
                                                                        fn ($record) => "{$record->first_name} {$record->last_name} ({$record->email})",
                                                                    )
                                                                    ->searchable(['first_name', 'last_name', 'email'])
                                                                    ->preload(),
                                                            ]),
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
