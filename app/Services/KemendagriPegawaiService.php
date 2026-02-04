<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KemendagriPegawaiService
{
    public static function getBirthdayToday()
    {
        $token = TokenService::getToken();
        $verifySsl = filter_var(env('KEMENDAGRI_API_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN);
        $baseUrl = rtrim(config('services.kemendagri.url') ?? 'https://apimanager-ropeg.kemendagri.go.id', '/');

        $response = self::requestBirthday($baseUrl, $token, $verifySsl);

        if (self::isTokenExpiredResponse($response)) {
            $token = TokenService::getToken(true);
            $response = self::requestBirthday($baseUrl, $token, $verifySsl);
        }

        if (! $response->successful()) {
            throw new \Exception('API error: ' . $response->body());
        }

        return $response->json();
    }

    private static function requestBirthday(string $baseUrl, string $token, bool $verifySsl)
    {
        return Http::asForm()
            ->withOptions(['verify' => $verifySsl])
            ->timeout(15)
            ->retry(3, 1000)
            ->withHeaders([
                'auth' => $token, // INI SESUAI DOKUMENTASI
            ])
            ->post($baseUrl . '/api/get_tgl_lahir_pegawai', [
                'hari_ini' => 1,
            ]);
    }

    private static function isTokenExpiredResponse($response): bool
    {
        if (!method_exists($response, 'json')) {
            return false;
        }

        $data = $response->json();
        $message = is_array($data) ? ($data['message'] ?? '') : '';

        return is_string($message)
            && stripos($message, 'token time expire') !== false;
    }
}
