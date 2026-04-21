<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::livewire('/investigations/create', 'pages::investigations.create')->name('Order-investigation');
Route::livewire('/investigations/orderEmailInvestigations/create', 'pages::investigations.without-order-id.create')->name('Order-email-investigation');
Route::livewire('/investigations/integration-index', 'pages::investigations.slug-container')->name('integration-index');



require __DIR__.'/settings.php';
