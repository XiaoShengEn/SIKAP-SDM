<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendaDataController extends Controller
{
    public function list(Request $request)
    {
        $perPage = 5;

        $kegiatan = Kegiatan::orderBy('tanggal_kegiatan', 'asc')
            ->orderBy('jam', 'asc')
            ->paginate($perPage);

        $data = $kegiatan->getCollection()->map(
            fn (Kegiatan $agenda) => $this->transformAgenda($agenda)
        );

        return response()->json([
            'data' => $data,
            'current_page' => $kegiatan->currentPage(),
            'last_page' => $kegiatan->lastPage(),
            'per_page' => $kegiatan->perPage(),
            'total' => $kegiatan->total(),
        ]);
    }

    public function apiIndex(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = max(1, min($perPage, 100));

        $agenda = Kegiatan::agendaOrder()->paginate($perPage);
        $data = $agenda->getCollection()->map(
            fn (Kegiatan $item) => $this->transformAgenda($item)
        );

        return response()->json([
            'success' => true,
            'message' => 'Data agenda berhasil diambil.',
            'data' => $data,
            'meta' => [
                'current_page' => $agenda->currentPage(),
                'last_page' => $agenda->lastPage(),
                'per_page' => $agenda->perPage(),
                'total' => $agenda->total(),
            ],
        ]);
    }

    private function transformAgenda(Kegiatan $kegiatan): array
    {
        $tanggal = Carbon::parse($kegiatan->tanggal_kegiatan)->startOfDay();
        $today = Carbon::today();
        $diff = $today->diffInDays($tanggal, false);

        if ($diff === 0) {
            $status = 'today';
        } elseif ($diff === 1) {
            $status = 'tomorrow';
        } elseif ($diff > 1) {
            $status = 'other';
        } else {
            $status = 'past';
        }

        return [
            'id' => $kegiatan->kegiatan_id,
            'kegiatan_id' => $kegiatan->kegiatan_id,
            'tanggal_kegiatan' => $kegiatan->tanggal_kegiatan,
            'tanggal_label' => $tanggal->translatedFormat('l, d F Y'),
            'jam' => $kegiatan->jam,
            'nama_kegiatan' => $kegiatan->nama_kegiatan,
            'tempat' => $kegiatan->tempat,
            'disposisi' => $kegiatan->disposisi,
            'keterangan' => $kegiatan->keterangan,
            'status' => $status,
        ];
    }
}
