<?php

namespace App\Filament\Resources\Companies\Schemas;

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
                                Section::make('Company Details')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Company Name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->email(),
                                        TextInput::make('phone')
                                            ->tel(),
                                        TextInput::make('website')
                                            ->url(),
                                        Textarea::make('description')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Address')
                                    ->columns(3)
                                    ->schema([
                                        Textarea::make('address')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        TextInput::make('city'),
                                        TextInput::make('country'),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Settings')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true),
                                        TimePicker::make('opening_time')
                                            ->seconds(false),
                                        TimePicker::make('closing_time')
                                            ->seconds(false),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
