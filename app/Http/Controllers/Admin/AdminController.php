<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\TahunAjaran;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
{
    $admin = User::whereIn('role', [
        'admin_presensi',
        'admin_prestasi'
    ]);

    if ($request->filled('search')) {
        $admin->where(function ($query) use ($request) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    $admin = $admin->paginate(10);

    $totalAdmin = User::whereIn('role', [
    'admin_presensi',
    'admin_prestasi'
])->count();

$totalPresensi = User::where('role', 'admin_presensi')->count();

$totalPrestasi = User::where('role', 'admin_prestasi')->count();
$tahunAktif = TahunAjaran::where('status', 'aktif')->first();

    return view('admin.admin.index', compact(
    'admin',
    'totalAdmin',
    'totalPresensi',
    'tahunAktif',
    'totalPrestasi'
));
}

    public function create()
{
    return view('admin.admin.create');
}

    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:100',
        'username' => 'required|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'role' => 'required|in:admin_presensi,admin_prestasi',
    ]);

    User::create([
        'nama' => $request->nama,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);

    return redirect()
        ->route('admin.admin.index')
        ->with('success', 'Admin berhasil ditambahkan.');
}

    public function edit($id)
{
    $admin = User::findOrFail($id);

    return view('admin.admin.edit', compact('admin'));
}

    public function update(Request $request, $id)
{
    $admin = User::findOrFail($id);

    $request->validate([
        'nama' => 'required|string|max:100',
        'username' => 'required|unique:users,username,' . $admin->id_user . ',id_user',
        'email' => 'required|email|unique:users,email,' . $admin->id_user . ',id_user',
        'role' => 'required|in:admin_presensi,admin_prestasi',
    ]);

    $admin->nama = $request->nama;
    $admin->username = $request->username;
    $admin->email = $request->email;
    $admin->role = $request->role;

    if ($request->filled('password')) {
        $admin->password = Hash::make($request->password);
    }

    $admin->save();

    return redirect()
        ->route('admin.admin.index')
        ->with('success', 'Data admin berhasil diperbarui.');
}

    public function destroy($id)
{
    $admin = User::findOrFail($id);

    $admin->delete();

    return redirect()
        ->route('admin.admin.index')
        ->with('success', 'Data admin berhasil dihapus.');
}
}