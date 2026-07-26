<?php

namespace App\Http\Controllers;

use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::all(); // ambil semua data siswa
        return view('siswa.index', compact('siswa'));
    }
}