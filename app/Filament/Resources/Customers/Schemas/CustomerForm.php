<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make(__('filament/customers.tabs.general'))
                            ->icon(Heroicon::OutlinedInformationCircle)
                            ->schema([
                                Section::make(__('filament/customers.sections.personal_information'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('first_name')
                                            ->label(__('filament/customers.fields.first_name'))
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('last_name')
                                            ->label(__('filament/customers.fields.last_name'))
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label(__('filament/customers.fields.email'))
                                            ->email()
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255),
                                        TextInput::make('phone')
                                            ->label(__('filament/customers.fields.phone'))
                                            ->tel()
                                            ->maxLength(255),
                                        DatePicker::make('date_of_birth')
                                            ->label(__('filament/customers.fields.date_of_birth'))
                                            ->maxDate(now()->subYears(18)),
                                    ]),
                                Section::make(__('filament/customers.sections.company_details'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('company_name')
                                            ->label(__('filament/customers.fields.company_name'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make(__('filament/customers.tabs.address'))
                            ->icon(Heroicon::OutlinedMapPin)
                            ->schema([
                                Section::make(__('filament/customers.sections.address'))
                                    ->columns(2)
                                    ->schema([
                                        Textarea::make('address')
                                            ->label(__('filament/customers.fields.address'))
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        TextInput::make('city')
                                            ->label(__('filament/customers.fields.city')),
                                        TextInput::make('state')
                                            ->label(__('filament/customers.fields.state')),
                                        TextInput::make('postal_code')
                                            ->label(__('filament/customers.fields.postal_code')),
                                        Select::make('country')
                                            ->label(__('filament/customers.fields.country'))
                                            ->options([
                                                'US' => __('filament/customers.countries.us'),
                                                'UK' => __('filament/customers.countries.uk'),
                                                'DE' => __('filament/customers.countries.de'),
                                                'FR' => __('filament/customers.countries.fr'),
                                                'CZ' => __('filament/customers.countries.cz'),
                                                'CA' => __('filament/customers.countries.ca'),
                                                'AU' => __('filament/customers.countries.au'),
                                            ])
                                            ->searchable(),
                                    ]),
                            ]),
                        Tab::make(__('filament/customers.tabs.account'))
                            ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                            ->schema([
                                Section::make(__('filament/customers.sections.status'))
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label(__('filament/customers.fields.active'))
                                            ->default(true),
                                        Toggle::make('is_vip')
                                            ->label(__('filament/customers.fields.vip_customer')),
                                        TextInput::make('lifetime_value')
                                            ->label(__('filament/customers.fields.lifetime_value'))
                                            ->numeric()
                                            ->prefix('$')
                                            ->disabled(),
                                    ]),
                                Section::make(__('filament/customers.sections.notes'))
                                    ->schema([
                                        Textarea::make('notes')
                                            ->label(__('filament/customers.fields.notes'))
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
