<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductStatus;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Product')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Product Information')
                                    ->description('Basic product details.')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true),
                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('name')->required(),
                                                TextInput::make('slug')->required(),
                                            ]),
                                        Select::make('status')
                                            ->options(ProductStatus::class)
                                            ->required()
                                            ->default('draft')
                                            ->live(),
                                        DatePicker::make('published_at')
                                            ->label('Publish Date')
                                            ->visible(fn (Get $get): bool => $get('status') === 'active'),
                                        ColorPicker::make('color')
                                            ->label('Product Color'),
                                    ]),
                                Section::make('Description')
                                    ->schema([
                                        Textarea::make('description')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        RichEditor::make('content')
                                            ->label('Full Description')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tabs\Tab::make('Pricing')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Section::make('Pricing')
                                    ->description('Set product pricing.')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        TextInput::make('compare_at_price')
                                            ->label('Compare at Price')
                                            ->numeric()
                                            ->prefix('$')
                                            ->helperText('Original price before discount'),
                                        TextInput::make('cost')
                                            ->label('Cost per item')
                                            ->numeric()
                                            ->prefix('$')
                                            ->helperText('Customers won\'t see this'),
                                    ]),
                            ]),
                        Tabs\Tab::make('Inventory')
                            ->icon('heroicon-o-cube')
                            ->schema([
                                Section::make('Inventory & Shipping')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('sku')
                                            ->label('SKU')
                                            ->required()
                                            ->unique(ignoreRecord: true),
                                        TextInput::make('barcode')
                                            ->label('Barcode (ISBN, UPC, GTIN)')
                                            ->maxLength(255),
                                        TextInput::make('stock_quantity')
                                            ->label('Quantity')
                                            ->numeric()
                                            ->required()
                                            ->default(0)
                                            ->minValue(0),
                                        TextInput::make('weight')
                                            ->numeric()
                                            ->suffix('kg'),
                                        Toggle::make('requires_shipping')
                                            ->default(true)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tabs\Tab::make('Attributes')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Section::make('Organization')
                                    ->schema([
                                        TagsInput::make('tags')
                                            ->suggestions(['new', 'sale', 'bestseller', 'limited', 'eco-friendly', 'premium'])
                                            ->columnSpanFull(),
                                        KeyValue::make('metadata')
                                            ->label('Custom Attributes')
                                            ->keyLabel('Attribute')
                                            ->valueLabel('Value')
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Visibility')
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('is_visible')
                                            ->label('Visible to customers')
                                            ->default(true),
                                        Toggle::make('is_featured')
                                            ->label('Featured product'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
