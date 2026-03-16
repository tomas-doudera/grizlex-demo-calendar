<?php

namespace App\Domain\Shared\Filament\Resources\Reviews\Schemas;

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
                Section::make(__('filament/reviews.sections.review'))
                    ->columns(2)
                    ->schema([
                        Select::make('reservation_id')
                            ->label(__('filament/reviews.fields.reservation'))
                            ->relationship('reservation', 'id')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('customer_id')
                            ->label(__('filament/reviews.fields.customer'))
                            ->relationship('customer', 'first_name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('rating')
                            ->label(__('filament/reviews.fields.rating'))
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(5)
                            ->default(5),
                        Toggle::make('is_published')
                            ->label(__('filament/reviews.fields.is_published'))
                            ->default(true),
                        Textarea::make('comment')
                            ->label(__('filament/reviews.fields.comment'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
