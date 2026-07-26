<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Menampilkan daftar mata pelajaran
     */
    public function index()
    {
        $mapel = Mapel::orderBy('nama_mapel', 'asc')->paginate(10);
        $tahun_aktif = TahunAjaran::where('status', 'aktif')->first();
        return view('admin.manajemen.mapel.index', compact('mapel','tahun_aktif'));
    }

    /**
     * Menampilkan halaman form tambah
     */
    public function create()
    {
        return view('admin.manajemen.mapel.create');
    }

    /**
     * Menyimpan mata pelajaran baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel',
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran tidak boleh kosong!',
            'nama_mapel.unique'   => 'Mata pelajaran ini sudah ada di daftar.',
        ]);

        try {
            Mapel::create([
                'nama_mapel' => $request->nama_mapel
            ]);

            return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambah data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman form edit
     */
    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);
        return view('admin.manajemen.mapel.edit', compact('mapel'));
    }

    /**
     * Update data mata pelajaran
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel,' . $id . ',id_mapel',
        ]);

        try {
            $mapel = Mapel::findOrFail($id);
            $mapel->update([
                'nama_mapel' => $request->nama_mapel
            ]);

            return redirect()->route('admin.mapel.index')->with('success', 'Nama mata pelajaran berhasil diubah.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * Menghapus mata pelajaran
     */
    public function destroy($id)
    {
        try {
            $mapel = Mapel::findOrFail($id);
            $mapel->delete();
            return back()->with('success', 'Mata pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data.');
        }
    }
}