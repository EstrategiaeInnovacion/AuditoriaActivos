<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->prefix('v1')->group(function () {
    Route::get('/users', function () {
        $erpUrl = 'https://erp.estrategiaeinnovacion.com.mx/api/v1/users';
        $erpApiKey = 'xpOaPnlnQirvPWiR2MDaBtNsur6j7m3Z4dnl0iK/lVc=';

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