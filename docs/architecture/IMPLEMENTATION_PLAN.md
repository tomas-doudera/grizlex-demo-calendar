# Implementation Plan — Phase 1: Foundation

> **Prerequisite:** Architecture guidelines approved (`docs/architecture/GUIDELINES.md`).
> **Branch:** `feature/modular-architecture` (from `modular-base`)

---

## Step 1: Cleanup — Remove Shop & Support

**Goal:** Strip out all code unrelated to the reservation platform.

### Delete Files

**Models:**
- `app/Models/Order.php`
- `app/Models/OrderItem.php`
- `app/Models/Product.php`
- `app/Models/Category.php`
- `app/Models/Project.php`
- `app/Models/Ticket.php`
- `app/Models/Comment.php`

**Enums:**
- `app/Enums/OrderStatus.php`
- `app/Enums/ProductStatus.php`
- `app/Enums/ProjectStatus.php`
- `app/Enums/TicketStatus.php`
- `app/Enums/TicketPriority.php`

**Filament Resources:**
- `app/Filament/Resources/Order/` (entire directory)
- `app/Filament/Resources/Product/` (entire directory)
- `app/Filament/Resources/Project/` (entire directory)
- `app/Filament/Resources/Ticket/` (entire directory)

**Filament Widgets:**
- `app/Filament/Widgets/OrdersByStatusChart.php`
- `app/Filament/Widgets/LatestOrdersWidget.php`

**Factories:**
- `database/factories/OrderFactory.php`
- `database/factories/OrderItemFactory.php`
- `database/factories/ProductFactory.php`
- `database/factories/CategoryFactory.php`
- `database/factories/ProjectFactory.php`
- `database/factories/TicketFactory.php`
- `database/factories/CommentFactory.php`

**Migrations:**
- Migration creating `orders` table
- Migration creating `order_items` table
- Migration creating `products` table
- Migration creating `categories` table
- Migration creating `projects` table
- Migration creating `tickets` table
- Migration creating `comments` table

### Modify Files

- `AdminPanelProvider.php` — Remove `shop` and `support` navigation groups
- `DatabaseSeeder.php` / `ShowcaseSeeder.php` — Remove references to deleted models
- Translation files — Remove shop/support translation keys
- `StatsOverview.php` widget — Remove any references to orders/products

### Verification

- `php artisan test --compact` — all remaining tests pass
- `vendor/bin/pint --dirty --format agent` — code style clean

---

## Step 2: Scaffold Domain Structure

**Goal:** Create the directory layout and base infrastructure.

### Create Directories

```
app/Domain/
  Shared/
    Concerns/
    Contracts/
    Enums/
    Models/
    Scopes/
    SharedServiceProvider.php
  Booking/
    Config/
    Enums/
    Events/
    Listeners/
    Models/
    Services/
    Filament/
      Resources/
      Pages/
      Widgets/
    database/
      migrations/
      factories/
    BookingPlugin.php
    BookingServiceProvider.php
  IndividualBooking/
    Config/
    Enums/
    Models/
    Services/
    Filament/
      Resources/
      Pages/
    database/
      migrations/
      factories/
    IndividualBookingPlugin.php
    IndividualBookingServiceProvider.php
  ClassBooking/
    Config/
    Enums/
    Models/
    Services/
    Filament/
      Resources/
    database/
      migrations/
      factories/
    ClassBookingPlugin.php
    ClassBookingServiceProvider.php
  PlaceBooking/
    Config/
    Enums/
    Models/
    Services/
    Filament/
      Resources/
      Pages/
    database/
      migrations/
      factories/
    PlaceBookingPlugin.php
    PlaceBookingServiceProvider.php
  Finance/
    Config/
    Enums/
    Models/
    Services/
    Filament/
      Resources/
      Widgets/
    database/
      migrations/
      factories/
    FinancePlugin.php
    FinanceServiceProvider.php
  Notification/
    Config/
    Events/
    Listeners/
    Models/
    Services/
    Filament/
      Resources/
    database/
      migrations/
      factories/
    NotificationPlugin.php
    NotificationServiceProvider.php
```

