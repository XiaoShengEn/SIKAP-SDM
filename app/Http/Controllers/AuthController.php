<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function loginProcess(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required'
        ]);

        $admin = DB::table('tb_admin')
            ->where('nip', $request->nip)
            ->first();

        if (!$admin) {
            return back()->withErrors(['NIP tidak ditemukan']);
        }

        if (!Hash::check($request->password, $admin->password_admin)) {
            return back()->withErrors(['Password salah']);
        }

        session([
            'admin_id' => $admin->id_admin,
            'nip'      => $admin->nip,
            'role'     => $admin->role_admin
        ]);

        if ($admin->role_admin === 'superadmin') {
            return redirect('/superadmin');
        }

        if ($admin->role_admin === 'normaladmin') {
            return redirect('/admin');
        }

        return back()->withErrors(['Role tidak dikenal']);
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}
