<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make(__('filament/companies.tabs.general'))
                            ->icon(Heroicon::OutlinedInformationCircle)
                            ->schema([
                                Section::make(__('filament/companies.sections.basic_info'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label(__('filament/companies.fields.title'))
                                            ->required()
                                            ->maxLength(255),
                                        Toggle::make('is_active')
                                                ->label(__('filament/companies.fields.is_active'))
                                                ->inline(false)
                                                ->default(true),
                                        Textarea::make('description')
                                            ->label(__('filament/companies.fields.description'))
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                                Section::make(__('filament/companies.sections.contact'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('email')
                                            ->label(__('filament/companies.fields.email'))
                                            ->email(),
                                        TextInput::make('phone')
                                            ->label(__('filament/companies.fields.phone'))
                                            ->tel(),
                                        TextInput::make('website')
                                            ->label(__('filament/companies.fields.website'))
                                            ->url()
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make(__('filament/companies.tabs.address'))
                            ->icon(Heroicon::OutlinedMapPin)
                            ->schema([
                                Section::make(__('filament/companies.sections.address'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('address')
                                            ->label(__('filament/companies.fields.address'))
                                            ->maxLength(500)
                                            ->columnSpanFull(),
                                    ]),
                                Section::make(__('filament/companies.sections.location'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('country')
                                            ->label(__('filament/companies.fields.country')),
                                        TextInput::make('city')
                                            ->label(__('filament/companies.fields.city')),
                                        TextInput::make('postal_code')
                                            ->label(__('filament/companies.fields.postal_code')),
                                    ]),
                            ]),
                        Tab::make(__('filament/companies.tabs.preferences'))
                            ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                            ->schema([
                                Section::make(__('filament/companies.sections.localization'))
                                    ->columns(2)
                                    ->schema([
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
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
