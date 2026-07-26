<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran; // Pastikan Model sudah ada
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->paginate(10);
        return view('admin.manajemen.tahun_ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('admin.manajemen.tahun_ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:10|unique:tahun_ajaran,tahun_ajaran',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Jika status yang diinput adalah 'aktif', nonaktifkan tahun ajaran lain terlebih dahulu
        if ($request->status == 'aktif') {
            TahunAjaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
        }

        TahunAjaran::create($request->all());

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ta = TahunAjaran::findOrFail($id);
        return view('admin.manajemen.tahun_ajaran.edit', compact('ta'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:10|unique:tahun_ajaran,tahun_ajaran,' . $id . ',id_tahun_ajaran',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $ta = TahunAjaran::findOrFail($id);

        // Logika agar hanya ada satu tahun ajaran yang aktif
        if ($request->status == 'aktif' && $ta->status == 'nonaktif') {
            TahunAjaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
        }

        $ta->update($request->all());

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
{
    try {
        $ta = TahunAjaran::findOrFail($id);

        // Cek apakah digunakan di tabel Kelas
        // Asumsi kamu punya relasi 'kelas' di Model TahunAjaran
        if ($ta->kelas()->exists()) {
            return back()->with('error', 'Gagal: Tahun ajaran ini tidak bisa dihapus karena masih digunakan oleh data Kelas.');
        }

        $ta->delete();
        return back()->with('success', 'Tahun ajaran berhasil dihapus.');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan sistem.');
    }
}
}