<div class="space-y-8">
    {{-- Step Indicator --}}
    <nav class="flex items-center justify-center gap-2">
        @foreach ([1 => __('booking.steps.service'), 2 => __('booking.steps.datetime'), 3 => __('booking.steps.info'), 4 => __('booking.steps.review')] as $num => $label)
            <button
                wire:click="goToStep({{ $num }})"
                @class([
                    'flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-colors',
                    'bg-zinc-900 text-white shadow-sm' => $step === $num,
                    'bg-zinc-100 text-zinc-900' => $step > $num && $step !== $num,
                    'bg-zinc-50 text-zinc-400' => $step < $num,
                ])
            >
                <span @class([
                    'flex size-6 items-center justify-center rounded-full text-xs font-semibold',
                    'bg-white/20 text-white' => $step === $num,
                    'bg-zinc-200 text-zinc-700' => $step > $num && $step !== $num,
                    'bg-zinc-100 text-zinc-400' => $step < $num,
                ])>
                    @if ($step > $num)
                        <flux:icon name="check" variant="micro" class="size-3.5" />
                    @else
                        {{ $num }}
                    @endif
                </span>
                <span class="hidden sm:inline">{{ $label }}</span>
            </button>
            @if ($num < 4)
                <div @class([
                    'h-px w-6',
                    'bg-zinc-300' => $step > $num,
                    'bg-zinc-200' => $step <= $num,
                ])></div>
            @endif
        @endforeach
    </nav>

    {{-- Step 1: Select Service --}}
    @if ($step === 1)
        <flux:card class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('booking.headings.select_service') }}</flux:heading>
                <flux:text class="mt-1">{{ __('booking.messages.choose_service') }}</flux:text>
            </div>

            @if ($this->companies->count() > 1)
                <flux:field>
                    <flux:label>{{ __('booking.fields.company') }}</flux:label>
                    <flux:select wire:model.live="companyId">
                        <flux:select.option value="">{{ __('booking.placeholders.select_company') }}</flux:select.option>
                        @foreach ($this->companies as $company)
                            <flux:select.option :value="$company->id">{{ $company->title }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>
            @endif

            @if ($this->services->isNotEmpty())
                <flux:radio.group wire:model.live="serviceId" variant="cards" class="flex-col">
                    @foreach ($this->services as $service)
                        <flux:radio :value="$service->id" wire:key="service-{{ $service->id }}">
                            <flux:radio.indicator />
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <flux:heading class="leading-4">{{ $service->name }}</flux:heading>
                                    @if ($service->color)
                                        <div class="size-3 rounded-full" style="background-color: {{ $service->color }}"></div>
                                    @endif
                                </div>
                                @if ($service->description)
                                    <flux:text size="sm" class="mt-1">{{ $service->description }}</flux:text>
                                @endif
                                <div class="mt-2 flex items-center gap-3">
                                    <flux:badge size="sm" variant="pill">{{ $service->duration_minutes }} {{ __('booking.labels.minutes') }}</flux:badge>
                                    <flux:badge size="sm" variant="pill" color="zinc">{{ number_format($service->price, 2) }} {{ __('booking.labels.currency') }}</flux:badge>
                                    @if ($service->requires_payment)
                                        <flux:badge size="sm" variant="pill" color="amber">{{ __('booking.labels.payment_required') }}</flux:badge>
                                    @endif
                                </div>
                            </div>
                        </flux:radio>
                    @endforeach
                </flux:radio.group>
            @elseif ($companyId)
                <flux:callout variant="warning">
                    <flux:text>{{ __('booking.messages.no_services') }}</flux:text>
                </flux:callout>
            @endif

            @error('step1')
                <flux:callout variant="danger">
                    <flux:text>{{ $message }}</flux:text>
                </flux:callout>
            @enderror

            <div class="flex justify-end">
                <flux:button variant="primary" wire:click="nextStep" :disabled="!$serviceId">
                    {{ __('booking.buttons.next') }}
                </flux:button>
            </div>
        </flux:card>
    @endif

    {{-- Step 2: Select Place & Date/Time --}}
    @if ($step === 2)
        <flux:card class="space-y-6">
            <flux:heading size="lg">{{ __('booking.headings.select_datetime') }}</flux:heading>

            <flux:field>
                <flux:label>{{ __('booking.fields.place') }}</flux:label>
                <flux:select wire:model.live="placeId" wire:change="selectPlace($event.target.value)">
                    <flux:select.option value="">{{ __('booking.placeholders.select_place') }}</flux:select.option>
                    @foreach ($this->places as $place)
                        <flux:select.option :value="$place->id">
                            {{ $place->title }} @if ($place->capacity) ({{ __('booking.labels.capacity') }}: {{ $place->capacity }}) @endif
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            @if ($placeId)
                <flux:field>
                    <flux:label>{{ __('booking.fields.date') }}</flux:label>
                    <flux:input type="date" wire:model.live="selectedDate" :min="now()->format('Y-m-d')" />
                </flux:field>
            @endif

            @if (count($availableSlots) > 0)
                <flux:field>
                    <flux:label>{{ __('booking.fields.time_slot') }}</flux:label>
                    <flux:radio.group wire:model.live="selectedSlotFrom" variant="pills" class="flex-wrap">
                        @foreach ($availableSlots as $slot)
                            <flux:radio
                                :value="$slot['from']"
                                :label="$slot['label']"
                                wire:key="slot-{{ $slot['from'] }}"
                            />
                        @endforeach
                    </flux:radio.group>
                </flux:field>
            @elseif ($placeId && $selectedDate)
                <flux:callout variant="warning">
                    <flux:text>{{ __('booking.messages.no_slots') }}</flux:text>
                </flux:callout>
            @endif

            @error('step2')
                <flux:callout variant="danger">
                    <flux:text>{{ $message }}</flux:text>
                </flux:callout>
            @enderror

            <div class="flex justify-between">
                <flux:button variant="ghost" wire:click="previousStep">{{ __('booking.buttons.back') }}</flux:button>
                <flux:button variant="primary" wire:click="nextStep" :disabled="!$selectedSlotFrom">
                    {{ __('booking.buttons.next') }}
                </flux:button>
            </div>
        </flux:card>
    @endif

    {{-- Step 3: Guest Information --}}
    @if ($step === 3)
        <flux:card class="space-y-6">
            <flux:heading size="lg">{{ __('booking.headings.guest_info') }}</flux:heading>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>{{ __('booking.fields.name') }}</flux:label>
                    <flux:input wire:model="guestName" required />
                    <flux:error name="guestName" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('booking.fields.email') }}</flux:label>
                    <flux:input type="email" wire:model="guestEmail" required />
                    <flux:error name="guestEmail" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('booking.fields.phone') }}</flux:label>
                    <flux:input type="tel" wire:model="guestPhone" />
                    <flux:error name="guestPhone" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>{{ __('booking.fields.notes') }}</flux:label>
                    <flux:textarea wire:model="notes" rows="3" />
                    <flux:error name="notes" />
                </flux:field>
            </div>

            <div class="flex justify-between">
                <flux:button variant="ghost" wire:click="previousStep">{{ __('booking.buttons.back') }}</flux:button>
                <flux:button variant="primary" wire:click="nextStep">
                    {{ __('booking.buttons.next') }}
                </flux:button>
            </div>
        </flux:card>
    @endif

    {{-- Step 4: Review & Confirm --}}
    @if ($step === 4)
        <flux:card class="space-y-6">
            <flux:heading size="lg">{{ __('booking.headings.review') }}</flux:heading>

            @php
                $reviewService = $this->selectedService;
                $reviewPlace = $placeId ? \App\Domain\PlaceBooking\Models\Place::find($placeId) : null;
            @endphp

            <flux:card class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <flux:text size="sm">{{ __('booking.review.service') }}</flux:text>
                        <flux:heading size="sm" class="mt-0.5">{{ $reviewService?->name }}</flux:heading>
                    </div>
                    <div>
                        <flux:text size="sm">{{ __('booking.review.place') }}</flux:text>
                        <flux:heading size="sm" class="mt-0.5">{{ $reviewPlace?->title }}</flux:heading>
                    </div>
                    <div>
                        <flux:text size="sm">{{ __('booking.review.date') }}</flux:text>
                        <flux:heading size="sm" class="mt-0.5">{{ \Carbon\Carbon::parse($selectedSlotFrom)->format('l, F j, Y') }}</flux:heading>
                    </div>
                    <div>
                        <flux:text size="sm">{{ __('booking.review.time') }}</flux:text>
                        <flux:heading size="sm" class="mt-0.5">{{ \Carbon\Carbon::parse($selectedSlotFrom)->format('H:i') }} - {{ \Carbon\Carbon::parse($selectedSlotTo)->format('H:i') }}</flux:heading>
                    </div>
                    <div>
                        <flux:text size="sm">{{ __('booking.review.name') }}</flux:text>
                        <flux:heading size="sm" class="mt-0.5">{{ $guestName }}</flux:heading>
                    </div>
                    <div>
                        <flux:text size="sm">{{ __('booking.review.email') }}</flux:text>
                        <flux:heading size="sm" class="mt-0.5">{{ $guestEmail }}</flux:heading>
                    </div>
                </div>

                <flux:separator />

                <div class="flex items-center justify-between">
                    <flux:heading>{{ __('booking.review.total') }}</flux:heading>
                    <flux:heading>{{ number_format($reviewService?->price ?? 0, 2) }} {{ __('booking.labels.currency') }}</flux:heading>
                </div>

                @if ($reviewService?->requires_payment)
                    <flux:callout variant="info">
                        <flux:text>{{ __('booking.messages.stripe_redirect') }}</flux:text>
                    </flux:callout>
                @endif
            </flux:card>

            <div class="flex justify-between">
                <flux:button variant="ghost" wire:click="previousStep">{{ __('booking.buttons.back') }}</flux:button>
                <flux:button variant="primary" wire:click="confirmBooking">
                    {{ $reviewService?->requires_payment ? __('booking.buttons.pay_with_stripe') : __('booking.buttons.confirm') }}
                </flux:button>
            </div>
        </flux:card>
    @endif
</div>
