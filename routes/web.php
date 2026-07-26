<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- IMPORT CONTROLLER ---
use App\Http\Controllers\Auth\AuthController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\ProfilController as AdminProfil;
use App\Http\Controllers\Admin\SiswaController; 
use App\Http\Controllers\Admin\GuruController; 
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\PresensiController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\PrestasiController as AdminPrestasi;
use App\Http\Controllers\Admin\RekapPresensiController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\ProgramKeahlianController;
use App\Http\Controllers\Admin\JenisUjianController;


//Guru Controllers
use App\Http\Controllers\Guru\ProfilController as GuruProfil;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Guru\PresensiController as GuruPresensiController;

// Siswa Controllers
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Siswa\PresensiController as SiswaPresensi;
use App\Http\Controllers\Siswa\ProfilController as SiswaProfil;
use App\Http\Controllers\Siswa\PrestasiController as SiswaPrestasi;
use App\Http\Controllers\Siswa\InformasiAkademikController;

// Wali Kelas Controllers
use App\Http\Controllers\WaliKelas\WaliKelasController;
use App\Http\Controllers\WaliKelas\ProfilController as WaliProfil;
use App\Http\Controllers\WaliKelas\DashboardController as WaliDashboard;
use App\Http\Controllers\WaliKelas\PresensiSiswaController;
use App\Http\Controllers\WaliKelas\WaliRekapController;
use App\Http\Controllers\WaliKelas\AkademikController; 

// --- ROUTE REDIRECT UTAMA ---
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        $role = strtolower($user->role);

        if ($role === 'guru') {
            // Logika cek relasi: User -> Guru -> Kelas
            $guru = \App\Models\Guru::where('id_user', $user->id_user)->first();
            $isWali = $guru ? \App\Models\Kelas::where('id_guru', $guru->id_guru)->exists() : false;
            
            return $isWali 
                ? redirect()->route('walikelas.dashboard') 
                : redirect()->route('guru.dashboard');
        }

        if (in_array($role, ['admin_presensi', 'admin_prestasi'])) {
    return redirect()->route('admin.dashboard');
}

$rolePath = str_replace(['_', ' '], '', $role);
return redirect()->to('/' . $rolePath . '/dashboard');
    }
    return view('welcome'); 
})->name('welcome');

