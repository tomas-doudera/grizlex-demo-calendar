<?php

namespace App\Providers\Filament;

use App\Domain\Booking\BookingPlugin;
use App\Domain\ClassBooking\ClassBookingPlugin;
use App\Domain\Finance\FinancePlugin;
use App\Domain\IndividualBooking\IndividualBookingPlugin;
use App\Domain\Notification\NotificationPlugin;
use App\Domain\PlaceBooking\PlaceBookingPlugin;
use App\Domain\Shared\SharedPlugin;
use App\Http\Middleware\ApplyLocale;
use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use TomasDoudera\CalMe\CalMePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->font('Inter')
            ->colors([
                'primary' => Color::Zinc,
                'gray' => Color::Zinc,
                'danger' => Color::Rose,
                'success' => Color::Emerald,
                'info' => Color::Sky,
                'warning' => Color::Amber,
            ])
            ->brandName('Grizlex')
            ->navigationGroups([
                NavigationGroup::make(fn (): string => __('filament/navigation.groups.calendars')),
                NavigationGroup::make(fn (): string => __('filament/navigation.groups.reservations')),
                NavigationGroup::make(fn (): string => __('filament/navigation.groups.crm')),
                NavigationGroup::make(fn (): string => __('filament/navigation.groups.finance')),
            ])
            ->plugins([
                SharedPlugin::make(),
                BookingPlugin::make(),
                IndividualBookingPlugin::make(),
                ClassBookingPlugin::make(),
                PlaceBookingPlugin::make(),
                FinancePlugin::make(),
                NotificationPlugin::make(),
                CalMePlugin::make(),
                FilamentLanguageSwitcherPlugin::make()
                    ->locales([
                        ['code' => 'en', 'name' => 'English', 'flag' => 'us'],
                        ['code' => 'cs', 'name' => 'Čeština', 'flag' => 'cz'],
                        ['code' => 'sk', 'name' => 'Slovenčina', 'flag' => 'sk'],
                        ['code' => 'de', 'name' => 'Deutsch', 'flag' => 'de'],
                    ])
                    ->rememberLocale()
                    ->showOnAuthPages(),
            ])
            ->pages([
                Dashboard::class,
            ])
            ->sidebarWidth('18rem')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ApplyLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
