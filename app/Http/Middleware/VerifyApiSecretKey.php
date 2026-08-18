<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiSecretKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = env('API_SECRET_KEY');
        $providedKey = $request->header('x-secret-key');

        if (!$expectedKey || $providedKey !== $expectedKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Invalid API Secret Key'
            ], 401);
        }

        return $next($request);
    }
}