// --- AUTHENTICATION (GUEST) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot.password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- SEMUA ROUTE YANG BUTUH LOGIN ---
Route::middleware('auth')->group(function () {
    
    // Alat Bantu Debugging (Akses: /debug-role)
    Route::get('/debug-role', function() {
        $user = Auth::user();
        $guru = \App\Models\Guru::where('id_user', $user->id_user)->first();
        $kelas = $guru ? \App\Models\Kelas::where('id_guru', $guru->id_guru)->first() : null;

        return [
            'Data_User_Login' => [
                'id_user' => $user->id_user,
                'role' => $user->role,
            ],
            'Data_Tabel_Guru' => $guru ? [
                'id_guru' => $guru->id_guru,
                'nama' => $guru->nama_guru,
                'id_user_di_tabel_guru' => $guru->id_user
            ] : 'TIDAK DITEMUKAN (id_user ini tidak ada di tabel guru)',
            'Data_Tabel_Kelas' => $kelas ? [
                'nama_kelas' => $kelas->nama_kelas,
                'id_guru_di_tabel_kelas' => $kelas->id_guru
            ] : 'TIDAK DITEMUKAN (id_guru ini tidak terdaftar sebagai wali di tabel kelas)',
        ];
    });

    // ==========================================
    // --- KHUSUS ROLE: ADMIN ---
    // ==========================================
    Route::middleware('role:admin_presensi,admin_prestasi')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/profil', [App\Http\Controllers\Admin\ProfilController::class, 'index'])->name('profil.index');
    Route::patch('/profil', [App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [App\Http\Controllers\Admin\ProfilController::class, 'updatePassword'])->name('profil.password');
     // ------------------------------------------

        Route::resource('admin', AdminController::class);
        Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
        Route::post('guru/import', [GuruController::class, 'import'])->name('guru.import');
        Route::post('kelas/import', [KelasController::class, 'import'])->name('kelas.import');

        Route::resource('mapel', MapelController::class);
        Route::resource('jenis-ujian', JenisUjianController::class);
        Route::resource('tahun-ajaran', TahunAjaranController::class);
        Route::resource('semester', SemesterController::class);
        Route::resource('program-keahlian', ProgramKeahlianController::class);
        Route::resource('siswa', SiswaController::class);
        Route::put('siswa/{id}/reset-password', [SiswaController::class, 'resetPassword'])->name('siswa.reset-password');
        Route::resource('guru', GuruController::class);
        Route::put('guru/{id}/reset-password', [GuruController::class, 'resetPassword'])->name('guru.reset-password');
        Route::resource('kelas', KelasController::class);
        Route::resource('pengumuman', PengumumanController::class);
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/pelajaran/create', [JadwalController::class, 'createPelajaran'])->name('jadwal.pelajaran.create');
        Route::get('/jadwal/ujian/create', [JadwalController::class, 'createUjian'])->name('jadwal.ujian.create');
        Route::get('/jadwal/pelajaran/{id_kelas}', [JadwalController::class, 'showPelajaran'])->name('jadwal.pelajaran.show');
        Route::get('/jadwal/pelajaran/edit/{id_kelas}', [JadwalController::class, 'editPelajaran'])->name('jadwal.pelajaran.edit');
        Route::put('/jadwal/pelajaran/update/{id_kelas}', [JadwalController::class, 'updatePelajaran'])->name('jadwal.pelajaran.update');
        Route::delete('/jadwal/pelajaran/destroy/{id_kelas}', [JadwalController::class, 'destroyPelajaran'])->name('jadwal.pelajaran.destroy');
        Route::get('/jadwal/ujian/{id_kelas}', [JadwalController::class, 'showUjian'])->name('jadwal.ujian.show'); // Rute yang tadi error
        Route::put('/jadwal/ujian/update/{id_kelas}', [JadwalController::class, 'updateUjian'])->name('jadwal.ujian.update');
        Route::get('/jadwal/ujian/edit/{id_kelas}', [JadwalController::class, 'editUjian'])->name('jadwal.ujian.edit');
        Route::delete('/jadwal/ujian/destroy/{id_kelas}', [JadwalController::class, 'destroyUjian'])->name('jadwal.ujian.destroy');
        Route::post('/jadwal/pelajaran/store', [JadwalController::class, 'storePelajaran'])->name('jadwal.pelajaran.store');
        Route::post('/jadwal/ujian/store', [JadwalController::class, 'storeUjian'])->name('jadwal.ujian.store');

    });

    // ==================================================
// ADMIN PRESENSI
// ==================================================
        
Route::middleware('role:admin_presensi')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::resource('presensi', PresensiController::class);

    Route::get('/rekap-presensi', [RekapPresensiController::class, 'index'])->name('rekap.index');
    Route::get('/rekap-presensi/{id}', [RekapPresensiController::class, 'show'])->name('rekap.show');
    Route::get('/rekap-presensi/{id}/download', [RekapPresensiController::class, 'download'])->name('rekap.download');
    Route::get('/rekap-presensi/{id}/download-excel', [RekapPresensiController::class, 'downloadExcel'])->name('rekap.download.excel');

    Route::get('/presensi/{presensi}/kelas/{kelas}', [PresensiController::class, 'showKelas'])->name('presensi.kelas.detail');

});

// ==================================================
// ADMIN PRESTASI
// ==================================================

Route::middleware('role:admin_prestasi')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::resource('prestasi', AdminPrestasi::class);

    Route::patch('prestasi/{id}/validasi', [AdminPrestasi::class, 'validasi'])->name('prestasi.validasi');

    Route::get('prestasi/rekap/pdf', [AdminPrestasi::class, 'downloadPdf'])->name('prestasi.rekap.pdf');

    Route::get('prestasi/rekap/excel', [AdminPrestasi::class, 'downloadExcel'])->name('prestasi.rekap.excel');

});


    
   // ==========================================
