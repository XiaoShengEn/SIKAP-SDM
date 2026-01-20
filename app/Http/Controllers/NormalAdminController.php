<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NormalAdminController extends Controller
{
    // ===============================
    // INDEX (Halaman Normal Admin)
    // ===============================
    public function index()
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8');

        $kegiatan = DB::table('tb_kegiatan')
            ->orderBy('tanggal_kegiatan', 'asc')
            ->get();

        return view('admin.normaladmin', compact('kegiatan'));
    }

    // ===============================
    // STORE KEGIATAN
    // ===============================
    public function kegiatanStore(Request $request)
    {
        $request->validate([
            'tanggal_kegiatan' => 'required|date',
            'jam'            => 'required|date_format:H:i',
            'nama_kegiatan'    => 'required|string|max:50',
            'disposisi'        => 'nullable|string|max:20',
            'tempat'           => 'nullable|string|max:50',
            'keterangan'       => 'nullable|string|max:50',
        ]);

        Kegiatan::create([
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'jam'            => $request->jam,
            'nama_kegiatan'    => $request->nama_kegiatan,
            'disposisi'        => $request->disposisi,
            'keterangan'       => $request->keterangan,
            'tempat'           => $request->tempat,
        ]);

        return back()->withFragment('agenda');
    }
    // ===============================
    // UPDATE KEGIATAN
    // ===============================
    public function kegiatanUpdate(Request $request, $id)
    {
        $request->validate([
            'tanggal_kegiatan' => 'nullable|date',
            'jam'              => 'nullable|date_format:H:i',
            'nama_kegiatan'    => 'required|string|max:50',
            'disposisi'        => 'nullable|string|max:20',
            'tempat'           => 'nullable|string|max:50',
            'keterangan'       => 'nullable|string|max:50',
        ]);

        // ✅ Field wajib
        $data = [
            'nama_kegiatan' => $request->nama_kegiatan,
            'disposisi'     => $request->disposisi,
            'keterangan'    => $request->keterangan,
            'tempat'        => $request->tempat,
        ];

        // ✅ Update tanggal hanya kalau dikirim
        if ($request->filled('tanggal_kegiatan')) {
            $data['tanggal_kegiatan'] = $request->tanggal_kegiatan;
        }

        // ✅ Update jam hanya kalau dikirim
        if ($request->filled('jam')) {
            $data['jam'] = $request->jam;
        }

        Kegiatan::where('kegiatan_id', $id)->update($data);

        return back()->withFragment('agenda');
    }

    // ===============================
    // DELETE KEGIATAN
    // ===============================
    public function kegiatanDelete($id)
    {
        Kegiatan::where('kegiatan_id', $id)->delete();
        return back()->withFragment('agenda');
    }
}
