<?php

namespace App\Http\Controllers;

use App\Events\AgendaUpdated;
use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NormalAdminController extends Controller
{
    private function bumpTvCacheVersion(): void
    {
        if (!Cache::add('tv:data:version', 1)) {
            Cache::increment('tv:data:version');
        }
    }

    private function bumpAgendaListCacheVersion(): void
    {
        if (!Cache::add('agenda:list:version', 1)) {
            Cache::increment('agenda:list:version');
        }
    }

    // ===============================
    // INDEX (Halaman Normal Admin)
    // ===============================
    public function index()
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8');

        return view('admin.normaladmin');
    }

    // ===============================
    // AJAX LIST dengan PAGINATION & SORTING
    // ===============================
    public function list(Request $request)
    {
        $perPage = 5;
        $page = max((int) $request->query('page', 1), 1);
        $version = (int) Cache::get('agenda:list:version', 1);
        $cacheKey = "agenda:list:v{$version}:p{$page}:pp{$perPage}";

        $payload = Cache::remember($cacheKey, 30, function () use ($perPage, $page) {
            $data = Kegiatan::agendaOrder()->paginate($perPage, ['*'], 'page', $page);

            $data->getCollection()->transform(function ($k) {
                return [
                    'id' => $k->kegiatan_id,
                    'tanggal_kegiatan' => $k->tanggal_kegiatan,
                    'tanggal_label' => Carbon::parse($k->tanggal_kegiatan)
                        ->translatedFormat('l, d F Y'),
                    'jam' => $k->jam,
                    'nama_kegiatan' => $k->nama_kegiatan,
                    'tempat' => $k->tempat,
                    'disposisi' => $k->disposisi,
                    'keterangan' => $k->keterangan,
                ];
            });

            return $data->toArray();
        });

        return response()->json($payload);
    }

    // ===============================
    // DETAIL KEGIATAN
    // ===============================
    public function kegiatanDetail($id)
    {
        $k = Kegiatan::where('kegiatan_id', $id)->firstOrFail();

        return response()->json([
            'kegiatan_id' => $k->kegiatan_id,
            'tanggal_kegiatan' => $k->tanggal_kegiatan,
            'jam' => $k->jam ? Carbon::parse($k->jam)->format('H:i') : null,
            'nama_kegiatan' => $k->nama_kegiatan,
            'tempat' => $k->tempat,
            'disposisi' => $k->disposisi,
            'keterangan' => $k->keterangan,
        ]);
    }

    // ===============================
    // STORE KEGIATAN
    // ===============================
    public function kegiatanStore(Request $request)
    {
        $request->validate([
            'tanggal_kegiatan' => 'required|date',
            'jam' => 'required',
            'nama_kegiatan' => 'required|string|max:255',
            'tempat' => 'required|string|max:255',
            'disposisi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $agenda = Kegiatan::create([
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'jam' => $request->jam,
            'nama_kegiatan' => $request->nama_kegiatan,
            'tempat' => $request->tempat,
            'disposisi' => $request->disposisi,
            'keterangan' => $request->keterangan,
        ]);

        $this->bumpTvCacheVersion();
        $this->bumpAgendaListCacheVersion();
        event(new AgendaUpdated($agenda));

        return response()->json([
            'success' => true,
            'message' => 'Agenda berhasil ditambahkan'
        ]);
    }

    // ===============================
    // UPDATE KEGIATAN
    // ===============================
    public function kegiatanUpdate(Request $request, $id)
    {
        $request->validate([
            'tanggal_kegiatan' => 'required|date',
            'jam' => 'required',
            'nama_kegiatan' => 'required|string|max:255',
            'tempat' => 'required|string|max:255',
            'disposisi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $kegiatan = Kegiatan::where('kegiatan_id', $id)->firstOrFail();

        $kegiatan->update([
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'jam' => $request->jam,
            'nama_kegiatan' => $request->nama_kegiatan,
            'tempat' => $request->tempat,
            'disposisi' => $request->disposisi,
            'keterangan' => $request->keterangan,
        ]);

        $this->bumpTvCacheVersion();
        $this->bumpAgendaListCacheVersion();
        event(new AgendaUpdated($kegiatan));

        return response()->json([
            'success' => true,
            'message' => 'Agenda berhasil diupdate'
        ]);
    }

    // ===============================
    // DELETE KEGIATAN
    // ===============================
    public function kegiatanDelete($id)
    {
        $agenda = Kegiatan::where('kegiatan_id', $id)->firstOrFail();

        $agenda->delete();

        $this->bumpTvCacheVersion();
        $this->bumpAgendaListCacheVersion();
        event(new AgendaUpdated($agenda));

        return response()->json([
            'success' => true,
            'message' => 'Agenda berhasil dihapus'
        ]);
    }
}