// --- KHUSUS ROLE: GURU (BIASA) ---
// ==========================================
Route::middleware('role:guru')->prefix('guru')->name('guru.')->group(function () {
    
    // 1. Dashboard dengan Auto-Redirect Wali Kelas
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $guru = \App\Models\Guru::where('id_user', $user->id_user)->first();
        $isWali = $guru ? \App\Models\Kelas::where('id_guru', $guru->id_guru)->exists() : false;

        if ($isWali) return redirect()->route('walikelas.dashboard');
        return view('guru.dashboard');
    })->name('dashboard');

    // 2. Profil Guru (MENGGUNAKAN ALIAS GuruProfil)
    // Panggil Controller, jangan function anonim
    Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [GuruProfil::class, 'index'])->name('index');
        Route::get('/edit', [GuruProfil::class, 'edit'])->name('edit');
        Route::post('/update', [GuruProfil::class, 'update'])->name('update');
        Route::post('update-foto', [GuruProfil::class, 'updateFoto'])->name('updateFoto');
        Route::post('/profil/update-password', [GuruProfil::class, 'updatePassword'])->name('updatePassword');
    });

    // 3. Menu Lainnya
    Route::get('/presensi', [\App\Http\Controllers\Guru\PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/{id}', [\App\Http\Controllers\Guru\PresensiController::class, 'show'])->name('presensi.show');
    Route::post('/presensi/confirm/{id}', [\App\Http\Controllers\Guru\PresensiController::class, 'confirm'])->name('presensi.confirm');
    Route::post('/presensi/update-status/{id}', [\App\Http\Controllers\Guru\PresensiController::class, 'updateStatusDetail'])->name('presensi.updateStatus');
    Route::post('/presensi/mark-reminded/{id}', [\App\Http\Controllers\Guru\PresensiController::class, 'markAllReminded'])->name('presensi.markAllReminded');
    Route::get('/jadwal', [App\Http\Controllers\Guru\JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/download-mengajar', [\App\Http\Controllers\Guru\JadwalController::class, 'downloadMengajar'])->name('jadwal.download.mengajar');
    Route::get('/jadwal/download-ujian', [\App\Http\Controllers\Guru\JadwalController::class, 'downloadUjian'])->name('jadwal.download.ujian');
    Route::get('/pengumuman', [\App\Http\Controllers\Guru\PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/{id}', [\App\Http\Controllers\Guru\PengumumanController::class, 'show'])->name('pengumuman.show');
});



    // ==========================================
    // --- KHUSUS ROLE: WALI KELAS ---
    // ==========================================
    Route::middleware('role:guru,walikelas')->prefix('walikelas')->name('walikelas.')->group(function () {
        Route::get('/dashboard', [WaliDashboard::class, 'index'])->name('dashboard');

        // 2. Profil Guru (MENGGUNAKAN ALIAS GuruProfil)
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [WaliProfil::class, 'index'])->name('index');
        Route::get('/edit', [WaliProfil::class, 'edit'])->name('edit');
        Route::post('/update', [WaliProfil::class, 'update'])->name('update');
        Route::post('update-foto', [WaliProfil::class, 'updateFoto'])->name('updateFoto');
        Route::post('/profil/update-password', [WaliProfil::class, 'updatePassword'])->name('updatePassword');
        Route::get('/download-kartu', [WaliProfil::class, 'downloadKartu'])->name('download-kartu');
    });
        Route::get('/data-siswa', [WaliKelasController::class, 'index'])->name('siswa.index');   
        Route::get('/data-siswa/{id}', [WaliKelasController::class, 'show'])->name('siswa.show');
        // Presensi Kelas
        Route::get('/presensi-kelas', [PresensiSiswaController::class, 'index'])->name('presensi.kelas');
        Route::get('/presensi-kelas/{nis}', [PresensiSiswaController::class, 'show'])->name('presensi.show');
        // Presensi Mengajar
        Route::get('/presensi-mengajar', [GuruPresensiController::class, 'index'])->name('presensi.mengajar');
        Route::get('/presensi-mengajar/{id}', [GuruPresensiController::class, 'show'])->name('presensi.mengajar.show');
        Route::post('/presensi-mengajar/{id}/confirm', [GuruPresensiController::class, 'confirm'])->name('presensi.confirm');
        Route::post('/presensi-mengajar/{id}/mark-all-reminded', [GuruPresensiController::class, 'markAllReminded'])->name('presensi.markAllReminded');
        Route::post('/presensi-mengajar/update-status/{id}', [GuruPresensiController::class,'updateStatusDetail'])->name('presensi.updateStatus');
        Route::get('/rekap-presensi', [WaliRekapController::class, 'index'])->name('rekap.index');
        Route::get('/rekap-presensi/pdf', [WaliRekapController::class, 'exportPdf'])->name('rekap.pdf');
        Route::get('/jadwal', [AkademikController::class, 'indexJadwal'])->name('jadwal.index');
        Route::get('/jadwal/cetak-mengajar', [AkademikController::class, 'downloadMengajar'])->name('jadwal.download.mengajar');
        Route::get('/jadwal/cetak-ujian', [AkademikController::class, 'downloadUjian'])->name('jadwal.download.ujian');
        Route::get('/pengumuman', [AkademikController::class, 'indexPengumuman'])->name('pengumuman.index');
        Route::get('/pengumuman/{id}', [AkademikController::class, 'showPengumuman'])->name('pengumuman.show');
    });

    // ==========================================
    // --- KHUSUS ROLE: SISWA ---
    // ==========================================
    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
        Route::get('/presensi', [SiswaPresensi::class, 'index'])->name('presensi.index');
        Route::get('/presensi/form/{id}', [SiswaPresensi::class, 'create'])->name('presensi.form');
        Route::post('/presensi/store', [SiswaPresensi::class, 'store'])->name('presensi.store');
        Route::get('/presensi/rekap', [SiswaPresensi::class, 'rekap'])->name('presensi.rekap');
        Route::get('/presensi/unduh',[SiswaPresensi::class, 'unduhRekap'])->name('presensi.unduh');

        Route::prefix('prestasi')->name('prestasi.')->group(function () {
            Route::get('/', [SiswaPrestasi::class, 'index'])->name('index'); 
            Route::get('/create', [SiswaPrestasi::class, 'create'])->name('create');
            Route::get('/{id}', [SiswaPrestasi::class, 'show'])->name('show'); 
            Route::post('/store', [SiswaPrestasi::class, 'store'])->name('store'); 
            Route::delete('/{id}', [SiswaPrestasi::class, 'destroy'])->name('destroy'); 
            
           
        });

        Route::prefix('profil')->name('profil.')->group(function () {
            Route::get('/', [SiswaProfil::class, 'index'])->name('index'); 
            Route::get('/download-kartu', [SiswaProfil::class, 'downloadKartu'])->name('download-kartu');
            Route::get('/perbarui-profil', [SiswaProfil::class, 'editFoto'])->name('edit-foto');
            Route::post('/update-foto', [SiswaProfil::class, 'updateFoto'])->name('updateFoto');
            Route::get('/edit-pribadi', [SiswaProfil::class, 'editPribadi'])->name('edit-pribadi');
            Route::put('/update-pribadi', [SiswaProfil::class, 'updatePribadi'])->name('updatePribadi');
            Route::get('/edit-kontak', [SiswaProfil::class, 'editKontak'])->name('edit-kontak');
            Route::put('/update-kontak', [SiswaProfil::class, 'updateKontak'])->name('updateKontak');
            Route::get('/edit-ortu', [SiswaProfil::class, 'editOrtu'])->name('edit-ortu');
            Route::put('/update-ortu', [SiswaProfil::class, 'updateOrtu'])->name('updateOrtu');
            Route::get('/edit-password', [SiswaProfil::class, 'editPassword'])->name('edit-password');
            Route::put('/update-password', [SiswaProfil::class, 'updatePassword'])->name('updatePassword');
            Route::get('/edit-program', [SiswaProfil::class, 'editProgram'])->name('edit-program');
            Route::put('/update-program', [SiswaProfil::class, 'updateProgram'])->name('updateProgram');
        });

        Route::prefix('informasi-akademik')->name('informasi.')->group(function () {
            Route::get('/jadwal', [InformasiAkademikController::class, 'jadwal'])->name('jadwal');
            Route::get('/jadwal/download/{type}', [InformasiAkademikController::class, 'downloadPDF'])->name('jadwal.download');
            Route::get('/pengumuman', [InformasiAkademikController::class, 'indexPengumuman'])->name('pengumuman.index');
            Route::get('/pengumuman/{id}', [InformasiAkademikController::class, 'showPengumuman'])->name('pengumuman.show');
        });
    });
    });