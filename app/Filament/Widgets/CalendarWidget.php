<?php

namespace App\Filament\Widgets;

use App\Enums\ReservationStatus;
use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Staff;
use Carbon\CarbonInterface;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                ->label('Date')
                ->default(now())
                ->native(false)
                ->live(),

            Select::make('company_id')
                ->label('Company')
                ->default(fn () => Company::query()->first()?->id)
                ->options(fn () => Company::query()->pluck('title', 'id')->toArray())
                ->required()
                ->selectablePlaceholder(false)
                ->live()
                ->afterStateUpdated(function (Set $set, ?string $state): void {
                    $set('place_ids', Place::where('company_id', $state ?? null)->pluck('id')->toArray());
                }),

            CheckboxList::make('place_ids')
                ->label('Place')
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

    protected function createEventFrom(array $arguments): array
    {
        $companyId = $this->getCompanyId();

        return [
            Grid::make()
                ->schema([
                    Fieldset::make('Booking Details')
                        ->schema([
                            Select::make('company_id')
                                ->label('Company')
                                ->options(Company::pluck('title', 'id'))
                                ->default($companyId)
                                ->disabled()
                                ->dehydrated()
                                ->required(),
                            Select::make('place_id')
                                ->label('Venue')
                                ->options(Place::where('company_id', $companyId)->pluck('title', 'id'))
                                ->default($arguments['resource_id'] ?? null)
                                ->live()
                                ->required(),
                            Select::make('service_id')
                                ->label('Service')
                                ->options(Service::where('company_id', $companyId)->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state): void {
                                    if ($state) {
                                        $service = Service::find($state);
                                        if ($service && $get('from_time')) {
                                            $from = Carbon::parse($get('from_time'));
                                            $set('to_time', $from->addMinutes($service->duration_minutes)->toDateTimeString());
                                            $set('total_price', $service->price);
                                        }
                                    }
                                }),
                            Select::make('staff_id')
                                ->label('Staff')
                                ->options(Staff::where('company_id', $companyId)->get()->mapWithKeys(fn (Staff $s) => [$s->id => "{$s->first_name} {$s->last_name}"]))
                                ->searchable()
                                ->preload(),
                        ]),
                    Fieldset::make('Time')
                        ->schema([
                            DateTimePicker::make('from_time')
                                ->label('Start')
                                ->default($arguments['from_time'] ?? null)
                                ->native(false)
                                ->withoutSeconds()
                                ->minutesStep(5)
                                ->live()
                                ->required()
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state, ?string $old): void {
                                    $this->recalculateToTime($get, $set, Carbon::parse($old ?? $state));
                                }),
                            DateTimePicker::make('to_time')
                                ->label('End')
                                ->default(isset($arguments['from_time']) ? Carbon::parse($arguments['from_time'])->addHour() : null)
                                ->native(false)
                                ->withoutSeconds()
                                ->minutesStep(5)
                                ->live()
                                ->required()
                                ->rules([
                                    function () {
                                        return function (string $attribute, $value, Closure $fail) {
                                            $time = Carbon::parse($value);
                                            $dayOfWeek = $time->format('l');
                                            $openingHours = $this->getOpeningHours();
                                            $hours = $openingHours[$dayOfWeek] ?? null;

                                            if (! $hours || $hours['max'] === 0) {
                                                $fail("The venue is closed on {$dayOfWeek}.");

                                                return;
                                            }

                                            if ($time->hour < $hours['min'] || $time->hour >= $hours['max']) {
                                                $fail("Start time must be between {$hours['min']}:00 and {$hours['max']}:00.");
                                            }
                                        };
                                    },
                                    function (Get $get, ?Model $record) {
                                        return function (string $attribute, $value, Closure $fail) use ($get, $record): void {
                                            $fromTime = Carbon::parse($get('from_time'));
                                            $toTime = Carbon::parse($value);

                                            if ($toTime->isBefore($fromTime)) {
                                                $fail('The time of the end must be later than the beginning.');

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
                                                $fail('The time slot is already occupied.');
                                            }
                                        };
                                    },
                                ]),
                        ]),
                ])
                ->columns(2),
            Fieldset::make('Capacity & Pricing')
                ->schema([
                    TextInput::make('capacity')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(30)
                        ->nullable(),
                    TextInput::make('booked_count')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(fn (Get $get) => $get('capacity'))
                        ->nullable(),
                    TextInput::make('total_price')
                        ->label('Price')
                        ->numeric()
                        ->prefix('$')
                        ->nullable(),
                    ToggleButtons::make('status')
                        ->options(ReservationStatus::class)
                        ->default('pending')
                        ->inline()
                        ->columnSpanFull(),
                ])->columns(3),
            Fieldset::make('Guest Information')
                ->schema([
                    TextInput::make('guest_name'),
                    TextInput::make('guest_email')
                        ->email(),
                    TextInput::make('guest_phone')
                        ->tel(),
                    Textarea::make('notes')
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(3),
        ];
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
                ->label('Delete')
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
            ->with(['service', 'staff', 'place'])
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

    protected function getEventModel(): string
    {
        return Reservation::class;
    }

    protected function getResourceAttribute(): string
    {
        return 'place_id';
    }
}
