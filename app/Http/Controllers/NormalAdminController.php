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
    public function kegiatanStore(Request $r)
    {
        DB::table('tb_kegiatan')->insert([
            'tanggal_kegiatan' => $r->tanggal_kegiatan,
            'nama_kegiatan'    => $r->nama_kegiatan,
            'disposisi'        => $r->disposisi,
            'keterangan'       => $r->keterangan,
            'tempat'           => $r->tempat
        ]);

        return back()->with('success', 'Agenda berhasil ditambahkan!');
    }

    // ===============================
    // UPDATE KEGIATAN
    // ===============================
    public function kegiatanUpdate(Request $r, $id)
    {
        DB::table('tb_kegiatan')
            ->where('kegiatan_id', $id)
            ->update([
                'tanggal_kegiatan' => $r->tanggal_kegiatan,
                'nama_kegiatan'    => $r->nama_kegiatan,
                'disposisi'        => $r->disposisi,
                'keterangan'       => $r->keterangan,
                'tempat'           => $r->tempat
            ]);

        return back()->with('success', 'Agenda berhasil diperbarui!');
    }

    // ===============================
    // DELETE KEGIATAN
    // ===============================
    public function kegiatanDelete($id)
    {
        DB::table('tb_kegiatan')
            ->where('kegiatan_id', $id)
            ->delete();

        return back()->with('success', 'Agenda berhasil dihapus!');
    }
}
