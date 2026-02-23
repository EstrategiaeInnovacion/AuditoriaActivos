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

    // Device Documents
    Route::post('devices/{device}/documents', [\App\Http\Controllers\DeviceDocumentController::class , 'store'])->name('device.documents.store');
    Route::get('documents/{document}/download', [\App\Http\Controllers\DeviceDocumentController::class , 'download'])->name('device.documents.download');
    Route::delete('documents/{document}', [\App\Http\Controllers\DeviceDocumentController::class , 'destroy'])->name('device.documents.destroy');

    // Exports
    Route::get('devices-export/excel', [DeviceController::class , 'exportExcel'])->name('devices.export.excel');
    Route::get('devices-export/pdf', [DeviceController::class , 'exportPdf'])->name('devices.export.pdf');
});

Route::middleware(['auth', 'verified', 'is_admin'])->group(function () {
    Route::resource('users', \App\Http\Controllers\UserController::class);
});

require __DIR__ . '/auth.php';
