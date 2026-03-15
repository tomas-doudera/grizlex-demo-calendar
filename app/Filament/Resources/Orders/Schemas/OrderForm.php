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
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make(__('filament/orders.sections.details'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('order_number')
                                            ->label(__('filament/orders.fields.order_number'))
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->default(fn () => 'ORD-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT)),
                                        Select::make('customer_id')
                                            ->label(__('filament/orders.fields.customer'))
                                            ->relationship('customer', 'first_name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Select::make('status')
                                            ->label(__('filament/orders.fields.status'))
                                            ->options(OrderStatus::class)
                                            ->required()
                                            ->default('pending'),
                                        Select::make('payment_method')
                                            ->label(__('filament/orders.fields.payment_method'))
                                            ->options([
                                                'credit_card' => __('filament/orders.payment_methods.credit_card'),
                                                'paypal' => __('filament/orders.payment_methods.paypal'),
                                                'bank_transfer' => __('filament/orders.payment_methods.bank_transfer'),
                                                'stripe' => __('filament/orders.payment_methods.stripe'),
                                            ]),
                                    ]),
                                Section::make(__('filament/orders.sections.items'))
                                    ->schema([
                                        Repeater::make('items')
                                            ->label(__('filament/orders.fields.items'))
                                            ->relationship()
                                            ->columns(4)
                                            ->schema([
                                                Select::make('product_id')
                                                    ->label(__('filament/orders.fields.product'))
                                                    ->relationship('product', 'name')
                                                    ->searchable()
                                                    ->preload(),
                                                TextInput::make('product_name')
                                                    ->label(__('filament/orders.fields.product_name'))
                                                    ->required(),
                                                TextInput::make('quantity')
                                                    ->label(__('filament/orders.fields.quantity'))
                                                    ->numeric()
                                                    ->required()
                                                    ->default(1)
                                                    ->minValue(1),
                                                TextInput::make('unit_price')
                                                    ->label(__('filament/orders.fields.unit_price'))
                                                    ->numeric()
                                                    ->required()
                                                    ->prefix('$'),
                                                TextInput::make('total')
                                                    ->label(__('filament/orders.fields.total'))
                                                    ->numeric()
                                                    ->required()
                                                    ->prefix('$'),
                                            ])
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->collapsible(),
                                    ]),
                                Section::make(__('filament/orders.sections.addresses'))
                                    ->columns(2)
                                    ->schema([
                                        Textarea::make('shipping_address')
                                            ->label(__('filament/orders.fields.shipping_address'))
                                            ->rows(3),
                                        Textarea::make('billing_address')
                                            ->label(__('filament/orders.fields.billing_address'))
                                            ->rows(3),
                                    ]),
                            ]),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make(__('filament/orders.sections.totals'))
                                    ->schema([
                                        TextInput::make('subtotal')
                                            ->label(__('filament/orders.fields.subtotal'))
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        TextInput::make('tax')
                                            ->label(__('filament/orders.fields.tax'))
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        TextInput::make('shipping_cost')
                                            ->label(__('filament/orders.fields.shipping_cost'))
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        TextInput::make('discount')
                                            ->label(__('filament/orders.fields.discount'))
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        TextInput::make('total')
                                            ->label(__('filament/orders.fields.total'))
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0)
                                            ->required(),
                                    ]),
                                Section::make(__('filament/orders.sections.payment'))
                                    ->schema([
                                        Toggle::make('is_paid')
                                            ->label(__('filament/orders.fields.is_paid')),
                                        DateTimePicker::make('paid_at')
                                            ->label(__('filament/orders.fields.paid_at')),
                                        DateTimePicker::make('shipped_at')
                                            ->label(__('filament/orders.fields.shipped_at')),
                                        DateTimePicker::make('delivered_at')
                                            ->label(__('filament/orders.fields.delivered_at')),
                                    ]),
                                Section::make(__('filament/orders.sections.notes'))
                                    ->schema([
                                        Textarea::make('notes')
                                            ->label(__('filament/orders.fields.notes'))
                                            ->rows(3),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
