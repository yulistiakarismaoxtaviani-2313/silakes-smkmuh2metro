<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisUjian;
use Illuminate\Http\Request;

class JenisUjianController extends Controller
{
    public function index() {
        $jenis_ujian = JenisUjian::paginate(10);
        return view('admin.manajemen.jenis_ujian.index', compact('jenis_ujian'));
    }

    public function create() {
        return view('admin.manajemen.jenis_ujian.create');
    }

    public function store(Request $request) {
        $request->validate(['nama_ujian' => 'required|string|max:255']);
        JenisUjian::create(['nama_ujian' => strtoupper($request->nama_ujian)]);
        return redirect()->route('admin.jenis-ujian.index')->with('success', 'Berhasil tambah jenis ujian');
    }

    public function edit($id) {
        $jenisUjian = JenisUjian::findOrFail($id);
        return view('admin.manajemen.jenis_ujian.edit', compact('jenisUjian'));
    }

    public function update(Request $request, $id) {
        $request->validate(['nama_ujian' => 'required|string|max:255']);
        $ju = JenisUjian::findOrFail($id);
        $ju->update(['nama_ujian' => strtoupper($request->nama_ujian)]);
        return redirect()->route('admin.jenis-ujian.index')->with('success', 'Berhasil update jenis ujian');
    }

    public function destroy($id) {
        JenisUjian::destroy($id);
        return back()->with('success', 'Data dihapus');
    }
}