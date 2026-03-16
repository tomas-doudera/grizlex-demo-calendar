<flux:card class="space-y-6 text-center">
    <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-green-100">
        <flux:icon name="check-circle" class="size-10 text-green-600" />
    </div>

    <flux:heading size="xl">{{ __('booking.success.heading') }}</flux:heading>
    <flux:text>{{ __('booking.success.message') }}</flux:text>

    @if ($reservation)
        <flux:card class="mx-auto max-w-md space-y-4 text-left">
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <flux:text size="sm">{{ __('booking.review.service') }}</flux:text>
                    <flux:heading size="sm" class="mt-0.5">{{ $reservation->service?->name }}</flux:heading>
                </div>
                <div>
                    <flux:text size="sm">{{ __('booking.review.place') }}</flux:text>
                    <flux:heading size="sm" class="mt-0.5">{{ $reservation->place?->title }}</flux:heading>
                </div>
                <div>
                    <flux:text size="sm">{{ __('booking.review.date') }}</flux:text>
                    <flux:heading size="sm" class="mt-0.5">{{ $reservation->from_time->format('l, F j, Y') }}</flux:heading>
                </div>
                <div>
                    <flux:text size="sm">{{ __('booking.review.time') }}</flux:text>
                    <flux:heading size="sm" class="mt-0.5">{{ $reservation->from_time->format('H:i') }} - {{ $reservation->to_time->format('H:i') }}</flux:heading>
                </div>
            </div>

            @if (! $paymentVerified)
                <flux:callout variant="warning">
                    <flux:text>{{ __('booking.success.payment_processing') }}</flux:text>
                </flux:callout>
            @endif
        </flux:card>
    @endif

    <div class="flex justify-center gap-4">
        <flux:button variant="primary" href="{{ route('booking') }}">
            {{ __('booking.buttons.book_another') }}
        </flux:button>
        <flux:button variant="ghost" href="{{ route('home') }}">
            {{ __('booking.buttons.go_home') }}
        </flux:button>
    </div>
</flux:card>
