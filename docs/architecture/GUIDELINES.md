# Modular Reservation Platform — Architecture Guidelines

> **Status:** Draft v1 — Approved decisions, pending implementation.
> **Branch:** `modular-base` → will implement on `feature/modular-architecture`

---

## 1. Architecture Overview

This application is a **modular reservation platform** built on Laravel 12 + Filament v5. The architecture uses **domain folders** with **Filament Plugins** as the integration layer.

### Core Principles

1. **Each domain is config-toggleable** — set `enabled => false` in `config/domains.php` and its UI disappears. ServiceProvider still loads (migrations, events stay), but Filament resources/pages/widgets are not registered.
2. **Domains never import from sibling domains directly** — always through `Shared` contracts or events.
3. **Events for side effects** — creating a reservation fires an event; notifications/payments listen.
4. **Config-driven behavior** — each domain has its own config file for defaults and toggles.
5. **Global company scoping** — `BelongsToCompany` trait with automatic global scope on all tenant-bound models.
6. **Thin contracts, fat implementations** — interfaces define minimum shared behavior; each domain handles its own complexity.

### What This Is NOT

- Not a multi-app monorepo — all domains live in one Laravel app.
- Not a microservice architecture — domains share one database and one deployment.
- Not a Composer package ecosystem (yet) — domains are folders, not packages. They can be extracted later.

---

## 2. Domain Map

```
app/Domain/
  Shared/                # Contracts, traits, value objects, base classes
  Booking/               # Reservation engine — availability, conflicts, time slots
  IndividualBooking/     # 1:1 staff appointments
  ClassBooking/          # Group classes & enrollment
  PlaceBooking/          # Room/facility reservations
  Finance/               # Payments, refunds
  Notification/          # Booking lifecycle communications
```

### Domain Toggle System

All domains are controlled via `config/domains.php`:

```php
// config/domains.php
return [
    'booking' => ['enabled' => true],
    'individual-booking' => ['enabled' => true],
    'class-booking' => ['enabled' => true],
    'place-booking' => ['enabled' => true],
    'finance' => ['enabled' => true],
    'notification' => ['enabled' => true],
];
```

A lightweight `DomainRegistry` helper in `App\Domain\Shared\DomainRegistry` provides runtime checks:

```php
<?php

namespace App\Domain\Shared;

class DomainRegistry
{
    public static function isEnabled(string $domain): bool
    {
        return config("domains.{$domain}.enabled", false);
    }

    /** @return array<string> */
    public static function enabled(): array
    {
        return collect(config('domains', []))
            ->filter(fn (array $config): bool => $config['enabled'] ?? false)
            ->keys()
            ->all();
    }
}
```

**What disabling does:** Only the Filament UI is hidden (resources, pages, widgets). Migrations still run (database schema stays consistent across all customers). Event listeners still function (background processes work). To fully disable backend behavior in a listener, check `DomainRegistry::isEnabled()` explicitly.

### Removed from Codebase

The following will be **deleted** (not modularized):

| Models | Reason |
|---|---|
| `Order`, `OrderItem` | Not a reservation concern |
| `Product`, `Category` | Not a reservation concern |
| `Project`, `Ticket`, `Comment` | Not a reservation concern |

Associated Filament resources, factories, seeders, migrations, and enums (`OrderStatus`, `ProductStatus`, `ProjectStatus`, `TicketStatus`, `TicketPriority`) will also be removed.

---

## 3. Domain Internal Structure

Every domain follows this exact layout. No exceptions.

```
app/Domain/{DomainName}/
  {DomainName}Plugin.php          # Filament Plugin (implements Filament\Contracts\Plugin)
  {DomainName}ServiceProvider.php # Laravel ServiceProvider (event listeners, bindings, migrations)
  Config/
    {domain-name}.php             # Domain-specific configuration
  Contracts/                      # Interfaces for cross-domain use
  Models/                         # Eloquent models
  Enums/                          # Domain-specific enums
  Services/                       # Business logic classes
  Actions/                        # Single-purpose action classes
  Events/                         # Domain events
  Listeners/                      # Event listeners
  Filament/
    Resources/                    # Filament resources (+ Pages/, Schemas/, Tables/ subdirs)
    Pages/                        # Filament custom pages
    Widgets/                      # Filament widgets
  database/
    migrations/                   # Domain migrations (loaded via ServiceProvider)
    factories/                    # Model factories
    seeders/                      # Domain seeders
```

### Rules

