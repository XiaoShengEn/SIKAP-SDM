<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // belum login
        if (!session()->has('admin_id')) {
            return redirect('/login');
        }

        // role tidak sesuai
        if (session('role') !== $role) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}