### Create Base Files

1. **`config/domains.php`** — Master domain toggle config (all enabled by default)
2. **`DomainRegistry`** — `app/Domain/Shared/DomainRegistry.php` — `isEnabled()`, `enabled()` helper
3. **`SharedServiceProvider`** — Registers shared scopes, merges config
4. **Each `{Domain}ServiceProvider`** — Loads migrations, registers listeners (always boots, no enabled check)
5. **Each `{Domain}Plugin`** — Discovers Filament resources/pages/widgets (checks `DomainRegistry::isEnabled()` before registering)
6. **Each `Config/{domain}.php`** — Behavioral config values (no `enabled` key — that lives in `config/domains.php`)
7. **Update `bootstrap/providers.php`** — Register all ServiceProviders

### Create Shared Infrastructure

1. **`BelongsToCompany` trait** — `app/Domain/Shared/Concerns/BelongsToCompany.php`
2. **`CompanyScope` global scope** — `app/Domain/Shared/Scopes/CompanyScope.php`
3. **Contracts** — `Reservable`, `HasAvailability`, `Payable`, `CalendarDataProvider`
4. **`ReservationType` enum** — `app/Domain/Shared/Enums/ReservationType.php`

### Verification

- Application boots without errors
- `php artisan migrate:status` shows all migrations
- Admin panel loads (even if resources are temporarily missing)

---

## Step 3: Shared Domain — Move Core Models

**Goal:** Relocate models shared across all domains.

### Move Models

| From | To |
|---|---|
| `app/Models/Company.php` | `app/Domain/Shared/Models/Company.php` |
| `app/Models/Customer.php` | `app/Domain/Shared/Models/Customer.php` |
| `app/Models/User.php` | `app/Domain/Shared/Models/User.php` |
| `app/Models/Service.php` | `app/Domain/Shared/Models/Service.php` |
| `app/Models/Review.php` | `app/Domain/Shared/Models/Review.php` |

### Move Factories

| From | To |
|---|---|
| `database/factories/CompanyFactory.php` | `app/Domain/Shared/database/factories/CompanyFactory.php` |
| `database/factories/CustomerFactory.php` | `app/Domain/Shared/database/factories/CustomerFactory.php` |
| `database/factories/UserFactory.php` | `app/Domain/Shared/database/factories/UserFactory.php` |
| `database/factories/ServiceFactory.php` | `app/Domain/Shared/database/factories/ServiceFactory.php` |
| `database/factories/ReviewFactory.php` | `app/Domain/Shared/database/factories/ReviewFactory.php` |

### Move Migrations

Move migrations for `companies`, `customers`, `services`, `reviews` tables to `app/Domain/Shared/database/migrations/`. Keep `users` table migration in `database/migrations/` (Laravel convention for auth).

### Update Namespaces

- Update all `use App\Models\Company` → `use App\Domain\Shared\Models\Company` across entire codebase
- Update all `use App\Models\Customer` → `use App\Domain\Shared\Models\Customer`
- Same for User, Service, Review
- Update factory `$model` properties and `newFactory()` overrides

### Move Filament Resources

| From | To |
|---|---|
| `app/Filament/Resources/Company/` | `app/Domain/Shared/Filament/Resources/Company/` |
| `app/Filament/Resources/Customer/` | `app/Domain/Shared/Filament/Resources/Customer/` |
| `app/Filament/Resources/Service/` | `app/Domain/Shared/Filament/Resources/Service/` |
| `app/Filament/Resources/Review/` | `app/Domain/Shared/Filament/Resources/Review/` |

Create `SharedPlugin` to register these Filament resources.

### Apply BelongsToCompany

Add `BelongsToCompany` trait to: Company (as owner, with different behavior), Service.

### Verification

- `php artisan test --compact` — all tests pass
- All Filament resources load correctly
- Factory model resolution works

---

## Step 4: Booking Domain — Reservation Engine

**Goal:** Move Reservation model, build the core scheduling engine.

### Move Models