- **Only create directories that have files.** Don't scaffold empty folders.
- **ServiceProvider** handles: migration loading (`loadMigrationsFrom`), event listener registration, service container bindings, config merging.
- **Plugin** handles: Filament-specific registration (resources, pages, widgets, navigation, render hooks).
- A domain MAY have a ServiceProvider without a Plugin (e.g., `Shared`).
- A domain MUST have a Plugin if it has any Filament components.

---

## 4. Filament Plugin Convention

Each domain's Plugin implements `Filament\Contracts\Plugin`:

```php
<?php

namespace App\Domain\IndividualBooking;

use App\Domain\Shared\DomainRegistry;
use Filament\Contracts\Plugin;
use Filament\Panel;

class IndividualBookingPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'individual-booking';
    }

    public function register(Panel $panel): void
    {
        if (! DomainRegistry::isEnabled('individual-booking')) {
            return;
        }

        $panel
            ->discoverResources(
                in: __DIR__ . '/Filament/Resources',
                for: 'App\Domain\IndividualBooking\Filament\Resources',
            )
            ->discoverPages(
                in: __DIR__ . '/Filament/Pages',
                for: 'App\Domain\IndividualBooking\Filament\Pages',
            )
            ->discoverWidgets(
                in: __DIR__ . '/Filament/Widgets',
                for: 'App\Domain\IndividualBooking\Filament\Widgets',
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
```

### AdminPanelProvider Registration

```php
->plugins([
    CalMePlugin::make(),
    FilamentLanguageSwitcherPlugin::make()->locales([...])->rememberLocale()->showOnAuthPages(),

    // Domain plugins
    \App\Domain\Booking\BookingPlugin::make(),
    \App\Domain\IndividualBooking\IndividualBookingPlugin::make(),
    \App\Domain\ClassBooking\ClassBookingPlugin::make(),
    \App\Domain\PlaceBooking\PlaceBookingPlugin::make(),
    \App\Domain\Finance\FinancePlugin::make(),
    \App\Domain\Notification\NotificationPlugin::make(),
])
```

All domain plugins are always registered — **never comment them out**. To disable a domain, set `enabled => false` in `config/domains.php`. The plugin's `register()` method returns early, so no Filament components are discovered. The ServiceProvider still boots (migrations run, events fire).

### Navigation Groups

Updated navigation groups (Shop and Support removed):

```php
->navigationGroups([
    NavigationGroup::make(fn (): string => __('filament/navigation.groups.calendars')),
    NavigationGroup::make(fn (): string => __('filament/navigation.groups.reservations')),
    NavigationGroup::make(fn (): string => __('filament/navigation.groups.crm')),
    NavigationGroup::make(fn (): string => __('filament/navigation.groups.finance')),
])
```

---

## 5. Service Provider Convention

ServiceProviders **always boot** regardless of the domain's enabled state. This ensures database schema stays consistent and event listeners remain active. Only the Plugin checks `DomainRegistry::isEnabled()`.

Each domain's ServiceProvider extends `Illuminate\Support\ServiceProvider`:

```php
<?php

namespace App\Domain\IndividualBooking;

use Illuminate\Support\ServiceProvider;

class IndividualBookingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/individual-booking.php', 'individual-booking');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Event listeners
        // Service container bindings
    }
}
```

Register all domain ServiceProviders in `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\FortifyServiceProvider::class,

    // Domain providers
    App\Domain\Shared\SharedServiceProvider::class,
    App\Domain\Booking\BookingServiceProvider::class,
    App\Domain\IndividualBooking\IndividualBookingServiceProvider::class,
    App\Domain\ClassBooking\ClassBookingServiceProvider::class,
    App\Domain\PlaceBooking\PlaceBookingServiceProvider::class,
    App\Domain\Finance\FinanceServiceProvider::class,
    App\Domain\Notification\NotificationServiceProvider::class,
];
```

---

## 6. Model Conventions

### Base Pattern

All models extend `Illuminate\Database\Eloquent\Model` directly (no custom base class).

```php
<?php

namespace App\Domain\IndividualBooking\Models;

use App\Domain\Shared\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    /** @use HasFactory<\App\Domain\IndividualBooking\Database\Factories\StaffFactory> */
    use BelongsToCompany;
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        // ...
    ];

    protected function casts(): array
    {
        return [
            'role' => StaffRole::class,
        ];
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(StaffSchedule::class);
    }
}
```

### Rules

- Always use `casts()` method, never `$casts` property.
- Always declare `$fillable` explicitly.
- Always use return type declarations on relationships.
- Use `/** @use HasFactory<FactoryClass> */` PHPDoc on the trait.
- Factory classes live in the domain's `database/factories/` directory.

### Factory Namespace

Factories need a custom `newFactory()` method since they're not in the default location:

