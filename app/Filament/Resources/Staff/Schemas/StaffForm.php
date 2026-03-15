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
                Section::make('Staff Details')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->label('Avatar')
                            ->avatar()
                            ->disk('public')
                            ->directory('staff-avatars')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpanFull(),
                        TextInput::make('first_name')
                            ->required(),
                        TextInput::make('last_name')
                            ->required(),
                        TextInput::make('email')
                            ->email(),
                        TextInput::make('phone')
                            ->tel(),
                        Select::make('company_id')
                            ->relationship('company', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        ToggleButtons::make('role')
                            ->options(StaffRole::class)
                            ->required()
                            ->default('instructor')
                            ->inline(),
                        TextInput::make('specialization'),
                        ColorPicker::make('color')
                            ->required(),
                        Toggle::make('is_active')
                            ->default(true),
                        Textarea::make('bio')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
