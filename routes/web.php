<?php

use App\Http\Controllers\StripeWebhookController;
use App\Livewire\BookingCancel;
use App\Livewire\BookingSuccess;
use App\Livewire\BookingWizard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('book', BookingWizard::class)->name('booking');
Route::get('book/success', BookingSuccess::class)->name('booking.success');
Route::get('book/cancel', BookingCancel::class)->name('booking.cancel');

Route::post('stripe/webhook', StripeWebhookController::class)->name('stripe.webhook');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
