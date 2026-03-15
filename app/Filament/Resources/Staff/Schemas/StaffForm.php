<?php

namespace App\Filament\Resources\Staff\Schemas;

use App\Enums\StaffRole;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament/staff.sections.details'))
                    ->columns(2)
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->label(__('filament/staff.fields.avatar'))
                            ->avatar()
                            ->disk('public')
                            ->directory('staff-avatars')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpanFull(),
                        TextInput::make('first_name')
                            ->label(__('filament/staff.fields.first_name'))
                            ->required(),
                        TextInput::make('last_name')
                            ->label(__('filament/staff.fields.last_name'))
                            ->required(),
                        TextInput::make('email')
                            ->label(__('filament/staff.fields.email'))
                            ->email(),
                        TextInput::make('phone')
                            ->label(__('filament/staff.fields.phone'))
                            ->tel(),
                        Select::make('company_id')
                            ->label(__('filament/staff.fields.company'))
                            ->relationship('company', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        ToggleButtons::make('role')
                            ->label(__('filament/staff.fields.role'))
                            ->options(StaffRole::class)
                            ->required()
                            ->default('instructor')
                            ->inline(),
                        TextInput::make('specialization')
                            ->label(__('filament/staff.fields.specialization')),
                        ColorPicker::make('color')
                            ->label(__('filament/staff.fields.color'))
                            ->required(),
                        Toggle::make('is_active')
                            ->label(__('filament/staff.fields.is_active'))
                            ->default(true),
                        Textarea::make('bio')
                            ->label(__('filament/staff.fields.bio'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
