<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceDocumentController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified', 'throttle:60,1'])->group(function () {
    // Exports (must be before resource to avoid route conflict)
    Route::get('export/devices/excel', [DeviceController::class, 'exportExcel'])->middleware('throttle:10,1')->name('devices.export.excel');
    Route::get('export/devices/pdf', [DeviceController::class, 'exportPdf'])->middleware('throttle:10,1')->name('devices.export.pdf');
    Route::get('devices/print-multiple-qrs', [DeviceController::class, 'printMultipleQrs'])->name('devices.print-multiple-qrs');
    Route::get('devices/broken', [DeviceController::class, 'brokenIndex'])->name('devices.broken');

    Route::resource('devices', DeviceController::class);
    Route::get('devices/{device}/print-qr', [DeviceController::class, 'printQr'])->name('devices.print-qr');
    Route::get('photos/{photo}', [DeviceController::class, 'showPhoto'])->name('device.photos.show');

    // Device Documents
    Route::post('devices/{device}/documents', [DeviceDocumentController::class, 'store'])->name('device.documents.store');
    Route::get('documents/{document}/download', [DeviceDocumentController::class, 'download'])->name('device.documents.download');
    Route::delete('documents/{document}', [DeviceDocumentController::class, 'destroy'])->name('device.documents.destroy');
});

Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
