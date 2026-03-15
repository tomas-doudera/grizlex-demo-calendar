<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Review')
                    ->columns(2)
                    ->schema([
                        Select::make('reservation_id')
                            ->relationship('reservation', 'id')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('customer_id')
                            ->relationship('customer', 'first_name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('rating')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(5)
                            ->default(5),
                        Toggle::make('is_published')
                            ->default(true),
                        Textarea::make('comment')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
