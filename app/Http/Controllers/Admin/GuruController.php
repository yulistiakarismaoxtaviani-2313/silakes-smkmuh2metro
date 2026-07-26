<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru; 
use App\Models\ProfilGuru;
use App\Models\TahunAjaran;
use App\Imports\GuruImport;
use App\Models\Mapel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $data_tahun_ajaran = TahunAjaran::all();
        $query = Guru::with(['user', 'profilGuru', 'kelas', 'mapel']);
        $mapels = Mapel::orderBy('nama_mapel')->get();

        // Filter Wali Kelas berdasarkan relasi (Tabel Kelas)
        if ($request->filled('is_wali')) {
            $request->is_wali == 'ya' ? $query->has('kelas') : $query->doesntHave('kelas');
        }

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nip', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', fn($qu) => $qu->where('nama', 'LIKE', "%{$search}%"));
            });
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('mapel')) {
    $query->whereHas('mapel', function ($q) use ($request) {
        $q->where('nama_mapel', $request->mapel);
    });
}

        $guru = $query->orderBy('nip', 'desc')->paginate(10)->withQueryString();
        $totalGuru = Guru::count();
        $totalAktif = Guru::where('status', 'aktif')->count();
        $totalNonaktif = Guru::where('status', '!=', 'aktif')->count();

        return view('admin.guru.index', compact(
            'guru', 'totalGuru', 'totalAktif', 'totalNonaktif', 'tahunAktif', 'data_tahun_ajaran', 'mapels'
        ));
    }

    public function create()
    {
        $mapels = Mapel::all();
        return view('admin.guru.create', compact('mapels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'           => 'required|unique:guru,nip', 
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'mapel' => 'required|array',
            'status'        => 'required|in:aktif,nonaktif',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $namaFoto = null;
            if ($request->hasFile('foto')) {
                $namaFoto = $request->nip . '_' . time() . '.' . $request->foto->extension();
                $request->foto->move(public_path('storage/profil/'), $namaFoto);
            }

            $user = User::create([
                'nama'     => strtoupper($request->nama),
                'email'    => $request->nip . '@guru.com',
                'username' => $request->nip,
                'password' => Hash::make($request->nip), 
                'role'     => 'guru',
                'photo'    => $namaFoto ?? '', 
            ]);

            $guru = Guru::create([
                'id_user'       => $user->id_user ?? $user->id,
                'nip'           => $request->nip,
                'jenis_kelamin' => $request->jenis_kelamin,
                'status'        => $request->status,
                'foto'          => $namaFoto,
            ]);

            ProfilGuru::create([
                'id_guru' => $guru->id_guru, 
            ]);

            $guru->mapel()->attach($request->mapel);

            DB::commit();
            return redirect()->route('admin.guru.index')->with('success', 'Data Guru dan Profil Berhasil Disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Gagal simpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.show', compact('guru'));
    }

    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        $mapels = Mapel::all();
        return view('admin.guru.edit', compact('guru', 'mapels'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        $request->validate([
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'mapel' => 'required|array',
            'status'        => 'required',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $user = User::findOrFail($guru->id_user);
            $user->nama = strtoupper($request->nama);

            if ($request->hasFile('foto')) {
                if ($guru->foto && file_exists(public_path('storage/profil/' . $guru->foto))) {
                    unlink(public_path('storage/profil/' . $guru->foto));
                }
                $namaFoto = $guru->nip . '_' . time() . '.' . $request->foto->extension();
                $request->foto->move(public_path('storage/profil/'), $namaFoto);
                $user->photo = $namaFoto;
                $guru->foto = $namaFoto;
            }

            $user->save();
            $guru->update([
                'jenis_kelamin' => $request->jenis_kelamin,
                'status'        => $request->status,
                'foto'          => $guru->foto,
            ]);

            ProfilGuru::updateOrCreate(
                ['id_guru' => $guru->id_guru],
                []
            );

            $guru->mapel()->sync($request->mapel);

            DB::commit();
            return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
{
    $request->validate(['file_excel' => 'required|mimes:xlsx,xls,csv|max:2048']);

    try {
        DB::transaction(function () use ($request) {
            Excel::import(new GuruImport, $request->file('file_excel'));
        });
        
        return redirect()->route('admin.guru.index')->with('success', 'Data berhasil diimport!');
    } catch (\Exception $e) {
        // Jika ada error, tampilkan pesan agar kita tahu masalahnya
        return back()->with('error', 'Gagal import: ' . $e->getMessage());
    }
}

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        try {
            DB::beginTransaction();
            if ($guru->foto && file_exists(public_path('storage/profil/' . $guru->foto))) {
                unlink(public_path('storage/profil/' . $guru->foto));
            }
            ProfilGuru::where('id_guru', $guru->id_guru)->delete();
            $userId = $guru->id_user;
            $guru->mapel()->detach();
            $guru->delete();
            if ($userId) { User::where('id_user', $userId)->delete(); }
            DB::commit();
            return redirect()->route('admin.guru.index')->with('success', 'Data berhasil dihapus!')->withQueryString();

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    public function resetPassword($id)
{
    $guru = Guru::findOrFail($id);

    $user = User::findOrFail($guru->id_user);

    $user->update([
        'password' => Hash::make($guru->nip)
    ]);

    return back()->with(
        'success',
        'Password guru berhasil direset. Password default menggunakan NBM guru.'
    );
}
}