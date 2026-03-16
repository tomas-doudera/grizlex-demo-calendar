<?php

namespace App\Domain\Finance\Filament\Resources\Payments;

use App\Domain\Finance\Filament\Resources\Payments\Pages\CreatePayment;
use App\Domain\Finance\Filament\Resources\Payments\Pages\EditPayment;
use App\Domain\Finance\Filament\Resources\Payments\Pages\ListPayments;
use App\Domain\Finance\Filament\Resources\Payments\Schemas\PaymentForm;
use App\Domain\Finance\Filament\Resources\Payments\Tables\PaymentsTable;
use App\Domain\Finance\Enums\PaymentStatus;
use App\Domain\Finance\Models\Payment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('filament/payments.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/payments.plural_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.finance');
    }

    public static function form(Schema $schema): Schema
    {
        return PaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'edit' => EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', PaymentStatus::Pending)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
