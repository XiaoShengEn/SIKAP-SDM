<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Kegiatan;

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
        $kegiatan = Kegiatan::agendaOrder()->paginate(4);

        $kegiatan->getCollection()->transform(function ($k) {
            $date = Carbon::parse($k->tanggal_kegiatan);

            if ($date->isToday()) {
                $status = 'today';
            } elseif ($date->isTomorrow()) {
                $status = 'tomorrow';
            } else {
                $status = 'other';
            }

            return [
                'id' => $k->kegiatan_id,
                'kegiatan_id' => $k->kegiatan_id,
                'tanggal_kegiatan' => $k->tanggal_kegiatan,
                'tanggal_label' => $date->translatedFormat('l, d F Y'),
                'jam' => $k->jam ? Carbon::parse($k->jam)->format('H:i') : null,
                'nama_kegiatan' => $k->nama_kegiatan,
                'tempat' => $k->tempat,
                'disposisi' => $k->disposisi,
                'keterangan' => $k->keterangan,
                'status' => $status,
            ];
        });

        return response()->json($kegiatan);
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
            'nama_kegiatan' => 'required|string|max:50',
            'tempat' => 'nullable|string|max:50',
            'disposisi' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string|max:50',
        ]);

        Kegiatan::create([
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'jam' => $request->jam,
            'nama_kegiatan' => $request->nama_kegiatan,
            'tempat' => $request->tempat,
            'disposisi' => $request->disposisi,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true]);
    }

    // ===============================
    // UPDATE KEGIATAN
    // ===============================
    public function kegiatanUpdate(Request $request, $id)
    {
        $request->validate([
            'tanggal_kegiatan' => 'required|date',
            'jam' => 'required',
            'nama_kegiatan' => 'required|string|max:50',
            'tempat' => 'nullable|string|max:50',
            'disposisi' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string|max:50',
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

        return response()->json(['success' => true]);
    }

    // ===============================
    // DELETE KEGIATAN
    // ===============================
    public function kegiatanDelete($id)
    {
        Kegiatan::where('kegiatan_id', $id)->delete();
        return response()->json(['success' => true]);
    }
}