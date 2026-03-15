<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
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
                                Section::make('Payment Details')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('payment_number')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->default(fn () => 'PAY-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT)),
                                        TextInput::make('amount')
                                            ->numeric()
                                            ->required()
                                            ->prefix('$'),
                                        Select::make('reservation_id')
                                            ->relationship('reservation', 'id')
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        Select::make('customer_id')
                                            ->relationship('customer', 'first_name')
                                            ->searchable()
                                            ->preload(),
                                        TextInput::make('transaction_id')
                                            ->columnSpanFull(),
                                        Textarea::make('notes')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Status')
                                    ->schema([
                                        ToggleButtons::make('status')
                                            ->options(PaymentStatus::class)
                                            ->required()
                                            ->default('pending')
                                            ->inline(),
                                        Select::make('method')
                                            ->options(PaymentMethod::class),
                                        DateTimePicker::make('paid_at'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
