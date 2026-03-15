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
                        Tabs\Tab::make(__('filament/products.tabs.general'))
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make(__('filament/products.sections.product_information'))
                                    ->description(__('filament/products.sections.product_information_description'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('filament/products.fields.name'))
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                        TextInput::make('slug')
                                            ->label(__('filament/products.fields.slug'))
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true),
                                        Select::make('category_id')
                                            ->label(__('filament/products.fields.category'))
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('name')->required(),
                                                TextInput::make('slug')->required(),
                                            ]),
                                        Select::make('status')
                                            ->label(__('filament/products.fields.status'))
                                            ->options(ProductStatus::class)
                                            ->required()
                                            ->default('draft')
                                            ->live(),
                                        DatePicker::make('published_at')
                                            ->label(__('filament/products.fields.publish_date'))
                                            ->visible(fn (Get $get): bool => $get('status') === 'active'),
                                        ColorPicker::make('color')
                                            ->label(__('filament/products.fields.product_color')),
                                    ]),
                                Section::make(__('filament/products.sections.description'))
                                    ->schema([
                                        Textarea::make('description')
                                            ->label(__('filament/products.fields.description'))
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        RichEditor::make('content')
                                            ->label(__('filament/products.fields.full_description'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tabs\Tab::make(__('filament/products.tabs.pricing'))
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Section::make(__('filament/products.sections.pricing'))
                                    ->description(__('filament/products.sections.pricing_description'))
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('price')
                                            ->label(__('filament/products.fields.price'))
                                            ->required()
                                            ->numeric()
                                            ->prefix('$')
                                            ->default(0),
                                        TextInput::make('compare_at_price')
                                            ->label(__('filament/products.fields.compare_at_price'))
                                            ->numeric()
                                            ->prefix('$')
                                            ->helperText(__('filament/products.fields.compare_at_price_helper')),
                                        TextInput::make('cost')
                                            ->label(__('filament/products.fields.cost_per_item'))
                                            ->numeric()
                                            ->prefix('$')
                                            ->helperText('Customers won\'t see this'),
                                    ]),
                            ]),
                        Tabs\Tab::make(__('filament/products.tabs.inventory'))
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
                                            ->label(__('filament/products.fields.weight'))
                                            ->numeric()
                                            ->suffix('kg'),
                                        Toggle::make('requires_shipping')
                                            ->label(__('filament/products.fields.requires_shipping'))
                                            ->default(true)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tabs\Tab::make(__('filament/products.tabs.attributes'))
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Section::make('Organization')
                                    ->schema([
                                        TagsInput::make('tags')
                                            ->label(__('filament/products.fields.tags'))
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
