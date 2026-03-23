<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    private const VALID_API_KEY = 'xpOaPnlnQirvPWiR2MDaBtNsur6j7m3Z4dnl0iK/lVc=';

    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        if (!$apiKey || $apiKey !== self::VALID_API_KEY) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized. Invalid or missing API key.',
            ], 401);
        }

        return $next($request);
    }
}
