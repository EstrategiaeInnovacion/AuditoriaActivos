<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', \App\Http\Controllers\DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

use App\Http\Controllers\DeviceController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('devices', DeviceController::class);
    Route::get('devices/{device}/print-qr', [DeviceController::class , 'printQr'])->name('devices.print-qr');
    Route::resource('users', \App\Http\Controllers\UserController::class);
});

require __DIR__ . '/auth.php';
