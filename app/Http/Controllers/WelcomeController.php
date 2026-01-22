<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8');

        //  DATA DARI DATABASE
        $profil = DB::table('tb_profil')->orderBy('id_profil')->get();

        $videos = DB::table('tb_video')->orderBy('video_id')->get();
        $playlist = $videos->pluck('video_kegiatan')
            ->map(fn ($v) => asset('videos/' . $v))
            ->toArray();

        $agendaKegiatan = DB::table('tb_kegiatan')
            ->whereDate('tanggal_kegiatan', '>=', Carbon::today('Asia/Jakarta'))
            ->orderBy('tanggal_kegiatan')
            ->orderBy('jam')
            ->get();

        $runningtext = DB::table('tb_runningtext')
            ->orderBy('id_text')
            ->pluck('isi_text')
            ->toArray();

        //  ultah dari API menggunakan cache  

        $cacheKey = 'ultah_' . Carbon::today('Asia/Jakarta')->format('Y-m-d');

        $ultahText = Cache::remember($cacheKey, now()->addDay(), function () {

            $token = config('services.kemendagri.token');
            $url   = config('services.kemendagri.url');

            if (!$token || !$url) {
                return ['Hari ini belum ada pegawai yang berulang tahun'];
            }

            try {
                $res = Http::withoutVerifying()
                    ->withHeaders([
                        'auth' => $token,
                        'Accept' => 'application/json',
                    ])
                    ->asForm()
                    ->timeout(15)
                    ->post($url, [
                        'hari_ini' => Carbon::today('Asia/Jakarta')->format('Y-m-d')
                    ]);

                if (!$res->successful()) {
                    return ['Hari ini belum ada pegawai yang berulang tahun'];
                }

                $data = $res->json('data') ?? [];

                if (count($data) === 0) {
                    return ['Hari ini belum ada pegawai yang berulang tahun'];
                }

                $texts = [];
                foreach ($data as $pegawai) {
                    if (!empty($pegawai['nama'])) {
                        $texts[] = "🎉 Selamat Berulang Tahun : {$pegawai['nama']} 🎉";
                    }
                }

                return count($texts)
                    ? $texts
                    : ['Hari ini belum ada pegawai yang berulang tahun'];

            } catch (\Throwable $e) {
                return ['Hari ini belum ada pegawai yang berulang tahun'];
            }
        });

        // gabungkan dengan running text lain
        $runningtext = array_merge($ultahText, $runningtext);

        return view('welcome', compact(
            'profil',
            'videos',
            'playlist',
            'agendaKegiatan',
            'runningtext'
        ));
    }
}
