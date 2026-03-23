<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->prefix('v1')->group(function () {
    Route::get('/users', function () {
        $erpUrl = config('app.erp_api_url') . '/api/v1/users';
        $erpApiKey = config('app.erp_api_key');

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $erpApiKey,
            ])->timeout(30)->get($erpUrl);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'data' => $data['data'] ?? [],
                    'source' => 'external_erp',
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to connect to ERP: ' . $response->status(),
            ], 502);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Connection error: ' . $e->getMessage(),
            ], 503);
        }
    });
});