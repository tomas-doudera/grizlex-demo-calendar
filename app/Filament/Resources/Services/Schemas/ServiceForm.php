<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make(__('filament/services.tabs.general'))
                            ->icon(Heroicon::OutlinedInformationCircle)
                            ->schema([
                                Section::make(__('filament/services.sections.details'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label(__('filament/services.fields.title'))
                                            ->required()
                                            ->maxLength(255),
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
                                            ->nullable(),
                                        TextInput::make('sort_order')
                                            ->label(__('filament/services.fields.sort_order'))
                                            ->numeric()
                                            ->default(0),
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
                            ]),
                    ]),
            ]);
    }
}
