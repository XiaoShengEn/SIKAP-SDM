<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TokenService
{
    public static function getToken()
    {
        $row = DB::table('api_tokens')
            ->where('service', 'kemendagri')
            ->first();

        if ($row && Carbon::now()->lt($row->expired_at)) {
            return $row->access_token;
        }

        $response = Http::asForm()
            ->withOptions(['verify' => false]) // SSL lokal bypass
            ->post('https://apimanager-ropeg.kemendagri.go.id/api/token', [
                'username' => env('KEMENDAGRI_API_USER'),
                'password' => env('KEMENDAGRI_API_PASS'),
            ]);

        if (! $response->successful()) {
            throw new \Exception('Gagal ambil token: ' . $response->body());
        }

        $data = $response->json();

        if (!isset($data['access_token'])) {
            throw new \Exception('Token tidak ditemukan: ' . json_encode($data));
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
