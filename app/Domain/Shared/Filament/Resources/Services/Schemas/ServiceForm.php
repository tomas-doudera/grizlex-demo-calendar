<?php

namespace App\Domain\Shared\Filament\Resources\Services\Schemas;

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
                Section::make(__('filament/services.sections.details'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament/services.fields.name'))
                            ->required(),
                        Select::make('company_id')
                            ->label(__('filament/services.fields.company'))
                            ->relationship('company', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('duration_minutes')
                            ->label(__('filament/services.fields.duration_minutes'))
                            ->numeric()
                            ->suffix('min')
                            ->default(60),
                        TextInput::make('price')
                            ->label(__('filament/services.fields.price'))
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        TextInput::make('max_participants')
                            ->label(__('filament/services.fields.max_participants'))
                            ->numeric()
                            ->default(1),
                        ColorPicker::make('color')
                            ->label(__('filament/services.fields.color')),
                        Toggle::make('is_active')
                            ->label(__('filament/services.fields.is_active'))
                            ->default(true),
                        Textarea::make('description')
                            ->label(__('filament/services.fields.description'))
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
