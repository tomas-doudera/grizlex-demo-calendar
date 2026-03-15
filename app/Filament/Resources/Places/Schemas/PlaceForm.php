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
                Section::make('Venue Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Venue Name')
                            ->required(),
                        TextInput::make('short_title')
                            ->label('Short Name'),
                        Select::make('company_id')
                            ->relationship('company', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('type')
                            ->options([
                                'court' => 'Court',
                                'room' => 'Room',
                                'pool' => 'Pool',
                                'studio' => 'Studio',
                                'field' => 'Field',
                                'track' => 'Track',
                            ]),
                        TextInput::make('capacity')
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                        TextInput::make('hourly_rate')
                            ->numeric()
                            ->prefix('$'),
                        ColorPicker::make('color'),
                        Toggle::make('is_active')
                            ->default(true),
                        Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull(),
                        TagsInput::make('amenities')
                            ->suggestions(['WiFi', 'AC', 'Showers', 'Lockers', 'Parking', 'Sound System', 'Lighting', 'Mirrors'])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