| From | To |
|---|---|
| `app/Models/Reservation.php` | `app/Domain/Booking/Models/Reservation.php` |

### Move Enums

| From | To |
|---|---|
| `app/Enums/ReservationStatus.php` | `app/Domain/Booking/Enums/ReservationStatus.php` |

### Enhance Reservation Model

Add columns via migration:
- `type` — `ReservationType` enum (staff, class, place)
- `reservable_type` — nullable string (polymorphic)
- `reservable_id` — nullable bigint (polymorphic)
- `checked_in_at` — nullable timestamp
- `cancelled_at` — nullable timestamp
- `cancellation_reason` — nullable text

### Create New Models

- **`AvailabilityRule`** — Defines when something is available
  - Columns: `available_type`, `available_id`, `day_of_week`, `start_time`, `end_time`, `effective_from`, `effective_until`
- **`BlockedSlot`** — Explicit unavailability
  - Columns: `blockable_type`, `blockable_id`, `start_at`, `end_at`, `reason`

### Create Services

- **`AvailabilityCalculator`** — Given a reservable + date range → available time slots
- **`ConflictDetector`** — Check if a new reservation overlaps with existing ones
- **`ReservationService`** — Orchestrates create, update, cancel flows

### Create Events

- `ReservationCreated`
- `ReservationConfirmed`
- `ReservationCancelled`
- `ReservationCompleted`
- `ReservationNoShow`

Each event carries: `reservationId`, `type`, `customerId`, `companyId`, `amount`.

### Move Filament Resources

| From | To |
|---|---|
| `app/Filament/Resources/Reservation/` | `app/Domain/Booking/Filament/Resources/Reservation/` |

Add type filter to ReservationResource table.

### Move Calendar

| From | To |
|---|---|
| `app/Filament/Pages/Calendar.php` | `app/Domain/Booking/Filament/Pages/Calendar.php` |
| `app/Filament/Pages/StaffCalendar.php` | `app/Domain/Booking/Filament/Pages/StaffCalendar.php` |
| `app/Filament/Pages/AvatarCalendar.php` | `app/Domain/Booking/Filament/Pages/AvatarCalendar.php` |
| `app/Filament/Widgets/CalendarWidget.php` | `app/Domain/Booking/Filament/Widgets/CalendarWidget.php` |
| `app/Filament/Widgets/StaffCalendarWidget.php` | `app/Domain/Booking/Filament/Widgets/StaffCalendarWidget.php` |
| `app/Filament/Widgets/AvatarCalendarWidget.php` | `app/Domain/Booking/Filament/Widgets/AvatarCalendarWidget.php` |

### Create Config

```php
// app/Domain/Booking/Config/booking.php
return [
    'default_slot_duration' => 60,
    'max_advance_booking_days' => 90,
    'allow_past_bookings' => false,
    'cancellation_policy' => [
        'allowed_before_hours' => 24,
    ],
];
```

### Verification

- Reservation CRUD works in Filament
- Calendar pages render
- AvailabilityCalculator unit tests pass
- ConflictDetector unit tests pass

---

## Step 5: IndividualBooking Domain — 1:1 Appointments

**Goal:** Staff booking with schedule management.

### Move Models

| From | To |
|---|---|
| `app/Models/Staff.php` | `app/Domain/IndividualBooking/Models/Staff.php` |

### Move Enums

| From | To |
|---|---|
| `app/Enums/StaffRole.php` | `app/Domain/IndividualBooking/Enums/StaffRole.php` |

### Create New Models

- **`StaffSchedule`** — Working hours per day of week
  - Columns: `staff_id`, `day_of_week`, `start_time`, `end_time`, `is_active`
- **`StaffBreak`** — Breaks and time off
  - Columns: `staff_id`, `date`, `start_time`, `end_time`, `reason`

### Create Services

- **`StaffAvailabilityService`** — Calculates staff availability considering schedule, breaks, existing bookings
  - Uses `Booking\Services\AvailabilityCalculator` internally (via contract)

### Implement Contracts

- `Staff` implements `Reservable` and `HasAvailability`

### Move Filament Resources

