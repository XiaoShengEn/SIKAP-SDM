<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function loginProcess(Request $request)
    {
        $admin = DB::table('tb_admin')
            ->where('email_admin', $request->email)
            ->first();

        if (!$admin) {
            return back()->withErrors(['Email tidak ditemukan']);
        }

        if ($admin->password_admin !== $request->password) {
            return back()->withErrors(['Password salah']);
        }

        session([
            'admin_id' => $admin->id_admin,
            'email'    => $admin->email_admin,
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
