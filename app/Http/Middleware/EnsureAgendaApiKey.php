<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnsureAgendaApiKey
{
    public function handle(Request $request, Closure $next): JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $configuredKey = (string) config('services.agenda_api.key', '');

        if ($configuredKey === '') {
            if (app()->environment(['local', 'testing'])) {
                return $next($request);
            }

            return response()->json([
                'success' => false,
                'message' => 'Agenda API key belum dikonfigurasi pada server.',
            ], 503);
        }

        $providedKey = $request->bearerToken()
            ?? $request->header('X-Agenda-Api-Key')
            ?? $request->query('api_key');

        if (!is_string($providedKey) || !hash_equals($configuredKey, $providedKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Agenda API key tidak valid.',
            ], 401);
        }

        return $next($request);
    }
}