| From | To |
|---|---|
| `app/Filament/Resources/Staff/` | `app/Domain/IndividualBooking/Filament/Resources/Staff/` |

### Create New Filament Resources

- **`StaffScheduleResource`** — Manage working hours per staff member

### Create Config

```php
// app/Domain/IndividualBooking/Config/individual-booking.php
return [
    'default_appointment_duration' => 60,
    'buffer_between_appointments' => 15,
    'allow_overlapping' => false,
];
```

### Verification

- Staff CRUD works
- StaffSchedule CRUD works
- StaffAvailabilityService correctly calculates available slots
- Staff appears in unified calendar

---

## Step 6: ClassBooking Domain — Group Classes

**Goal:** Group class definitions, sessions, and enrollment.

### Create New Models

- **`ClassDefinition`** — Template for a class
  - Columns: `company_id`, `name`, `description`, `default_duration_minutes`, `default_capacity`, `service_id` (FK to Shared\Service), `color`, `is_active`
- **`ClassSession`** — Specific occurrence of a class
  - Columns: `class_definition_id`, `staff_id`, `place_id`, `starts_at`, `ends_at`, `capacity`, `status` (ClassSessionStatus enum), `notes`
- **`ClassEnrollment`** — Customer enrollment in a session
  - Columns: `class_session_id`, `customer_id`, `reservation_id` (FK to Booking\Reservation), `status` (EnrollmentStatus enum), `enrolled_at`, `cancelled_at`

### Create Enums

- **`ClassSessionStatus`** — Scheduled, InProgress, Completed, Cancelled
- **`EnrollmentStatus`** — Enrolled, Waitlisted, Cancelled, Attended, NoShow

### Create Services

- **`EnrollmentService`** — Handle sign-up, capacity checks, waitlist promotion
  - Fires `ReservationCreated` when enrollment creates a booking
- **`ClassSessionGenerator`** — Generate ClassSession records from a recurring schedule

### Create Filament Resources

- **`ClassDefinitionResource`** — Manage class templates
- **`ClassSessionResource`** — Manage specific sessions, view enrollment list

### Implement Contracts

- `ClassSession` implements `Reservable`

### Create Config

```php
// app/Domain/ClassBooking/Config/class-booking.php
return [
    'default_capacity' => 20,
    'allow_waitlist' => true,
    'waitlist_max_size' => 5,
    'auto_promote_from_waitlist' => true,
];
```

### Verification

- ClassDefinition CRUD works
- ClassSession CRUD works
- Enrollment flow works (enroll, cancel, waitlist)
- Capacity limits enforced
- Class sessions appear in unified calendar

---

## Step 7: PlaceBooking Domain — Facility Reservations

**Goal:** Room, court, and facility booking with schedules.

### Move Models

| From | To |
|---|---|
| `app/Models/Place.php` | `app/Domain/PlaceBooking/Models/Place.php` |

### Enhance Place Model

Add columns via migration:
- `min_booking_minutes` — minimum booking duration
- `max_booking_minutes` — maximum booking duration
- `booking_interval_minutes` — slot grid interval (15, 30, 60)
- `requires_approval` — boolean

### Create New Models

- **`PlaceSchedule`** — Operating hours per day of week
  - Columns: `place_id`, `day_of_week`, `start_time`, `end_time`, `is_active`

### Create Services

- **`PlaceAvailabilityService`** — Available time slots for a place on a given date
- **`PlaceBookingService`** — Create place reservations, enforce min/max duration

### Implement Contracts

- `Place` implements `Reservable` and `HasAvailability`

### Move Filament Resources

| From | To |
|---|---|
| `app/Filament/Resources/Place/` | `app/Domain/PlaceBooking/Filament/Resources/Place/` |

### Create New Filament Resources

- **`PlaceScheduleResource`** — Manage place operating hours

### Create Config

```php
// app/Domain/PlaceBooking/Config/place-booking.php
return [
    'default_booking_interval' => 60,
    'min_booking_duration' => 30,
    'max_booking_duration' => 480,
    'require_approval_by_default' => false,
];
```