```php
protected static function newFactory(): StaffFactory
{
    return StaffFactory::new();
}
```

---

## 7. Multi-Tenancy — BelongsToCompany

### The Trait

Lives in `App\Domain\Shared\Concerns\BelongsToCompany`:

```php
<?php

namespace App\Domain\Shared\Concerns;

use App\Domain\Shared\Models\Company;
use App\Domain\Shared\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCompany
{
    public static function bootBelongsToCompany(): void
    {
        static::addGlobalScope(new CompanyScope());

        static::creating(function ($model) {
            if (! $model->company_id && $company = static::resolveCurrentCompany()) {
                $model->company_id = $company->id;
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    protected static function resolveCurrentCompany(): ?Company
    {
        // Resolve from authenticated user's company or session
        // Implementation depends on auth setup
        return null;
    }
}
```

### The Global Scope

Lives in `App\Domain\Shared\Scopes\CompanyScope`:

```php
<?php

namespace App\Domain\Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if ($companyId = static::resolveCompanyId()) {
            $builder->where($model->getTable() . '.company_id', $companyId);
        }
    }

    protected static function resolveCompanyId(): ?int
    {
        // Resolve from auth context
        return null;
    }
}
```

### Models That Use It

| Domain | Models |
|---|---|
| Shared | Company (owns, does NOT use trait) |
| Booking | Reservation |
| IndividualBooking | Staff, StaffSchedule, StaffBreak |
| ClassBooking | ClassDefinition, ClassSession |
| PlaceBooking | Place, PlaceSchedule |
| Finance | Payment |

### Bypassing the Scope

When you need all records regardless of company (e.g., admin super-panel, migrations, seeders):

```php
Staff::withoutGlobalScope(CompanyScope::class)->get();
```

---

## 8. Cross-Domain Communication

### Allowed

1. **Events** — Domain A fires an event, Domain B listens.
2. **Shared Contracts** — Domains depend on interfaces from `Shared`, not concrete classes.
3. **Service Container** — Laravel DI resolves implementations.

### Forbidden

1. **Direct model imports across sibling domains** — `IndividualBooking` must NOT import `Finance\Models\Payment` directly.
2. **Direct service calls across sibling domains** — Use events or contracts.
3. **Shared database queries** — Each domain queries only its own tables. Cross-domain data flows through events carrying DTOs.

### Exception: Shared Domain

All domains MAY import from `Shared`. This is the only allowed cross-domain import.

### Event Pattern

```php
// In Booking domain — fires event
namespace App\Domain\Booking\Events;

class ReservationCreated
{
    public function __construct(
        public readonly int $reservationId,
        public readonly string $type,
        public readonly int $customerId,
        public readonly int $companyId,
        public readonly float $amount,
    ) {}
}

// In Finance domain — listens
namespace App\Domain\Finance\Listeners;

use App\Domain\Booking\Events\ReservationCreated;

class CreatePendingPayment
{
    public function handle(ReservationCreated $event): void
    {
        // Create payment record using event data (not by importing Reservation model)
    }
}
```

> **Note:** Event classes live in the domain that fires them. Listener classes live in the domain that handles them. The firing domain's events are the one allowed cross-domain import exception besides `Shared`.

### Contracts

Contracts live in `App\Domain\Shared\Contracts\`:

```php
interface Reservable
{
    public function getReservationType(): ReservationType;
    public function getDurationMinutes(): int;
    public function getPriceAmount(): float;
}

interface HasAvailability
{
    public function isAvailableAt(CarbonImmutable $dateTime, int $durationMinutes): bool;
}

interface Payable
{
    public function getPayableAmount(): float;
    public function getPayableDescription(): string;
}
```

Keep contracts **thin**. If a method doesn't apply to all implementors, it doesn't belong in the contract.

---

## 9. Migration Conventions

### Location

Each domain stores migrations in its own `database/migrations/` directory, loaded by its ServiceProvider via `$this->loadMigrationsFrom()`.

### Naming

Prefix migration filenames with the domain name for clarity in `php artisan migrate:status`:

```
app/Domain/Booking/database/migrations/
  2026_03_15_000001_booking_create_reservations_table.php
  2026_03_15_000002_booking_create_availability_rules_table.php
  2026_03_15_000003_booking_create_blocked_slots_table.php

app/Domain/IndividualBooking/database/migrations/
  2026_03_15_000001_individual_booking_create_staff_table.php
  2026_03_15_000002_individual_booking_create_staff_schedules_table.php
