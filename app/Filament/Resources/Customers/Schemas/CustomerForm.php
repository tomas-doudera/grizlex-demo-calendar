<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Grid::make(1)
                    ->columnSpan(2)
                    ->schema([
                        Section::make('Personal Information')
                            ->columns(2)
                            ->schema([
                                TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                                DatePicker::make('date_of_birth')
                                    ->maxDate(now()->subYears(18)),
                            ]),
                        Section::make('Company Details')
                            ->columns(2)
                            ->schema([
                                TextInput::make('company_name'),
                                TextInput::make('job_title'),
                            ]),
                        Section::make('Address')
                            ->columns(2)
                            ->schema([
                                Textarea::make('address')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                TextInput::make('city'),
                                TextInput::make('state'),
                                TextInput::make('postal_code'),
                                Select::make('country')
                                    ->options([
                                        'US' => 'United States',
                                        'UK' => 'United Kingdom',
                                        'DE' => 'Germany',
                                        'FR' => 'France',
                                        'CZ' => 'Czech Republic',
                                        'CA' => 'Canada',
                                        'AU' => 'Australia',
                                    ])
                                    ->searchable(),
                            ]),
                    ]),
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Status')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                                Toggle::make('is_vip')
                                    ->label('VIP Customer'),
                                TextInput::make('lifetime_value')
                                    ->numeric()
                                    ->prefix('$')
                                    ->disabled(),
                            ]),
                        Section::make('Notes')
                            ->schema([
                                Textarea::make('notes')
                                    ->rows(4),
                            ]),
                    ]),
            ]);
    }
}
