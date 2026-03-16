<?php

namespace App\Domain\IndividualBooking\Filament\Resources\Staff\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('day_of_week')
                    ->options([
                        0 => __('filament/enums.day_of_week.monday'),
                        1 => __('filament/enums.day_of_week.tuesday'),
                        2 => __('filament/enums.day_of_week.wednesday'),
                        3 => __('filament/enums.day_of_week.thursday'),
                        4 => __('filament/enums.day_of_week.friday'),
                        5 => __('filament/enums.day_of_week.saturday'),
                        6 => __('filament/enums.day_of_week.sunday'),
                    ])
                    ->required(),
                TextInput::make('start_time')
                    ->type('time')
                    ->required(),
                TextInput::make('end_time')
                    ->type('time')
                    ->required(),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day_of_week')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => __('filament/enums.day_of_week.monday'),
                        1 => __('filament/enums.day_of_week.tuesday'),
                        2 => __('filament/enums.day_of_week.wednesday'),
                        3 => __('filament/enums.day_of_week.thursday'),
                        4 => __('filament/enums.day_of_week.friday'),
                        5 => __('filament/enums.day_of_week.saturday'),
                        6 => __('filament/enums.day_of_week.sunday'),
                        default => '',
                    })
                    ->sortable(),
                TextColumn::make('start_time'),
                TextColumn::make('end_time'),
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('day_of_week')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
