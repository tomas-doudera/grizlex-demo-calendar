<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(4)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(3)
                            ->schema([
                                Section::make('Personal Information')
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('first_name'),
                                        TextEntry::make('last_name'),
                                        TextEntry::make('email')
                                            ->copyable()
                                            ->icon('heroicon-o-envelope'),
                                        TextEntry::make('phone')
                                            ->icon('heroicon-o-phone')
                                            ->placeholder('Not set'),
                                        TextEntry::make('date_of_birth')
                                            ->date()
                                            ->placeholder('Not set'),
                                        TextEntry::make('company_name')
                                            ->placeholder('Not set'),
                                        TextEntry::make('job_title')
                                            ->placeholder('Not set'),
                                    ]),
                                Section::make('Address')
                                    ->columns(4)
                                    ->schema([
                                        TextEntry::make('address')
                                            ->placeholder('Not set')
                                            ->columnSpanFull(),
                                        TextEntry::make('city')
                                            ->placeholder('Not set'),
                                        TextEntry::make('state')
                                            ->placeholder('Not set'),
                                        TextEntry::make('postal_code')
                                            ->placeholder('Not set'),
                                        TextEntry::make('country')
                                            ->placeholder('Not set')
                                            ->badge(),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Account')
                                    ->schema([
                                        IconEntry::make('is_active')
                                            ->boolean()
                                            ->label('Active'),
                                        IconEntry::make('is_vip')
                                            ->boolean()
                                            ->label('VIP'),
                                        TextEntry::make('lifetime_value')
                                            ->money('USD')
                                            ->color('success'),
                                        TextEntry::make('orders_count')
                                            ->state(fn ($record): int => $record->orders()->count())
                                            ->badge()
                                            ->color('info'),
                                        TextEntry::make('created_at')
                                            ->dateTime(),
                                        TextEntry::make('notes')
                                            ->placeholder('No notes'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
