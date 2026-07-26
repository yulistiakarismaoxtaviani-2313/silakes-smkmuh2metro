<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramKeahlian;
use App\Models\TahunAjaran; 
use Illuminate\Http\Request;

class ProgramKeahlianController extends Controller
{
    public function index()
    {
        $programKeahlian = ProgramKeahlian::orderBy('kode_program', 'asc')->paginate(10);
         $tahun_aktif = TahunAjaran::where('status', 'aktif')->first();
        return view('admin.manajemen.program_keahlian.index', compact('programKeahlian','tahun_aktif'));
    }

    public function create()
    {
        return view('admin.manajemen.program_keahlian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|string|max:255',
            'kode_program' => 'required|string|max:10|unique:program_keahlian,kode_program',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        ProgramKeahlian::create($request->all());

        return redirect()->route('admin.program-keahlian.index')->with('success', 'Program keahlian berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pk = ProgramKeahlian::findOrFail($id);
        return view('admin.manajemen.program_keahlian.edit', compact('pk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_program' => 'required|string|max:255',
            'kode_program' => 'required|string|max:10|unique:program_keahlian,kode_program,' . $id . ',id_program_keahlian',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        $pk = ProgramKeahlian::findOrFail($id);
        $pk->update($request->all());

        return redirect()->route('admin.program-keahlian.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $pk = ProgramKeahlian::findOrFail($id);

            // Cek apakah digunakan di tabel lain (misalnya tabel jurusan atau kelas)
            // Pastikan Anda sudah membuat relasi di model ProgramKeahlian
            // Contoh: if ($pk->jurusan()->exists()) { ... }
            
            $pk->delete();
            return back()->with('success', 'Program keahlian berhasil dihapus.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem atau data sedang digunakan.');
        }
    }
}