```

### Rules

- Domain migrations may only create/modify tables owned by that domain.
- Foreign keys referencing tables in other domains are allowed (database-level integrity).
- When modifying an existing column, include ALL original column attributes to prevent data loss.
- Use `decimal(12, 2)` for all currency fields.
- Use `foreignId()->constrained()->cascadeOnDelete()` for parent relationships.
- Polymorphic columns: `morphs('reservable')` for type + id.

### Existing Migrations

The current `database/migrations/` directory contains the original migrations. These will be:
1. **Kept in place** for tables that remain (users, companies, customers).
2. **Moved** to the appropriate domain for domain-owned tables.
3. **Deleted** for removed features (orders, products, categories, projects, tickets, comments).

---

## 10. Testing Conventions

### Structure

Tests mirror the domain structure:

```
tests/
  Feature/
    Domain/
      Shared/
      Booking/
      IndividualBooking/
      ClassBooking/
      PlaceBooking/
      Finance/
      Notification/
  Unit/
    Domain/
      Booking/
        AvailabilityCalculatorTest.php
        ConflictDetectorTest.php
```

### Rules

- Use Pest (not PHPUnit directly).
- Create tests with `php artisan make:test --pest {path}`.
- Feature tests for Filament resources use `Livewire::test()` or `livewire()`.
- Unit tests for services (AvailabilityCalculator, ConflictDetector, etc.).
- Always use model factories — never manually set attributes that a factory handles.
- Run with `php artisan test --compact --filter=DomainName` during development.
- Every domain must have tests before it's considered complete.

### Factory Location

Factories live in their domain:

```php
namespace App\Domain\IndividualBooking\Database\Factories;

