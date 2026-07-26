<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengumuman::where('status', 'aktif')
            ->whereIn('target', ['semua', 'guru'])
            ->orderBy('tanggal_tayang', 'desc');

        // Fitur Pencarian
        if ($request->has('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        $pengumuman = $query->get();
        
        // Ambil daftar kategori unik untuk filter
        $categories = Pengumuman::whereIn('target', ['semua', 'guru'])
            ->distinct()
            ->pluck('kategori');

        return view('guru.informasi-akademik.pengumuman.index', compact('pengumuman', 'categories'));
    }

    // Tambahkan ini di dalam class PengumumanController

public function show($id)
{
    // Mengambil data pengumuman berdasarkan ID
    // Tetap difilter agar guru hanya bisa akses yang targetnya 'semua' atau 'guru'
    $detail = \App\Models\Pengumuman::where('id_pengumuman', $id)
        ->whereIn('target', ['semua', 'guru'])
        ->where('status', 'aktif')
        ->firstOrFail(); // Akan muncul 404 jika ID tidak ditemukan atau target tidak sesuai

    return view('guru.informasi-akademik.pengumuman.show', compact('detail'));
}
}