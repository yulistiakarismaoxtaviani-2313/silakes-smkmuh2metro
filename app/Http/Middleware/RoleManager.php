<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $userRole = strtolower($user->role);
        $allowedRoles = array_map('strtolower', $roles);

        // 2. Cek apakah role user ada dalam daftar yang diizinkan (admin, guru, siswa)
        if (in_array($userRole, $allowedRoles)) {

            // 3. LOGIKA KHUSUS: Jika URL yang diakses mengandung 'walikelas'
            if ($request->is('walikelas') || $request->is('walikelas/*')) {
                
                // Cari data guru berdasarkan id_user login
                $guru = \App\Models\Guru::where('id_user', $user->id_user)->first();
                
                // Pastikan guru ada dan terdaftar di tabel kelas
                $isWali = $guru ? \Illuminate\Support\Facades\DB::table('kelas')
                            ->where('id_guru', $guru->id_guru)
                            ->exists() : false;

                if (!$isWali) {
                    abort(403, 'Akses Ditolak. Data Wali Kelas tidak ditemukan untuk akun Anda.');
                }
            }

            return $next($request);
        }

        // Jika role tidak cocok sama sekali
        abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
    }
}