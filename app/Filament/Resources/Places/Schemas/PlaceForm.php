<?php

namespace App\Filament\Resources\Places\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
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
                            ->label(__('filament/places.fields.title'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('short_title')
                            ->label(__('filament/places.fields.short_title'))
                            ->maxLength(10),
                        Select::make('company_id')
                            ->label(__('filament/places.fields.company'))
                            ->relationship('company', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('type')
                            ->label(__('filament/places.fields.type'))
                            ->options([
                                'room' => __('filament/places.types.room'),
                                'court' => __('filament/places.types.court'),
                                'zone' => __('filament/places.types.zone'),
                                'studio' => __('filament/places.types.studio'),
                                'field' => __('filament/places.types.field'),
                                'pool' => __('filament/places.types.pool'),
                            ]),
                        TextInput::make('capacity')
                            ->label(__('filament/places.fields.capacity'))
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                        ColorPicker::make('color')
                            ->label(__('filament/places.fields.color')),
                        TextInput::make('image_url')
                            ->label(__('filament/places.fields.image_url'))
                            ->url()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label(__('filament/places.fields.description'))
                            ->rows(2)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label(__('filament/places.fields.is_active'))
                            ->default(true),
                    ]),
                Section::make(__('filament/places.sections.booking_settings'))
                    ->columns(3)
                    ->collapsed()
                    ->schema([
                        TextInput::make('min_booking_minutes')
                            ->label(__('filament/places.fields.min_booking_minutes'))
                            ->numeric()
                            ->default(30)
                            ->suffix('min'),
                        TextInput::make('max_booking_minutes')
                            ->label(__('filament/places.fields.max_booking_minutes'))
                            ->numeric()
                            ->default(120)
                            ->suffix('min'),
                        TextInput::make('booking_interval_minutes')
                            ->label(__('filament/places.fields.booking_interval_minutes'))
                            ->numeric()
                            ->default(15)
                            ->suffix('min'),
                        TextInput::make('advance_booking_days')
                            ->label(__('filament/places.fields.advance_booking_days'))
                            ->numeric()
                            ->default(30)
                            ->suffix(__('filament/places.suffixes.days')),
                        TextInput::make('cancellation_hours')
                            ->label(__('filament/places.fields.cancellation_hours'))
                            ->numeric()
                            ->default(24)
                            ->suffix(__('filament/places.suffixes.hours')),
                    ]),
            ]);
    }
}
