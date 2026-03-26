<?php

namespace App\Filament\Resources\Places\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PlaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make(__('filament/places.tabs.general'))
                            ->icon(Heroicon::OutlinedInformationCircle)
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Section::make(__('filament/places.sections.details'))
                                            ->columns(2)
                                            ->schema([
                                                TextInput::make('title')
                                                    ->label(__('filament/places.fields.title'))
                                                    ->required()
                                                    ->maxLength(255),
                                                Select::make('company_id')
                                                    ->label(__('filament/places.fields.company'))
                                                    ->relationship('company', 'title')
                                                    ->required()
                                                    ->searchable()
                                                    ->preload(),
                                                Textarea::make('description')
                                                    ->label(__('filament/places.fields.description'))
                                                    ->rows(2)
                                                    ->columnSpanFull(),
                                                Toggle::make('is_active')
                                                    ->label(__('filament/places.fields.is_active'))
                                                    ->default(true),
                                            ]),
                                        Section::make(__('filament/places.sections.contact'))
                                            ->columns(2)
                                            ->schema([
                                                TextInput::make('email')
                                                    ->label(__('filament/places.fields.email'))
                                                    ->email()
                                                    ->maxLength(255),
                                                TextInput::make('phone')
                                                    ->label(__('filament/places.fields.phone'))
                                                    ->tel()
                                                    ->maxLength(255),
                                                TextInput::make('address')
                                                    ->label(__('filament/places.fields.address'))
                                                    ->maxLength(255)
                                                    ->columnSpanFull(),
                                                TextInput::make('city')
                                                    ->label(__('filament/places.fields.city'))
                                                    ->maxLength(255),
                                                TextInput::make('postal_code')
                                                    ->label(__('filament/places.fields.postal_code'))
                                                    ->maxLength(255),
                                                TextInput::make('country')
                                                    ->label(__('filament/places.fields.country'))
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ]),
                        Tab::make(__('filament/places.tabs.opening_hours'))
                            ->icon(Heroicon::OutlinedClock)
                            ->schema([
                                Section::make(__('filament/places.sections.opening_hours'))
                                    ->description(__('filament/places.sections.opening_hours_description'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('opening_hours.monday')
                                            ->label(__('filament/places.days.monday'))
                                            ->placeholder(__('filament/places.opening_hours_placeholder'))
                                            ->maxLength(255),
                                        TextInput::make('opening_hours.tuesday')
                                            ->label(__('filament/places.days.tuesday'))
                                            ->placeholder(__('filament/places.opening_hours_placeholder'))
                                            ->maxLength(255),
                                        TextInput::make('opening_hours.wednesday')
                                            ->label(__('filament/places.days.wednesday'))
                                            ->placeholder(__('filament/places.opening_hours_placeholder'))
                                            ->maxLength(255),
                                        TextInput::make('opening_hours.thursday')
                                            ->label(__('filament/places.days.thursday'))
                                            ->placeholder(__('filament/places.opening_hours_placeholder'))
                                            ->maxLength(255),
                                        TextInput::make('opening_hours.friday')
                                            ->label(__('filament/places.days.friday'))
                                            ->placeholder(__('filament/places.opening_hours_placeholder'))
                                            ->maxLength(255),
                                        TextInput::make('opening_hours.saturday')
                                            ->label(__('filament/places.days.saturday'))
                                            ->placeholder(__('filament/places.opening_hours_placeholder'))
                                            ->maxLength(255),
                                        TextInput::make('opening_hours.sunday')
                                            ->label(__('filament/places.days.sunday'))
                                            ->placeholder(__('filament/places.opening_hours_placeholder'))
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
