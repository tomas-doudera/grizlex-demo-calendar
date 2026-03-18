<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make(__('filament/companies.sections.details'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label(__('filament/companies.fields.title'))
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label(__('filament/companies.fields.email'))
                                            ->email(),
                                        TextInput::make('phone')
                                            ->label(__('filament/companies.fields.phone'))
                                            ->tel(),
                                        TextInput::make('website')
                                            ->label(__('filament/companies.fields.website'))
                                            ->url(),
                                        Textarea::make('description')
                                            ->label(__('filament/companies.fields.description'))
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                                Section::make(__('filament/companies.sections.address'))
                                    ->columns(3)
                                    ->schema([
                                        Textarea::make('address')
                                            ->label(__('filament/companies.fields.address'))
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        TextInput::make('city')
                                            ->label(__('filament/companies.fields.city')),
                                        TextInput::make('postal_code')
                                            ->label(__('filament/companies.fields.postal_code')),
                                        TextInput::make('country')
                                            ->label(__('filament/companies.fields.country')),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make(__('filament/companies.sections.settings'))
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label(__('filament/companies.fields.is_active'))
                                            ->default(true),
                                        Select::make('timezone')
                                            ->label(__('filament/companies.fields.timezone'))
                                            ->options(fn (): array => collect(timezone_identifiers_list())
                                                ->mapWithKeys(fn (string $tz): array => [$tz => $tz])
                                                ->toArray())
                                            ->searchable()
                                            ->default('Europe/Prague')
                                            ->required(),
                                        Select::make('currency')
                                            ->label(__('filament/companies.fields.currency'))
                                            ->options([
                                                'CZK' => 'CZK',
                                                'EUR' => 'EUR',
                                                'USD' => 'USD',
                                                'GBP' => 'GBP',
                                            ])
                                            ->default('CZK')
                                            ->required(),
                                        TimePicker::make('opening_time')
                                            ->label(__('filament/companies.fields.opening_time'))
                                            ->seconds(false),
                                        TimePicker::make('closing_time')
                                            ->label(__('filament/companies.fields.closing_time'))
                                            ->seconds(false),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
