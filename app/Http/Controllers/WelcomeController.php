<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    public function index()
    {
        // ===============================
        // SET BAHASA INDONESIA
        // ===============================
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8');

        // ===============================
        // PROFIL PIMPINAN
        // ===============================
        $profil = DB::table('tb_profil')
            ->orderBy('id_profil', 'asc')
            ->get();

        // ===============================
        // VIDEO (playlist)
        // ===============================
        $videos = DB::table('tb_video')
            ->orderBy('video_id', 'asc')
            ->get();

        $playlist = $videos->pluck('video_kegiatan')
            ->map(fn($v) => asset('videos/' . $v))
            ->toArray();

        // ===============================
        // AGENDA (HANYA HARI INI & KE DEPAN)
        // ===============================
        $agendaKegiatan = DB::table('tb_kegiatan')
    ->whereDate('tanggal_kegiatan', '>=', Carbon::today('Asia/Jakarta'))
    ->orderBy('tanggal_kegiatan', 'asc')
    ->orderBy('jam', 'asc')
    ->get();

        // ===============================
        // RUNNING TEXT
        // ===============================
        $runningtext = DB::table('tb_runningtext')
            ->orderBy('id_text', 'asc')
            ->pluck('isi_text')
            ->toArray();

        // ===============================
        // RETURN VIEW
        // ===============================
        return view('welcome', compact(
            'profil',
            'videos',
            'playlist',
            'agendaKegiatan',
            'runningtext'
        ));
    }
}
