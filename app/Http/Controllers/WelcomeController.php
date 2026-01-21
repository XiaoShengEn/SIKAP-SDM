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

        $profil = DB::table('tb_profil')->orderBy('id_profil')->get();
        $videos = DB::table('tb_video')->orderBy('video_id')->get();
        $playlist = $videos->pluck('video_kegiatan')->map(fn($v) => asset('videos/' . $v))->toArray();
        $agendaKegiatan = DB::table('tb_kegiatan')
            ->whereDate('tanggal_kegiatan', '>=', Carbon::today('Asia/Jakarta'))
            ->orderBy('tanggal_kegiatan')
            ->orderBy('jam')
            ->get();
        $runningtext = DB::table('tb_runningtext')->orderBy('id_text')->pluck('isi_text')->toArray();

        $ultahText = Cache::remember('ultah_hari_ini', 10, function() {
        $token = config('services.kemendagri.token');
        if (!$token) {
            return ['Hari ini belum ada pegawai yang berulang tahun'];
        }

        $texts = [];
        $date = Carbon::today('Asia/Jakarta'); 

        $res = Http::withoutVerifying()
            ->withHeaders([
                'auth' => $token,
                'Accept' => 'application/json',
            ])
            ->asForm()
            ->timeout(15)
            ->post(config('services.kemendagri.url'), [
                'hari_ini' => $date->format('Y-m-d')
            ]);

        $data = $res->successful() ? $res->json('data') : [];

        $found = [];
        foreach ($data as $pegawai) {
            if (substr($pegawai['tanggal_lahir'], 5, 5) === $date->format('m-d')) {
                $found[] = $pegawai['nama'];
            }
        }

        if (!empty($found)) {
            foreach ($found as $nama) {
                $texts[] = "🎉 Selamat Berulang Tahun : {$nama} 🎉";
            }
        } else {
            $texts[] = 'Hari ini belum ada pegawai yang berulang tahun';
        }

        return $texts;
    });

    // gabungkan dengan running text lain
    $runningtext = array_merge($ultahText, $runningtext);

    return view('welcome', compact('profil', 'videos', 'playlist', 'agendaKegiatan', 'runningtext'));

    }
    }