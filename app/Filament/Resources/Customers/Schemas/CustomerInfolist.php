<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Customer;
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
                                Section::make(__('filament/customers.sections.personal_information'))
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('first_name')
                                            ->label(__('filament/customers.fields.first_name')),
                                        TextEntry::make('last_name')
                                            ->label(__('filament/customers.fields.last_name')),
                                        TextEntry::make('email')
                                            ->label(__('filament/customers.fields.email'))
                                            ->copyable()
                                            ->icon('heroicon-o-envelope'),
                                        TextEntry::make('phone')
                                            ->label(__('filament/customers.fields.phone'))
                                            ->icon('heroicon-o-phone')
                                            ->placeholder(__('filament/customers.columns.not_set')),
                                        TextEntry::make('date_of_birth')
                                            ->label(__('filament/customers.fields.date_of_birth'))
                                            ->date()
                                            ->placeholder(__('filament/customers.columns.not_set')),
                                        TextEntry::make('company_name')
                                            ->label(__('filament/customers.fields.company_name'))
                                            ->placeholder(__('filament/customers.columns.not_set')),
                                    ]),
                                Section::make(__('filament/customers.sections.address'))
                                    ->columns(4)
                                    ->schema([
                                        TextEntry::make('address')
                                            ->label(__('filament/customers.fields.address'))
                                            ->placeholder(__('filament/customers.columns.not_set'))
                                            ->columnSpanFull(),
                                        TextEntry::make('city')
                                            ->label(__('filament/customers.fields.city'))
                                            ->placeholder(__('filament/customers.columns.not_set')),
                                        TextEntry::make('state')
                                            ->label(__('filament/customers.fields.state'))
                                            ->placeholder(__('filament/customers.columns.not_set')),
                                        TextEntry::make('postal_code')
                                            ->label(__('filament/customers.fields.postal_code'))
                                            ->placeholder(__('filament/customers.columns.not_set')),
                                        TextEntry::make('country')
                                            ->label(__('filament/customers.fields.country'))
                                            ->placeholder(__('filament/customers.columns.not_set'))
                                            ->badge(),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make(__('filament/customers.sections.account'))
                                    ->schema([
                                        IconEntry::make('is_active')
                                            ->boolean()
                                            ->label(__('filament/customers.columns.active')),
                                        IconEntry::make('is_vip')
                                            ->boolean()
                                            ->label(__('filament/customers.columns.vip')),
                                        TextEntry::make('lifetime_value')
                                            ->label(__('filament/customers.fields.lifetime_value'))
                                            ->money('USD')
                                            ->color('success'),
                                        TextEntry::make('reservations_count')
                                            ->label(__('filament/customers.infolist.reservations_count'))
                                            ->state(fn (Customer $record): int => $record->reservations()->count())
                                            ->badge()
                                            ->color('info'),
                                        TextEntry::make('created_at')
                                            ->label(__('filament/customers.fields.created_at'))
                                            ->dateTime(),
                                        TextEntry::make('notes')
                                            ->label(__('filament/customers.fields.notes'))
                                            ->placeholder(__('filament/customers.columns.no_notes')),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
