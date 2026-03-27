<?php

namespace App\Filament\Resources\Venues\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class VenueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make(__('filament/venues.tabs.general'))
                            ->icon(Heroicon::OutlinedInformationCircle)
                            ->schema([
                                Section::make(__('filament/venues.sections.details'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label(__('filament/venues.fields.title'))
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('place_id')
                                            ->label(__('filament/venues.fields.place'))
                                            ->relationship('place', 'title')
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        Select::make('type')
                                            ->label(__('filament/venues.fields.type'))
                                            ->options([
                                                'room' => __('filament/venues.types.room'),
                                                'court' => __('filament/venues.types.court'),
                                                'zone' => __('filament/venues.types.zone'),
                                                'studio' => __('filament/venues.types.studio'),
                                                'field' => __('filament/venues.types.field'),
                                                'pool' => __('filament/venues.types.pool'),
                                            ]),
                                        TextInput::make('capacity')
                                            ->label(__('filament/venues.fields.capacity'))
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1),
                                        Textarea::make('description')
                                            ->label(__('filament/venues.fields.description'))
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        Toggle::make('is_active')
                                            ->label(__('filament/venues.fields.is_active')),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