### Verification

- Place CRUD works with new booking fields
- PlaceSchedule management works
- PlaceAvailabilityService correctly calculates slots
- Places appear in unified calendar

---

## Step 8: Finance Domain — Payments & Refunds

**Goal:** Polymorphic payment system with refund support.

### Move Models

| From | To |
|---|---|
| `app/Models/Payment.php` | `app/Domain/Finance/Models/Payment.php` |

### Move Enums

| From | To |
|---|---|
| `app/Enums/PaymentStatus.php` | `app/Domain/Finance/Enums/PaymentStatus.php` |
| `app/Enums/PaymentMethod.php` | `app/Domain/Finance/Enums/PaymentMethod.php` |

### Enhance Payment Model

Add columns via migration:
- `payable_type` — nullable string (polymorphic, replaces direct reservation_id)
- `payable_id` — nullable bigint
- `refunded_amount` — decimal(12,2), default 0
- `refunded_at` — nullable timestamp
- `gateway` — nullable string
- `gateway_reference` — nullable string

### Create New Models

- **`Refund`** — Partial/full refund records
  - Columns: `payment_id`, `amount`, `reason`, `refunded_at`, `processed_by`

### Create Contracts

- **`PaymentGateway`** interface — `charge(Payable, PaymentMethod): PaymentResult`, `refund(Payment, float): RefundResult`

### Create Services

- **`PaymentService`** — Orchestrates payment flow
- **`RefundService`** — Handles partial/full refunds

### Create Listeners

- **`CreatePendingPayment`** — Listens to `ReservationCreated` event

### Move Filament Resources

| From | To |
|---|---|
| `app/Filament/Resources/Payment/` | `app/Domain/Finance/Filament/Resources/Payment/` |

### Move Widgets

| From | To |
|---|---|
| `app/Filament/Widgets/RevenueChart.php` | `app/Domain/Finance/Filament/Widgets/RevenueChart.php` |

### Create Config

```php
// app/Domain/Finance/Config/finance.php
return [
    'default_currency' => 'CZK',
    'default_payment_method' => 'cash',
    'auto_create_payment_on_booking' => true,
];
```

### Verification

- Payment CRUD works
- Polymorphic payable resolves correctly
- Refund flow works
- Revenue widget renders
- Payment auto-created on reservation (via event)

---

## Step 9: Notification Domain — Lifecycle Communications

**Goal:** Automated notifications for booking events.

### Create Models

- **`NotificationTemplate`** — Customizable templates per company
  - Columns: `company_id`, `type`, `channel` (email/sms), `subject`, `body`, `is_active`
- **`NotificationLog`** — Sent notification history
  - Columns: `notifiable_type`, `notifiable_id`, `channel`, `type`, `sent_at`, `status`

### Create Notification Classes

Using Laravel's built-in notification system:
- `ReservationConfirmationNotification`
- `ReservationReminderNotification`
- `ReservationCancelledNotification`
- `ClassEnrollmentConfirmation`

### Create Services

- **`NotificationScheduler`** — Schedules reminder notifications (24h, 1h before)
- **`TemplateRenderer`** — Renders notification templates with reservation data

### Create Listeners

- **`SendReservationConfirmation`** — Listens to `ReservationCreated`
- **`SendCancellationNotice`** — Listens to `ReservationCancelled`

### Create Filament Resources

- **`NotificationTemplateResource`** — Manage notification templates

### Create Config

```php
// app/Domain/Notification/Config/notification.php
return [
    'channels' => ['mail'],
    'reminders' => [
        'enabled' => true,
        'before_hours' => [24, 1],
    ],
    'auto_confirm' => true,
];
```

### Verification

- NotificationTemplate CRUD works
- Notifications fire on reservation events
- NotificationLog records sent notifications

---

## Step 10: Wire Everything Together

**Goal:** Connect all domains and verify the complete system.

### Update AdminPanelProvider

