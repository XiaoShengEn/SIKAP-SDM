<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WelcomeController extends Controller
{
    private function resolveTvBackgroundImage(): ?string
    {
        $metaPath = storage_path('app/tv_backgrounds.json');
        if (File::exists($metaPath)) {
            $items = json_decode(File::get($metaPath), true);
            if (is_array($items) && count($items) > 0) {
                $active = collect($items)->firstWhere('is_active', true) ?? $items[0];
                $filename = $active['filename'] ?? null;

                if (!empty($filename)) {
                    $full = public_path('images/tv-backgrounds/' . $filename);
                    if (File::exists($full)) {
                        return asset('images/tv-backgrounds/' . $filename) . '?v=' . (@filemtime($full) ?: time());
                    }
                }
            }
        }

        $candidates = glob(public_path('images/tv-background.*')) ?: [];
        if (count($candidates) === 0) {
            return null;
        }

        $latest = collect($candidates)
            ->sortByDesc(fn($path) => @filemtime($path) ?: 0)
            ->first();

        if (!$latest) {
            return null;
        }

        return asset('images/' . basename($latest)) . '?v=' . (@filemtime($latest) ?: time());
    }

    private function tvCacheVersion(): int
    {
        return (int) Cache::get('tv:data:version', 1);
    }

    public function index()
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8');

        $today = Carbon::today('Asia/Jakarta');
        $version = $this->tvCacheVersion();

        $profil = Cache::remember("tv:profil:v{$version}", 300, function () {
            return DB::table('tb_profil')->orderBy('id_profil')->get();
        });

        $videos = Cache::remember("tv:videos:v{$version}", 300, function () {
            return DB::table('tb_video')->orderBy('video_id')->get();
        });

        $playlist = $videos
            ->pluck('video_kegiatan')
            ->map(fn($v) => asset('videos/' . $v))
            ->toArray();

        $agendaKegiatan = Cache::remember("tv:agenda:v{$version}:{$today->toDateString()}", 60, function () use ($today) {
            return DB::table('tb_kegiatan')
                ->whereDate('tanggal_kegiatan', '>=', $today)
                ->orderBy('tanggal_kegiatan')
                ->orderBy('jam')
                ->get();
        });

        $runningtext = Cache::remember("tv:runningtext:v{$version}", 120, function () {
            return DB::table('tb_runningtext')
                ->orderBy('id_text')
                ->pluck('isi_text')
                ->toArray();
        });

        $ultahText = Cache::remember("tv:birthday:{$today->toDateString()}", 300, function () use ($today) {
            return DB::table('birthday_today')
                ->whereDate('created_at', $today)
                ->pluck('nama')
                ->filter(fn($n) => $n !== null && trim($n) !== '')
                ->map(fn($n) => "Selamat Berulang Tahun: $n")
                ->toArray();
        });

        $runningtext = array_merge($ultahText, $runningtext);

        if (count($runningtext) === 0) {
            $runningtext = ['-'];
        }

        $backgroundImage = $this->resolveTvBackgroundImage();

        return view('welcome', compact(
            'profil',
            'videos',
            'playlist',
            'agendaKegiatan',
            'runningtext',
            'backgroundImage'
        ));
    }
}
