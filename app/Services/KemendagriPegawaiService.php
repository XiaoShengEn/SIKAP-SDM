<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KemendagriPegawaiService
{
    public static function getBirthdayToday()
    {
        $token = TokenService::getToken();

        $response = Http::asForm()
            ->withOptions(['verify' => false])
            ->withHeaders([
                'auth' => $token, // ⬅️ INI SESUAI DOKUMENTASI
            ])
            ->post('https://apimanager-ropeg.kemendagri.go.id/api/get_tgl_lahir_pegawai', [
                'hari_ini' => 1, // optional tapi dianjurkan
            ]);

        if (! $response->successful()) {
            throw new \Exception('API error: ' . $response->body());
        }

        return $response->json();
    }
}
