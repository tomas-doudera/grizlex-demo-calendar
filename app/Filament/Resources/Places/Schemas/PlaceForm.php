<?php

namespace App\Filament\Resources\Places\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament/places.sections.details'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament/places.fields.venue_name'))
                            ->required(),
                        TextInput::make('short_title')
                            ->label(__('filament/places.fields.short_name')),
                        Select::make('company_id')
                            ->label(__('filament/places.fields.company'))
                            ->relationship('company', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('type')
                            ->label(__('filament/places.fields.type'))
                            ->options([
                                'court' => __('filament/places.types.court'),
                                'room' => __('filament/places.types.room'),
                                'pool' => __('filament/places.types.pool'),
                                'studio' => __('filament/places.types.studio'),
                                'field' => __('filament/places.types.field'),
                                'track' => __('filament/places.types.track'),
                            ]),
                        TextInput::make('capacity')
                            ->label(__('filament/places.fields.capacity'))
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                        TextInput::make('hourly_rate')
                            ->label(__('filament/places.fields.hourly_rate'))
                            ->numeric()
                            ->prefix('$'),
                        ColorPicker::make('color')
                            ->label(__('filament/places.fields.color')),
                        Toggle::make('is_active')
                            ->label(__('filament/places.fields.is_active'))
                            ->default(true),
                        Textarea::make('description')
                            ->label(__('filament/places.fields.description'))
                            ->rows(2)
                            ->columnSpanFull(),
                        TagsInput::make('amenities')
                            ->label(__('filament/places.fields.amenities'))
                            ->suggestions(['WiFi', 'AC', 'Showers', 'Lockers', 'Parking', 'Sound System', 'Lighting', 'Mirrors'])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
