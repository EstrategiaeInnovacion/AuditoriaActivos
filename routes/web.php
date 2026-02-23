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
    // Exports (must be before resource to avoid route conflict)
    Route::get('export/devices/excel', [DeviceController::class , 'exportExcel'])->name('devices.export.excel');
    Route::get('export/devices/pdf', [DeviceController::class , 'exportPdf'])->name('devices.export.pdf');

    Route::resource('devices', DeviceController::class);
    Route::get('devices/{device}/print-qr', [DeviceController::class , 'printQr'])->name('devices.print-qr');
    Route::get('photos/{photo}', [DeviceController::class , 'showPhoto'])->name('device.photos.show');

    // Device Documents
    Route::post('devices/{device}/documents', [\App\Http\Controllers\DeviceDocumentController::class , 'store'])->name('device.documents.store');
    Route::get('documents/{document}/download', [\App\Http\Controllers\DeviceDocumentController::class , 'download'])->name('device.documents.download');
    Route::delete('documents/{document}', [\App\Http\Controllers\DeviceDocumentController::class , 'destroy'])->name('device.documents.destroy');
});

Route::middleware(['auth', 'verified', \App\Http\Middleware\IsAdmin::class])->group(function () {
    Route::resource('users', \App\Http\Controllers\UserController::class);
});

require __DIR__ . '/auth.php';
