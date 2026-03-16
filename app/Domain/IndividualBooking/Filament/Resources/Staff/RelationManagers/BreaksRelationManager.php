<?php

namespace App\Domain\IndividualBooking\Filament\Resources\Staff\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BreaksRelationManager extends RelationManager
{
    protected static string $relationship = 'breaks';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->label(__('filament/staff.breaks.date'))
                    ->helperText(__('filament/staff.breaks.date_help')),
                Select::make('day_of_week')
                    ->label(__('filament/staff.breaks.day_of_week'))
                    ->options([
                        0 => __('filament/enums.day_of_week.monday'),
                        1 => __('filament/enums.day_of_week.tuesday'),
                        2 => __('filament/enums.day_of_week.wednesday'),
                        3 => __('filament/enums.day_of_week.thursday'),
                        4 => __('filament/enums.day_of_week.friday'),
                        5 => __('filament/enums.day_of_week.saturday'),
                        6 => __('filament/enums.day_of_week.sunday'),
                    ])
                    ->helperText(__('filament/staff.breaks.day_of_week_help')),
                TextInput::make('start_time')
                    ->type('time')
                    ->required(),
                TextInput::make('end_time')
                    ->type('time')
                    ->required(),
                TextInput::make('reason'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->placeholder(__('filament/staff.breaks.recurring')),
                TextColumn::make('day_of_week')
                    ->formatStateUsing(fn (?int $state): string => match ($state) {
                        0 => __('filament/enums.day_of_week.monday'),
                        1 => __('filament/enums.day_of_week.tuesday'),
                        2 => __('filament/enums.day_of_week.wednesday'),
                        3 => __('filament/enums.day_of_week.thursday'),
                        4 => __('filament/enums.day_of_week.friday'),
                        5 => __('filament/enums.day_of_week.saturday'),
                        6 => __('filament/enums.day_of_week.sunday'),
                        default => '',
                    }),
                TextColumn::make('start_time'),
                TextColumn::make('end_time'),
                TextColumn::make('reason'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
