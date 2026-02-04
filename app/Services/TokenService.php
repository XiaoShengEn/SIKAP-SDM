<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TokenService
{
    public static function getToken(bool $forceRefresh = false)
    {
        if (! $forceRefresh) {
            $row = DB::table('api_tokens')
                ->where('service', 'kemendagri')
                ->first();

            if ($row && Carbon::now()->lt($row->expired_at)) {
                return $row->access_token;
            }
        }

        $verifySsl = filter_var(env('KEMENDAGRI_API_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN);
        $baseUrl = rtrim(config('services.kemendagri.url') ?? 'https://apimanager-ropeg.kemendagri.go.id', '/');

        $username = trim((string) env('KEMENDAGRI_API_USER', ''));
        $password = trim((string) env('KEMENDAGRI_API_PASS', ''));

        if ($username === '' || $password === '') {
            throw new \Exception('Kredensial Kemendagri kosong.');
        }

        $response = Http::asForm()
            ->withOptions(['verify' => $verifySsl])
            ->timeout(15)
            ->retry(3, 1000)
            ->post($baseUrl . '/api/token', [
                'username' => $username,
                'password' => $password,
            ]);

        if (! $response->successful()) {
            throw new \Exception('Gagal ambil token: ' . $response->body());
        }

        $data = $response->json();

        if (!isset($data['access_token'])) {
            $message = $data['message'] ?? 'Token tidak ditemukan';
            throw new \Exception($message . ': ' . json_encode($data));
        }

        $expiredAt = Carbon::now()->addHours(23);

        DB::table('api_tokens')->updateOrInsert(
            ['service' => 'kemendagri'],
            [
                'access_token' => $data['access_token'],
                'expired_at'   => $expiredAt,
                'updated_at'   => now(),
            ]
        );

        return $data['access_token'];
    }
}
