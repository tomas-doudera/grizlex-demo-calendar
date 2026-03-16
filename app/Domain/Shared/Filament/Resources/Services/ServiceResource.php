<?php

namespace App\Domain\Shared\Filament\Resources\Services;

use App\Domain\Shared\Filament\Resources\Services\Pages\CreateService;
use App\Domain\Shared\Filament\Resources\Services\Pages\EditService;
use App\Domain\Shared\Filament\Resources\Services\Pages\ListServices;
use App\Domain\Shared\Filament\Resources\Services\Schemas\ServiceForm;
use App\Domain\Shared\Filament\Resources\Services\Tables\ServicesTable;
use App\Domain\Shared\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('filament/services.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/services.plural_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.reservations');
    }

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
