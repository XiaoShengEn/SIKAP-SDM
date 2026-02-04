<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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

        //  ultah dari API
        $ultahText = DB::table('birthday_today')
            ->pluck('nama')
            ->filter(fn ($n) => $n !== null && trim($n) !== '')
            ->map(fn ($n) => "🎉Selamat Berulang Tahun: $n 🎉")
            ->toArray();

        if (count($ultahText) === 0) {
            $ultahText = ['-'];
        }

        // gabungkan dengan running text lain
        $runningtext = array_merge($ultahText, $runningtext);

        if (count($runningtext) === 0) {
            $runningtext = ['-'];
        }

        return view('welcome', compact(
            'profil',
            'videos',
            'playlist',
            'agendaKegiatan',
            'runningtext'
        ));
    }
}
