<?php

use App\Domain\Booking\Enums\ReservationStatus;
use App\Domain\IndividualBooking\Enums\StaffRole;
use App\Domain\Shared\Models\User;
use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use Filament\Facades\Filament;

$locales = ['en', 'cs', 'sk', 'de'];

$translationFiles = [
    'navigation',
    'reservations',
    'staff',
    'companies',
    'places',
    'services',
    'payments',
    'reviews',
    'customers',
    'widgets',
    'enums',
];

test('all translation files exist for each locale', function () use ($locales, $translationFiles) {
    foreach ($locales as $locale) {
        foreach ($translationFiles as $file) {
            $path = lang_path("{$locale}/filament/{$file}.php");
            expect(file_exists($path))
                ->toBeTrue("Missing translation file: {$path}");
        }
    }
});

test('translation files return arrays for each locale', function () use ($locales, $translationFiles) {
    foreach ($locales as $locale) {
        foreach ($translationFiles as $file) {
            $translations = require lang_path("{$locale}/filament/{$file}.php");
            expect($translations)
                ->toBeArray("Translation file {$locale}/filament/{$file}.php does not return an array");
        }
    }
});

test('all translation keys from EN exist in other locales', function () use ($locales, $translationFiles) {
    foreach ($translationFiles as $file) {
        $enTranslations = require lang_path("en/filament/{$file}.php");
        $enKeys = array_keys_recursive($enTranslations);

        foreach (['cs', 'sk', 'de'] as $locale) {
            $localeTranslations = require lang_path("{$locale}/filament/{$file}.php");
            $localeKeys = array_keys_recursive($localeTranslations);

            foreach ($enKeys as $key) {
                expect(in_array($key, $localeKeys))
                    ->toBeTrue("Missing key '{$key}' in {$locale}/filament/{$file}.php");
            }
        }
    }
});

test('navigation translations resolve correctly for each locale', function (string $locale) {
    app()->setLocale($locale);

    expect(__('filament/navigation.groups.calendars'))->not->toBe('filament/navigation.groups.calendars');
    expect(__('filament/navigation.groups.reservations'))->not->toBe('filament/navigation.groups.reservations');
    expect(__('filament/navigation.groups.crm'))->not->toBe('filament/navigation.groups.crm');
    expect(__('filament/navigation.groups.finance'))->not->toBe('filament/navigation.groups.finance');
})->with($locales);

test('enum labels are translated correctly', function (string $locale) {
    app()->setLocale($locale);

    foreach (ReservationStatus::cases() as $status) {
        $label = $status->getLabel();
        expect($label)->not->toContain('filament/enums', "Enum label not translated for {$status->name} in locale {$locale}");
    }

    foreach (StaffRole::cases() as $role) {
        $label = $role->getLabel();
        expect($label)->not->toContain('filament/enums', "Enum label not translated for {$role->name} in locale {$locale}");
    }
})->with($locales);

test('Czech locale returns Czech translations', function () {
    app()->setLocale('cs');

    expect(__('filament/navigation.groups.calendars'))->toBe('Kalendáře');
    expect(__('filament/navigation.groups.reservations'))->toBe('Rezervace');
    expect(__('filament/navigation.pages.dashboard'))->toBe('Přehled');
});

test('German locale returns German translations', function () {
    app()->setLocale('de');

    expect(__('filament/navigation.groups.calendars'))->toBe('Kalender');
    expect(__('filament/navigation.groups.finance'))->toBe('Finanzen');
    expect(__('filament/navigation.pages.dashboard'))->toBe('Dashboard');
});

test('Slovak locale returns Slovak translations', function () {
    app()->setLocale('sk');

    expect(__('filament/navigation.groups.calendars'))->toBe('Kalendáre');
    expect(__('filament/navigation.groups.reservations'))->toBe('Rezervácie');
});

test('language switcher plugin is registered on admin panel', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $panel = Filament::getPanel('admin');
    $plugins = $panel->getPlugins();

    $hasLanguageSwitcher = false;
    foreach ($plugins as $plugin) {
        if ($plugin instanceof FilamentLanguageSwitcherPlugin) {
            $hasLanguageSwitcher = true;
            break;
        }
    }

    expect($hasLanguageSwitcher)->toBeTrue('Language switcher plugin is not registered');
});

test('admin panel renders with language switcher for authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin')
        ->assertSuccessful();
});

/**
 * Recursively extract all keys from a nested array using dot notation.
 *
 * @return array<int, string>
 */
function array_keys_recursive(array $array, string $prefix = ''): array
{
    $keys = [];

    foreach ($array as $key => $value) {
        $fullKey = $prefix ? "{$prefix}.{$key}" : (string) $key;

        if (is_array($value)) {
            $keys = array_merge($keys, array_keys_recursive($value, $fullKey));
        } else {
            $keys[] = $fullKey;
        }
    }

    return $keys;
}
