<flux:card class="space-y-6 text-center">
    <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-amber-100">
        <flux:icon name="x-circle" class="size-10 text-amber-600" />
    </div>

    <flux:heading size="xl">{{ __('booking.cancel.heading') }}</flux:heading>
    <flux:text>{{ __('booking.cancel.message') }}</flux:text>

    <div class="flex justify-center gap-4">
        <flux:button variant="primary" href="{{ route('booking') }}">
            {{ __('booking.buttons.try_again') }}
        </flux:button>
        <flux:button variant="ghost" href="{{ route('home') }}">
            {{ __('booking.buttons.go_home') }}
        </flux:button>
    </div>
</flux:card>
