<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Pimpinan;
use App\Models\Video;
use App\Models\Kegiatan;
use App\Models\RunningText;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // ============================================================
    // DASHBOARD SUPERADMIN
    // ============================================================
    public function index()
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8');

        $profil = Pimpinan::orderBy('id_profil', 'asc')->get();
        $videos = Video::all();
        $kegiatan = Kegiatan::orderBy('tanggal_kegiatan', 'asc')->get();
        $runningtext = RunningText::orderBy('id_text')->get();
        $normaladmin = User::whereIn('role_admin', ['normaladmin', 'superadmin'])->get();

        return view('admin.superadmin', compact(
            'profil',
            'videos',
            'kegiatan',
            'runningtext',
            'normaladmin'
        ));
    }

    // ============================================================
    // CRUD PROFIL PIMPINAN — tb_profil
    // ============================================================
    public function profilIndex()
    {
        $profil = DB::table('tb_profil')->get();
        return view('admin.profil', compact('profil'));
    }

    public function profilStore(Request $request)
    {
        $request->validate([
            'nama_pimpinan'    => 'required|string|max:100',
            'jabatan_pimpinan' => 'required|string|max:100',
            'foto_pimpinan'    => 'required|image|max:2048'
        ]);

        $file = $request->file('foto_pimpinan');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/profil'), $namaFile);

        DB::table('tb_profil')->insert([
            'nama_pimpinan'    => $request->nama_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'foto_pimpinan'    => $namaFile
        ]);

        return back()->withFragment('profil')->with('success', 'Profil pimpinan berhasil ditambahkan!');
    }

    public function profilUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_pimpinan'    => 'required|string|max:100',
            'jabatan_pimpinan' => 'required|string|max:100',
            'foto_pimpinan'    => 'required|image|max:2048'
        ]);

        $profil = DB::table('tb_profil')->where('id_profil', $id)->first();
        $fotoLama = $profil->foto_pimpinan;

        if ($request->hasFile('foto_pimpinan')) {
            if (!empty($fotoLama)) {
                $oldPath = public_path('uploads/profil/' . $fotoLama);
                if (file_exists($oldPath)) unlink($oldPath);
            }

            $file = $request->file('foto_pimpinan');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profil'), $namaFile);

            $fotoLama = $namaFile;
        }

        DB::table('tb_profil')->where('id_profil', $id)->update([
            'nama_pimpinan'    => $request->nama_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'foto_pimpinan'    => $fotoLama
        ]);

        return back()->withFragment('profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function profilDelete($id)
    {
        $profil = DB::table('tb_profil')->where('id_profil', $id)->first();

        if ($profil && !empty($profil->foto_pimpinan)) {
            $path = public_path('uploads/profil/' . $profil->foto_pimpinan);
            if (file_exists($path)) unlink($path);
        }

        DB::table('tb_profil')->where('id_profil', $id)->delete();

        return back()->withFragment('profil')->with('success', 'Profil berhasil dihapus!');
    }

    // ============================================================
    // CRUD VIDEO
    // ============================================================
    public function videoStore(Request $request)
    {
        $request->validate([
            'video_kegiatan'   => 'required|file|mimes:mp4,mov,avi|max:50000',
            'video_keterangan' => 'required|string'
        ]);

        $file = $request->file('video_kegiatan');
        $namaFile = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('videos'), $namaFile);

        Video::create([
            'video_kegiatan'   => $namaFile,
            'video_keterangan' => $request->video_keterangan
        ]);

        return back()->withFragment('video')->with('success', 'Video berhasil diupload!');
    }

    public function videoUpdate(Request $request, $id)
    {
        $request->validate([
            'video_kegiatan'   => 'required|file|mimes:mp4,mov,avi|max:50000',
            'video_keterangan' => 'required|string'
        ]);

        $video = Video::findOrFail($id);
        $namaFile = $video->video_kegiatan;

        if ($request->hasFile('video_kegiatan')) {
            $old = public_path('videos/' . $video->video_kegiatan);
            if (file_exists($old)) unlink($old);

            $file = $request->file('video_kegiatan');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('videos'), $namaFile);
        }

        $video->update([
            'video_kegiatan'   => $namaFile,
            'video_keterangan' => $request->video_keterangan
        ]);

        return back()->withFragment('video')->with('success', 'Video berhasil diperbarui!');
    }

    public function videoDelete($id)
    {
        $video = Video::findOrFail($id);

        $path = public_path('videos/' . $video->video_kegiatan);
        if (file_exists($path)) unlink($path);

        $video->delete();

        return back()->withFragment('video')->with('success', 'Video berhasil dihapus!');
    }

    // ============================================================
    // CRUD KEGIATAN
    // ============================================================
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

        return back()->withFragment('agenda')->with('success', 'Kegiatan berhasil ditambahkan!');
    }

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

        return back()->withFragment('agenda')->with('success', 'Kegiatan berhasil diperbarui!');
    }



    public function kegiatanDelete($id)
    {
        Kegiatan::where('kegiatan_id', $id)->delete();
        return back()->withFragment('agenda')->with('success', 'Kegiatan berhasil dihapus!');
    }


    // ============================================================
    // CRUD RUNNING TEXT
    // ============================================================

    public function runningtextStore(Request $request)
    {
        $request->validate([
            'isi_text' => 'required|string|max:100',
        ]);

        RunningText::create([
            'isi_text' => $request->isi_text,
        ]);

        return back()->withFragment('runningtext')->with('success', 'Running text berhasil ditambahkan!');
    }

    public function runningtextUpdate(Request $request, $id)
    {
        $request->validate([
            'isi_text' => 'required|string|max:100',
        ]);

        RunningText::where('id_text', $id)->update([
            'isi_text' => $request->isi_text,
        ]);

        return back()->withFragment('runningtext')->with('success', 'Running text berhasil diperbarui!');
    }

    public function runningtextDelete($id)
    {
        RunningText::where('id_text', $id)->delete();

        return back()->withFragment('runningtext')->with('success', 'Running text berhasil dihapus!');
    }

    // ============================================================
    // NORMAL ADMIN CRUD — PK = id_admin
    // ============================================================
    public function normalAdminStore(Request $request)
    {
        $request->validate([
            'nama_admin'      => 'required|string|max:50',
            'bagian'          => 'required|string|max:50',
            'nip'             => 'required|digits:18',
            'password_admin'  => 'required|string|min:6|max:20',
            'role_admin'      => 'required|in:normaladmin,superadmin',
        ]);


        User::create([
            'nama_admin'     => $request->nama_admin,
            'bagian'         => $request->bagian,
            'nip'            => $request->nip,
            'password_admin' => Hash::make($request->password_admin),
            'role_admin'     => $request->role_admin,
        ]);


        return redirect()->back()->withFragment('normaladmin')->with('success', 'Normal admin berhasil ditambahkan!');
    }

    public function normalAdminUpdate(Request $request, $id)
    {
        $admin = User::where('id_admin', $id)->firstOrFail();

        $request->validate([
            'nama_admin' => 'required|string|max:30|unique:tb_admin,nama_admin,' . $id . ',id_admin',
            'bagian'     => 'required|string|max:30',
            'nip'        => 'required|digits:18',
            'password_admin' => 'nullable|string|min:6|max:20',
        ]);


        $admin->nama_admin = $request->nama_admin;
        $admin->bagian     = $request->bagian;
        $admin->nip = $request->nip;

        if ($request->filled('password_admin')) {
            $admin->password_admin = Hash::make($request->password_admin);
        }


        $admin->save();

        return back()->withFragment('normaladmin')->with('success', 'Normal admin berhasil diperbarui!');
    }

    public function normalAdminDelete($id)
    {
        User::where('id_admin', $id)->delete();
        return back()->withFragment('normaladmin')->with('success', 'Normal admin berhasil dihapus!');
    }
}