- Register ALL domain plugins unconditionally (never comment out — config controls activation)
- Each plugin internally checks `DomainRegistry::isEnabled()` before discovering components
- Update navigation groups (remove shop/support, keep calendars/reservations/crm/finance)
- Filter navigation groups based on which domains are enabled (hide empty groups)
- Remove old `discoverResources/Pages/Widgets` calls (plugins handle this now)

### Update Seeders

- Create per-domain seeders
- `DatabaseSeeder` calls domain seeders in correct order
- Demo data covers: companies, customers, staff + schedules, places + schedules, class definitions + sessions, reservations of all types, payments

### Update Existing Tests

- Fix all namespace references
- Ensure auth tests still pass (User model moved)

### Final Cleanup

- Remove empty `app/Models/` directory (or keep just for future non-domain models)
- Remove empty `app/Filament/Resources/`, `app/Filament/Pages/`, `app/Filament/Widgets/`
- Remove old `app/Enums/` directory
- Run `vendor/bin/pint --format agent` on all PHP files

---

## Step 11: Test Suite

**Goal:** Comprehensive test coverage for every domain.

### Unit Tests

| Domain | Tests |
|---|---|
| Booking | `AvailabilityCalculatorTest`, `ConflictDetectorTest`, `ReservationServiceTest` |
| IndividualBooking | `StaffAvailabilityServiceTest` |
| ClassBooking | `EnrollmentServiceTest`, `ClassSessionGeneratorTest` |
| PlaceBooking | `PlaceAvailabilityServiceTest`, `PlaceBookingServiceTest` |
| Finance | `PaymentServiceTest`, `RefundServiceTest` |

### Feature Tests (Filament)

| Domain | Tests |
|---|---|
| Shared | `CompanyResourceTest`, `CustomerResourceTest`, `ServiceResourceTest` |
| Booking | `ReservationResourceTest`, `CalendarPageTest` |
| IndividualBooking | `StaffResourceTest`, `StaffScheduleResourceTest` |
| ClassBooking | `ClassDefinitionResourceTest`, `ClassSessionResourceTest` |
| PlaceBooking | `PlaceResourceTest`, `PlaceScheduleResourceTest` |
| Finance | `PaymentResourceTest`, `RefundTest` |
| Notification | `NotificationTemplateResourceTest` |

### Integration Tests

- **Book → Pay → Notify flow** — Create reservation, verify payment created, verify notification sent
- **Class enrollment flow** — Enroll customer, verify capacity decremented, verify reservation created
- **Cancellation flow** — Cancel reservation, verify refund created, verify notification sent
- **Availability calculation** — Create bookings, verify available slots exclude booked times

### Run

```bash
php artisan test --compact
```

All tests must pass before Phase 1 is considered complete.

---

## Execution Order Summary

| Step | Domain | Type | Estimated Complexity |
|---|---|---|---|
| 1 | — | Cleanup | Medium (many files to delete) |
| 2 | All | Scaffolding | Low (directory creation) |
| 3 | Shared | Move + Create | Medium (namespace updates) |
| 4 | Booking | Move + Create | High (new services, events) |
| 5 | IndividualBooking | Move + Create | Medium (new schedule models) |
| 6 | ClassBooking | Create (new) | High (entirely new domain) |
| 7 | PlaceBooking | Move + Create | Medium (new schedule models) |
| 8 | Finance | Move + Enhance | Medium (polymorphic, refunds) |
| 9 | Notification | Create (new) | Medium (event listeners) |
| 10 | — | Integration | Medium (wiring, seeders) |
| 11 | All | Testing | High (comprehensive coverage) |

---

## Definition of Done — Phase 1

- [ ] All Shop & Support code removed
- [ ] 7 domains created with correct structure
- [ ] All models relocated with updated namespaces
- [ ] BelongsToCompany trait applied with global scope
- [ ] Availability engine working (calculator, conflict detector)
- [ ] ClassBooking fully functional (definitions, sessions, enrollment)
- [ ] Unified calendar showing all reservation types
- [ ] Payments polymorphic with refund support
- [ ] Notifications firing on booking events
- [ ] AdminPanelProvider using domain plugins
- [ ] All tests passing
- [ ] `vendor/bin/pint` clean
