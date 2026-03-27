<?php

namespace App\Filament\Resources\Places\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Slider\Enums\PipsMode;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Forms\Components\Placeholder;

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
                                ->compact()
                                ->schema([
                                    ...collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
                                        ->map(fn (string $day) => Grid::make(['default' => 4])
                                            ->extraAttributes(['class' => '-mb-2 -ml-40'])
                                            ->schema([
                                                Placeholder::make('day_' . $day)
                                                    ->hiddenLabel()
                                                    ->content(__('filament/places.days.' . $day))
                                                    ->extraAttributes(['class' => 'flex items-center pt-10 pl-50'])
                                                    ->columnSpan(1),
                                                static::getOpeningHoursSchema($day)
                                                    ->columnSpan(3),
                                            ]))
                                        ->all(),
                                ]),
                            ]),
                    ]),
            ]);
    }

    protected static function getOpeningHoursSchema(string $day): Slider
    {
        return
            Slider::make('opening_hours.' . $day)
                ->hiddenLabel()
                ->label(__('filament/places.days.' . $day))
                ->range(minValue: 0, maxValue: 23)
                ->step(1)
                ->tooltips(RawJs::make(<<<'JS'
                    `${Math.round($value)}:00`
                JS))
                ->fillTrack([false, true, false])
                ->pips(PipsMode::Steps)
                ->pipsFormatter(RawJs::make(<<<'JS'
                    `${($value)}:00`
                JS))
                ->pipsFilter(RawJs::make(<<<'JS'
                    ($value === 0 || $value === 23)
                        ? 2
                        : -1
                    JS));
    }
}
