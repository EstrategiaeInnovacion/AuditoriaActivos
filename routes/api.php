<?php

use App\Models\Employee;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->prefix('v1')->group(function () {
    Route::get('/users', function () {
        return response()->json([
            'success' => true,
            'data' => Employee::select('id', 'name', 'employee_id', 'department', 'position', 'phone', 'is_active', 'created_at')->where('is_active', true)->get(),
        ]);
    });
});
