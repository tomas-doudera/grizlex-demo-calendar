<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
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
                                Section::make('Order Details')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('order_number')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->default(fn () => 'ORD-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT)),
                                        Select::make('customer_id')
                                            ->relationship('customer', 'first_name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Select::make('status')
                                            ->options(OrderStatus::class)
                                            ->required()
                                            ->default('pending'),
                                        Select::make('payment_method')
                                            ->options([
                                                'credit_card' => 'Credit Card',
                                                'paypal' => 'PayPal',
                                                'bank_transfer' => 'Bank Transfer',
                                                'stripe' => 'Stripe',
                                            ]),
                                    ]),
                                Section::make('Items')
                                    ->schema([
                                        Repeater::make('items')
                                            ->relationship()
                                            ->columns(4)
                                            ->schema([
                                                Select::make('product_id')
                                                    ->relationship('product', 'name')
                                                    ->searchable()
                                                    ->preload(),
                                                TextInput::make('product_name')
                                                    ->required(),
                                                TextInput::make('quantity')
                                                    ->numeric()
                                                    ->required()
                                                    ->default(1)
                                                    ->minValue(1),
                                                TextInput::make('unit_price')
                                                    ->numeric()
                                                    ->required()
                                                    ->prefix('$'),
                                                TextInput::make('total')
                                                    ->numeric()
                                                    ->required()
                                                    ->prefix('$'),
                                            ])
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->collapsible(),
                                    ]),
                                Section::make('Addresses')
                                    ->columns(2)
                                    ->schema([
                                        Textarea::make('shipping_address')
                                            ->rows(3),
                                        Textarea::make('billing_address')
                                            ->rows(3),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Totals')
                                    ->schema([
                                        TextInput::make('subtotal')
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        TextInput::make('tax')
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        TextInput::make('shipping_cost')
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        TextInput::make('discount')
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        TextInput::make('total')
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0)
                                            ->required(),
                                    ]),
                                Section::make('Payment')
                                    ->schema([
                                        Toggle::make('is_paid'),
                                        DateTimePicker::make('paid_at'),
                                        DateTimePicker::make('shipped_at'),
                                        DateTimePicker::make('delivered_at'),
                                    ]),
                                Section::make('Notes')
                                    ->schema([
                                        Textarea::make('notes')
                                            ->rows(3),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
