<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Kegiatan;
use App\Events\AgendaUpdated;

class NormalAdminController extends Controller
{
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
public function kegiatanList(Request $request)
{
    $today = \Carbon\Carbon::today();

    $all = Kegiatan::get()->map(function ($k) use ($today) {

        $tgl = \Carbon\Carbon::parse($k->tanggal_kegiatan);
        $diff = $today->diffInDays($tgl, false);

        if ($diff == 0) {
            $status = 'today';
        } elseif ($diff == 1) {
            $status = 'tomorrow';
        } else {
            $status = 'other';
        }

        return [
            'id' => $k->kegiatan_id,
            'tanggal_kegiatan' => $k->tanggal_kegiatan,
            'tanggal_label' => $tgl->locale('id')->translatedFormat('l, d F Y'),
            'jam' => $k->jam ? \Carbon\Carbon::parse($k->jam)->format('H:i') : null,
            'status' => $status,
            'nama_kegiatan' => $k->nama_kegiatan,
            'tempat' => $k->tempat,
            'disposisi' => $k->disposisi,
            'keterangan' => $k->keterangan,
            'diff' => abs($diff),
            'is_past' => $diff < 0 ? 1 : 0,
        ];
    });

    $sorted = $all->sortBy([
        ['is_past', 'asc'],
        ['diff', 'asc'],
    ])->values();

    $perPage = 4;
    $page = $request->page ?? 1;
    $items = $sorted->slice(($page - 1) * $perPage, $perPage)->values();

    return response()->json([
        'data' => $items,
        'total' => $sorted->count(),
        'current_page' => (int)$page,
        'last_page' => ceil($sorted->count() / $perPage),
    ]);
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

        // 🔴 BROADCAST REALTIME
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

        // 🔴 BROADCAST REALTIME
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

        // 🔴 BROADCAST REALTIME
        event(new AgendaUpdated($agenda));

        return response()->json([
            'success' => true,
            'message' => 'Agenda berhasil dihapus'
        ]);
    }
}