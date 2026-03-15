<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        Select::make('company_id')
                            ->relationship('company', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('duration_minutes')
                            ->numeric()
                            ->suffix('min')
                            ->default(60),
                        TextInput::make('price')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        TextInput::make('max_participants')
                            ->numeric()
                            ->default(1),
                        ColorPicker::make('color'),
                        Toggle::make('is_active')
                            ->default(true),
                        Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
