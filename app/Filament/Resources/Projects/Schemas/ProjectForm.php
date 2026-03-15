<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\ProjectStatus;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Project Details')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        Textarea::make('description')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Select::make('customer_id')
                                            ->relationship('customer', 'first_name')
                                            ->searchable()
                                            ->preload(),
                                        Select::make('status')
                                            ->options(ProjectStatus::class)
                                            ->required()
                                            ->default('planning'),
                                        DatePicker::make('start_date'),
                                        DatePicker::make('due_date')
                                            ->afterOrEqual('start_date'),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Progress & Budget')
                                    ->schema([
                                        TextInput::make('progress')
                                            ->numeric()
                                            ->suffix('%')
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->default(0),
                                        TextInput::make('budget')
                                            ->numeric()
                                            ->prefix('$'),
                                        TextInput::make('spent')
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        ColorPicker::make('color'),
                                        Toggle::make('is_pinned')
                                            ->label('Pin to top'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
