<?php

namespace App\Filament\Widgets;

use App\Enums\ReservationStatus;
use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;
use App\Settings\CalendarSettings;
use Carbon\CarbonInterface;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use TomasDoudera\CalMe\Widgets\CalMeWidget;

class CalendarWidget extends CalMeWidget
{
    protected static bool $isDiscovered = false;

    protected array $cellDimensions = [
        'week-vertical' => [
            'cell_width' => 150,
            'cell_height' => 75,
        ],
    ];

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->schema([
            DatePicker::make('date')
                ->label(__('filament/calendar.filters.date'))
                ->default(now())
                ->native(false)
                ->live(),

            Select::make('company_id')
                ->label(__('filament/calendar.filters.company'))
                ->default(fn () => Company::query()->first()?->id)
                ->options(fn () => Company::query()->pluck('title', 'id')->toArray())
                ->required()
                ->selectablePlaceholder(false)
                ->live()
                ->afterStateUpdated(function (Set $set, ?string $state): void {
                    $set('place_ids', Place::where('company_id', $state ?? null)->pluck('id')->toArray());
                }),

            CheckboxList::make('place_ids')
                ->label(__('filament/calendar.filters.places'))
                ->default(fn (Get $get) => Place::where('company_id', $get('company_id'))->pluck('id')->toArray())
                ->options(fn (Get $get) => Place::where('company_id', $get('company_id'))->pluck('title', 'id')->toArray())
                ->bulkToggleable()
                ->live(),
        ]);
    }

    protected function getCompanyId(): ?int
    {
        return $this->filters['company_id'] ?? null;
    }

    #[Computed]
    protected function getResources(): array
    {
        return Place::query()
            ->where('company_id', $this->getCompanyId() ?? null)
            ->whereIn('id', $this->filters['place_ids'] ?? [])
            ->pluck('title', 'id')
            ->toArray();
    }

    // #[Computed]
    // protected function getResourceMeta(): array
    // {
    //     return Place::query()
    //         ->whereIn('id', array_keys($this->getResources))
    //         ->get()
    //         ->keyBy('id')
    //         ->map(fn (Place $place) => [
    //             'avatar_url' => $place->image_url,
    //             'color'      => $place->color,
    //             'initials'   => mb_strtoupper(mb_substr($place->short_title ?? $place->title, 0, 2)),
    //         ])
    //         ->toArray();
    // }
    protected function createEventFrom(array $arguments): array
    {
        $companyId = $this->getCompanyId();

        return [
            Grid::make()
                ->schema([
                    Fieldset::make(__('filament/calendar.form.booking_details'))
                        ->schema([
                            Select::make('company_id')
                                ->label(__('filament/calendar.form.company'))
                                ->options(Company::pluck('title', 'id'))
                                ->default($companyId)
                                ->disabled()
                                ->dehydrated()
                                ->required(),
                            Select::make('place_id')
                                ->label(__('filament/calendar.form.place'))
                                ->options(Place::where('company_id', $companyId)->pluck('title', 'id'))
                                ->default($arguments['resource_id'] ?? null)
                                ->live()
                                ->required(),
                            Select::make('staff_id')
                                ->label(__('filament/reservations.fields.staff'))
                                ->relationship('staff', 'first_name', fn ($query) => $query->bookable())
                                ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                                ->searchable(['first_name', 'last_name'])
                                ->preload(),
                        ]),
                    Fieldset::make(__('filament/calendar.form.time'))
                        ->schema([
                            DateTimePicker::make('from_time')
                                ->label(__('filament/calendar.form.start'))
                                ->default($arguments['from_time'] ?? null)
                                ->native(false)
                                ->withoutSeconds()
                                ->minutesStep(5)
                                ->live()
                                ->required()
                                ->rules([
                                    function () {
                                        return function (string $attribute, $value, Closure $fail): void {
                                            $time = Carbon::parse($value);
                                            $dayOfWeek = $time->format('l');
                                            $openingHours = $this->getOpeningHours();
                                            $hours = $openingHours[$dayOfWeek] ?? null;

                                            if (! $hours || $hours['max'] === 0) {
                                                $fail(__('filament/calendar.validation.venue_closed', ['day' => $dayOfWeek]));

                                                return;
                                            }

                                            if ($time->hour < $hours['min'] || $time->hour >= $hours['max']) {
                                                $fail(__('filament/calendar.validation.time_range', ['min' => $hours['min'], 'max' => $hours['max']]));
                                            }
                                        };
                                    },
                                ])
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state, ?string $old): void {
                                    $this->recalculateToTime($get, $set, Carbon::parse($old ?? $state));
                                }),
                            DateTimePicker::make('to_time')
                                ->label(__('filament/calendar.form.end'))
                                ->default(isset($arguments['from_time']) ? Carbon::parse($arguments['from_time'])->addHour() : null)
                                ->native(false)
                                ->withoutSeconds()
                                ->minutesStep(5)
                                ->live()
                                ->required()
                                ->rules([
                                    function () {
                                        return function (string $attribute, $value, Closure $fail): void {
                                            $time = Carbon::parse($value);
                                            $dayOfWeek = $time->format('l');
                                            $openingHours = $this->getOpeningHours();
                                            $hours = $openingHours[$dayOfWeek] ?? null;

                                            if (! $hours || $hours['max'] === 0) {
                                                $fail(__('filament/calendar.validation.venue_closed', ['day' => $dayOfWeek]));

                                                return;
                                            }

                                            if ($time->hour < $hours['min'] || $time->hour >= $hours['max']) {
                                                $fail(__('filament/calendar.validation.time_range', ['min' => $hours['min'], 'max' => $hours['max']]));
                                            }
                                        };
                                    },
                                    function (Get $get, ?Model $record) {
                                        return function (string $attribute, $value, Closure $fail) use ($get, $record): void {
                                            $fromTime = Carbon::parse($get('from_time'));
                                            $toTime = Carbon::parse($value);

                                            if ($toTime->isBefore($fromTime)) {
                                                $fail(__('filament/calendar.validation.end_after_start'));

                                                return;
                                            }

                                            $placeId = $get('place_id');

                                            if (! $placeId) {
                                                return;
                                            }

                                            $query = Reservation::where('place_id', $placeId)
                                                ->where(function ($q) use ($fromTime, $toTime) {
                                                    $q->where('from_time', '<', $toTime)
                                                        ->where('to_time', '>', $fromTime);
                                                });

                                            if ($record?->getKey()) {
                                                $query->where('id', '!=', $record->getKey());
                                            }

                                            if ($query->exists()) {
                                                $fail(__('filament/calendar.validation.time_occupied'));
                                            }
                                        };
                                    },
                                ]),
                        ]),
                ])
                ->columns(2),
            Fieldset::make(__('filament/calendar.form.capacity_section'))
                ->schema([
                    TextInput::make('capacity')
                        ->label(__('filament/calendar.form.capacity'))
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(function (Get $get): ?int {
                            $placeId = $get('place_id');
                            if (! $placeId) {
                                return null;
                            }

                            return Place::query()->find($placeId)?->capacity;
                        })
                        ->placeholder(function (Get $get): ?string {
                            $placeId = $get('place_id');
                            if (! $placeId) {
                                return null;
                            }

                            return 'Max: '.Place::query()->find($placeId)?->capacity;
                        })
                        ->nullable(),
                    TextInput::make('booked_count')
                        ->label(__('filament/calendar.form.booked_count'))
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(fn (Get $get) => $get('capacity'))
                        ->default(0)
                        ->nullable(),
                    ToggleButtons::make('status')
                        ->label(__('filament/calendar.form.status'))
                        ->options(ReservationStatus::class)
                        ->default('pending')
                        ->inline()
                        ->columnSpanFull(),
                ])->columns(2),
            Fieldset::make(__('filament/calendar.form.customer_section'))
                ->schema([
                    Select::make('customers')
                        ->label(__('filament/calendar.form.customers'))
                        ->relationship(
                            'customers',
                            'email',
                            fn ($query) => $query->orderBy('last_name')->orderBy('first_name'),
                        )
                        ->multiple()
                        ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->first_name} {$record->last_name} ({$record->email})",
                        )
                        ->searchable(['first_name', 'last_name', 'email'])
                        ->preload(),
                ])->columns(2),
        ];
    }

    protected function getTitle(): ?string
    {
        return $this->getCompanyTitle();
    }

    protected function getCompanyTitle(): ?string
    {
        $companyId = $this->getCompanyId();

        return Company::find($companyId)?->title ?? null;
    }

    protected function editEventFrom(array $arguments): array
    {
        return $this->createEventFrom($arguments);
    }

    protected function recalculateToTime(Get $get, Set $set, Carbon $oldFrom): void
    {
        $newFromTime = Carbon::parse($get('from_time'));
        $minutesDiff = $oldFrom->diffInMinutes($get('to_time'));

        $set('to_time', $newFromTime->addMinutes($minutesDiff)->toDateTimeString());
    }

    protected function getEditEventFooterActions(Model $record, array $arguments): array
    {
        return [
            Action::make('deleteReservation')
                ->label(__('filament/calendar.actions.delete'))
                ->successNotificationTitle(__('cal-me::labels.success_notification'))
                ->failureNotificationTitle(__('cal-me::labels.failure_notification'))
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn () => $record->delete())
                ->cancelParentActions(),
        ];
    }

    protected function validateUpdate(CarbonInterface $fromTime, CarbonInterface $toTime, int $eventId, int $resourceId): bool
    {
        $openingHours = $this->getOpeningHours();
        $dayOfWeek = $fromTime->format('l');

        if (! isset($openingHours[$dayOfWeek])) {
            return false;
        }

        if ($openingHours[$dayOfWeek]['max'] === 0) {
            return false;
        }

        $openingTime = $fromTime->copy()->setHour($openingHours[$dayOfWeek]['min'])->setMinute(0)->setSecond(0);
        $closingTime = $fromTime->copy()->setHour($openingHours[$dayOfWeek]['max'])->setMinute(0)->setSecond(0);
        $toDayHours = $openingHours[$toTime->format('l')] ?? null;
        $toOpeningTime = $toTime->copy()->setHour($toDayHours['min'])->setMinute(0)->setSecond(0);
        $toClosingTime = $toTime->copy()->setHour($toDayHours['max'])->setMinute(0)->setSecond(0);

        if ($fromTime->lt($openingTime) || $fromTime->gte($closingTime)) {
            return false;
        }

        if ($fromTime->isSameDay($toTime) && $toTime->gt($closingTime)) {
            return false;
        }

        if ($fromTime->gte($toTime)) {
            return false;
        }

        if (! $toDayHours || $toDayHours['max'] === 0) {
            return false;
        }

        if ($toTime->lt($toOpeningTime) || $toTime->gt($toClosingTime)) {
            return false;
        }

        $overlaps = Reservation::where('id', '!=', $eventId)
            ->where('place_id', $resourceId)
            ->where(function ($query) use ($fromTime, $toTime) {
                $query->where('from_time', '<', $toTime)
                    ->where('to_time', '>', $fromTime);
            })
            ->exists();

        return ! $overlaps;
    }

    #[Computed]
    protected function getEvents(): Collection
    {
        $date = Carbon::parse($this->filters['date'] ?? now());

        if ($this->calendarView === 'day-horizontal') {
            $startDate = $date->copy()->startOfDay();
            $endDate = $date->copy()->endOfDay();
        } else {
            $startDate = $date->copy()->startOfWeek();
            $endDate = $date->copy()->endOfWeek()->addSecond();
        }

        return Reservation::query()
            ->with(['place', 'staff'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('from_time', [$startDate, $endDate])
                    ->orWhereBetween('to_time', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('from_time', '<', $startDate)
                            ->where('to_time', '>', $endDate);
                    });
            })
            ->when(
                $this->getCompanyId(),
                fn ($query, $companyId) => $query->where('company_id', $companyId)
            )
            ->when(
                $this->filters['place_ids'] ?? null,
                fn ($query, $placeIds) => $query->whereIn('place_id', $placeIds)
            )
            ->get();

    }

    protected function getPollingInterval(): ?string
    {
        return '30s';
    }

    /**
     * Interim: full-day window until opening hours are loaded per Place (or Company).
     *
     * @return array<string, array{min: int, max: int}>
     */
    #[Computed]
    protected function getOpeningHours(): array
    {
        return once(function (): array {
            $day = ['min' => 0, 'max' => 24];

            return [
                'Monday' => $day,
                'Tuesday' => $day,
                'Wednesday' => $day,
                'Thursday' => $day,
                'Friday' => $day,
                'Saturday' => $day,
                'Sunday' => $day,
            ];
        });
    }

    protected function getWidgetCellDimensions(string $view): array
    {
        $base = $this->cellDimensions[$view] ?? [];

        if ($view !== 'week-vertical') {
            return $base;
        }

        $settings = app(CalendarSettings::class);

        return [
            ...$base,
            'cell_width' => $settings->step_width * 4,
            'cell_height' => $settings->row_height,
        ];
    }

    protected function getEventModel(): string
    {
        return Reservation::class;
    }

    protected function getResourceAttribute(): string
    {
        return 'place_id';
    }
}