use App\Domain\IndividualBooking\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Staff> */
class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->name(),
            // ...
        ];
    }
}
```

---

## 11. Enum Conventions

- Enums live in their domain's `Enums/` directory.
- All enums implement Filament contracts: `HasColor`, `HasIcon`, `HasLabel`.
- Enum keys use `TitleCase` (e.g., `Confirmed`, `NoShow`, `CheckedIn`).
- Labels use translation keys: `__('filament/enums.reservation_status.confirmed')`.

### Shared Enums (in `Shared/Enums/`)

- `ReservationType` — Staff, Class, Place (extensible for Phase 2: Equipment, etc.)

### Domain Enums (stay in their domain)

- `ReservationStatus` — Booking domain
- `PaymentStatus`, `PaymentMethod` — Finance domain
- `StaffRole` — IndividualBooking domain
- `ClassSessionStatus` — ClassBooking domain
- `EnrollmentStatus` — ClassBooking domain

---

## 12. Calendar Integration

### Strategy: Unified Calendar with Type Filters

One CalMe calendar page shows all booking types. Filterable by:
- Reservation type (Individual, Class, Place)
- Staff member
- Place/room
- Status

### Implementation

The existing `CalendarWidget`, `StaffCalendarWidget`, and `AvatarCalendarWidget` will be moved to the `Booking` domain since they display cross-domain data (reservations of all types).

Calendar pages move to `Booking/Filament/Pages/`.

Each booking domain provides a **calendar data provider** that the unified calendar aggregates:

```php
// In Shared contracts
interface CalendarDataProvider
{
    /** @return Collection<int, CalendarEvent> */
    public function getEvents(Carbon $start, Carbon $end, array $filters = []): Collection;
    public function getEventColor(): string;
    public function getReservationType(): ReservationType;
}
```

Each booking domain implements this contract. The unified calendar widget collects from all registered providers.

---

## 13. Naming Conventions

| Thing | Convention | Example |
|---|---|---|
| Domain folder | `PascalCase` | `IndividualBooking` |
| Plugin class | `{Domain}Plugin` | `IndividualBookingPlugin` |
| ServiceProvider | `{Domain}ServiceProvider` | `IndividualBookingServiceProvider` |
| Config file | `kebab-case.php` | `individual-booking.php` |
| Migration prefix | `snake_case` domain | `individual_booking_create_staff_table` |
| Event class | Past tense verb | `ReservationCreated`, `EnrollmentCancelled` |
| Listener class | Present tense action | `CreatePendingPayment`, `SendConfirmation` |
| Service class | `{Noun}Service` | `AvailabilityCalculator`, `ConflictDetector` |
| Action class | `{Verb}{Noun}` | `CreateReservation`, `CancelEnrollment` |
| Contract/Interface | Adjective or `Has{Noun}` | `Reservable`, `HasAvailability`, `Payable` |
| Filament Resource | `{Model}Resource` | `StaffResource`, `ClassDefinitionResource` |
| Factory class | `{Model}Factory` | `StaffFactory` |

---

## 14. Dependency Rules (Import Matrix)

```
Shared ← can be imported by ALL domains
Booking ← can import: Shared
IndividualBooking ← can import: Shared, Booking (events only)
ClassBooking ← can import: Shared, Booking (events only)
PlaceBooking ← can import: Shared, Booking (events only)
Finance ← can import: Shared, Booking (events only)
Notification ← can import: Shared, Booking (events only)
```

**Booking domain events** are the bridge. All side-effect domains listen to Booking events.

---

## 15. Config Convention

Each domain has a config file in its `Config/` directory:

The `enabled` flag lives **only** in `config/domains.php` (see Section 2). Domain-specific configs contain only behavioral settings:

```php
// app/Domain/IndividualBooking/Config/individual-booking.php
return [
    'default_appointment_duration' => 60, // minutes
    'allow_overlapping_staff_bookings' => false,
    'cancellation_policy' => [
        'allowed_before_hours' => 24,
        'refund_percentage' => 100,
    ],
];
```

Access via `config('individual-booking.default_appointment_duration')`.

---

## 16. Code to Remove

Before modularizing, these will be deleted entirely:

### Models & Related
- `App\Models\Order` + `OrderResource` + `OrderFactory` + migration
- `App\Models\OrderItem` + `OrderItemFactory` + migration
- `App\Models\Product` + `ProductResource` + `ProductFactory` + migration
- `App\Models\Category` + `CategoryFactory` + migration
- `App\Models\Project` + `ProjectResource` + `ProjectFactory` + migration
- `App\Models\Ticket` + `TicketResource` + `TicketFactory` + migration
- `App\Models\Comment` + `CommentFactory` + migration

### Enums
- `OrderStatus`
- `ProductStatus`
- `ProjectStatus`
- `TicketStatus`
- `TicketPriority`

### Filament Widgets
- `OrdersByStatusChart`
- `LatestOrdersWidget`

### Seeders
- Any seeders referencing removed models

### Navigation Groups
- Remove `shop` and `support` groups from translations and `AdminPanelProvider`

### Database
- Drop tables: `orders`, `order_items`, `products`, `categories`, `projects`, `tickets`, `comments`

---

## 17. Migration Path — Model Relocation

| Current Location | New Location |
|---|---|
| `app/Models/Company.php` | `app/Domain/Shared/Models/Company.php` |
| `app/Models/Customer.php` | `app/Domain/Shared/Models/Customer.php` |
| `app/Models/User.php` | `app/Domain/Shared/Models/User.php` |
| `app/Models/Service.php` | `app/Domain/Shared/Models/Service.php` |
| `app/Models/Review.php` | `app/Domain/Shared/Models/Review.php` |
| `app/Models/Reservation.php` | `app/Domain/Booking/Models/Reservation.php` |
| `app/Models/Staff.php` | `app/Domain/IndividualBooking/Models/Staff.php` |
| `app/Models/Place.php` | `app/Domain/PlaceBooking/Models/Place.php` |
| `app/Models/Payment.php` | `app/Domain/Finance/Models/Payment.php` |
| `app/Enums/ReservationStatus.php` | `app/Domain/Booking/Enums/ReservationStatus.php` |
| `app/Enums/PaymentStatus.php` | `app/Domain/Finance/Enums/PaymentStatus.php` |
| `app/Enums/PaymentMethod.php` | `app/Domain/Finance/Enums/PaymentMethod.php` |
| `app/Enums/StaffRole.php` | `app/Domain/IndividualBooking/Enums/StaffRole.php` |

---

## 18. Implementation Phases

### Phase 1: Foundation (Current Scope)

1. **Cleanup** — Remove Shop & Support code
2. **Scaffold** — Create domain directory structure
3. **Shared** — Move shared models, create contracts, traits (BelongsToCompany)
4. **Booking** — Move Reservation, build availability engine, events
5. **IndividualBooking** — Move Staff, add scheduling models
6. **ClassBooking** — New models (ClassDefinition, ClassSession, ClassEnrollment)
7. **PlaceBooking** — Move Place, add scheduling models
8. **Finance** — Move Payment, add Refund, polymorphic payable
9. **Notification** — Event listeners, templates
10. **Calendar** — Unified calendar with type filters
11. **Wire** — Update AdminPanelProvider, providers, test suite
12. **Test** — Full test coverage per domain

### Phase 2: Extended Features

- EquipmentBooking domain
- Subscription/Package domain
- Waitlist domain
- Reporting/Analytics domain

### Phase 3: Customer-Facing

- Loyalty/Points domain
- Invoicing domain
- CustomerPortal (separate Filament panel or Livewire frontend)
