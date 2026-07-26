<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semester = Semester::orderBy('id_semester', 'asc')->paginate(10);
        return view('admin.manajemen.semester.index', compact('semester'));
    }

    public function create()
    {
        return view('admin.manajemen.semester.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_semester' => 'required|string|max:20',
        'status' => 'required|in:aktif,nonaktif',
    ]);

    // Cari Tahun Ajaran yang sedang aktif saat ini
    $taAktif = \App\Models\TahunAjaran::where('status', 'aktif')->first();

    // Proteksi: Jika belum ada TA yang aktif, jangan kasih simpan semester
    if (!$taAktif) {
        return back()->with('error', 'Gagal! Aktifkan dulu salah satu Tahun Ajaran di menu Manajemen Tahun Ajaran.');
    }

    if ($request->status == 'aktif') {
        \App\Models\Semester::where('status', 'aktif')->update(['status' => 'nonaktif']);
    }

    // Masukkan data termasuk ID Tahun Ajaran yang aktif tadi
    \App\Models\Semester::create([
        'nama_semester' => $request->nama_semester,
        'status'        => $request->status,
        'id_tahun_ajaran' => $taAktif->id_tahun_ajaran, // Ini kuncinya!
    ]);

    return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil disimpan.');
}

    public function edit($id)
    {
        $semester = Semester::findOrFail($id);
        return view('admin.manajemen.semester.edit', compact('semester'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_semester' => 'required|string|max:20|unique:semester,nama_semester,' . $id . ',id_semester',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $sem = Semester::findOrFail($id);

        if ($request->status == 'aktif' && $sem->status == 'nonaktif') {
            Semester::where('status', 'aktif')->update(['status' => 'nonaktif']);
        }

        $sem->update($request->all());
        return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $sem = Semester::findOrFail($id);
            $sem->delete();
            return back()->with('success', 'Semester berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus. Data mungkin masih digunakan di tabel lain.');
        }
    }
}