<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Carbon\Carbon;

class AgendaDataController extends Controller
{
    public function list(Request $request)
{
    $perPage = 5; // ⬅️ jadi 5 data

    $kegiatan = Kegiatan::orderBy('tanggal_kegiatan', 'asc')
        ->orderBy('jam', 'asc')
        ->paginate($perPage);

    $data = $kegiatan->map(function ($k) {

        $tanggal = Carbon::parse($k->tanggal_kegiatan)->startOfDay();
        $today = Carbon::today();

        $diff = $tanggal->diffInDays($today, false);

        if ($diff === 0) {
            $status = 'today';        // hijau
        } elseif ($diff === 1) {
            $status = 'tomorrow';     // kuning
        } elseif ($diff > 1) {
            $status = 'other';        // abu
        } else {
            $status = 'past';         // putih
        }

        return [
            'id' => $k->id,
            'kegiatan_id' => $k->id,
            'tanggal_kegiatan' => $k->tanggal_kegiatan,
            'tanggal_label' => $tanggal->translatedFormat('l, d F Y'),
            'jam' => $k->jam,
            'nama_kegiatan' => $k->nama_kegiatan,
            'tempat' => $k->tempat,
            'disposisi' => $k->disposisi,
            'keterangan' => $k->keterangan,
            'status' => $status,
        ];
    });

    return response()->json([
        'data' => $data,
        'current_page' => $kegiatan->currentPage(),
        'last_page' => $kegiatan->lastPage(),
        'total' => $kegiatan->total(),
    ]);
}

}
