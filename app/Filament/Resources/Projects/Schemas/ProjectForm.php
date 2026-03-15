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
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make(__('filament/projects.sections.details'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('filament/projects.fields.name'))
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        Textarea::make('description')
                                            ->label(__('filament/projects.fields.description'))
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Select::make('customer_id')
                                            ->label(__('filament/projects.fields.customer'))
                                            ->relationship('customer', 'first_name')
                                            ->searchable()
                                            ->preload(),
                                        Select::make('status')
                                            ->label(__('filament/projects.fields.status'))
                                            ->options(ProjectStatus::class)
                                            ->required()
                                            ->default('planning'),
                                        DatePicker::make('start_date')
                                            ->label(__('filament/projects.fields.start_date')),
                                        DatePicker::make('due_date')
                                            ->label(__('filament/projects.fields.due_date'))
                                            ->afterOrEqual('start_date'),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make(__('filament/projects.sections.progress_budget'))
                                    ->schema([
                                        TextInput::make('progress')
                                            ->label(__('filament/projects.fields.progress'))
                                            ->numeric()
                                            ->suffix('%')
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->default(0),
                                        TextInput::make('budget')
                                            ->label(__('filament/projects.fields.budget'))
                                            ->numeric()
                                            ->prefix('$'),
                                        TextInput::make('spent')
                                            ->label(__('filament/projects.fields.spent'))
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        ColorPicker::make('color')
                                            ->label(__('filament/projects.fields.color')),
                                        Toggle::make('is_pinned')
                                            ->label('Pin to top'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
