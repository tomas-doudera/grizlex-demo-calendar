<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <script>document.documentElement.classList.remove('dark'); window.localStorage.setItem('flux.appearance', 'light');</script>
    </head>
    <body class="min-h-screen bg-white antialiased">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50">
            <x-app-logo :href="route('home')" />

            <flux:spacer />

            <flux:navbar class="-mb-px">
                <flux:navbar.item icon="calendar" :href="route('booking')" :current="request()->routeIs('booking')">
                    {{ __('booking.nav.book') }}
                </flux:navbar.item>
            </flux:navbar>
        </flux:header>

        <flux:main container class="max-w-3xl!">
            {{ $slot }}
        </flux:main>

        @fluxScripts
    </body>
</html>
