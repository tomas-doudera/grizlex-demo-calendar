<?php

namespace App\Domain\IndividualBooking\Filament\Resources\Staff;

use App\Domain\IndividualBooking\Filament\Resources\Staff\Pages\CreateStaff;
use App\Domain\IndividualBooking\Filament\Resources\Staff\Pages\EditStaff;
use App\Domain\IndividualBooking\Filament\Resources\Staff\Pages\ListStaff;
use App\Domain\IndividualBooking\Filament\Resources\Staff\RelationManagers\BreaksRelationManager;
use App\Domain\IndividualBooking\Filament\Resources\Staff\RelationManagers\SchedulesRelationManager;
use App\Domain\IndividualBooking\Filament\Resources\Staff\Schemas\StaffForm;
use App\Domain\IndividualBooking\Filament\Resources\Staff\Tables\StaffTable;
use App\Domain\IndividualBooking\Models\Staff;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'first_name';

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.reservations');
    }

    public static function getModelLabel(): string
    {
        return __('filament/staff.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/staff.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return StaffForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SchedulesRelationManager::class,
            BreaksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaff::route('/'),
            'create' => CreateStaff::route('/create'),
            'edit' => EditStaff::route('/{record}/edit'),
        ];
    }
}
