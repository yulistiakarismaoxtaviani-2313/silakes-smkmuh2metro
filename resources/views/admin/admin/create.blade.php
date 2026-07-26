@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <div class="bg-[#004AAD] rounded-t-xl px-8 py-6 shadow-md flex justify-between items-center border-b border-white/10">
            <div>
                <h2 class="text-white font-bold text-xl tracking-wider uppercase">
                    Tambah Admin Baru
                </h2>
                <p class="text-white/60 text-[10px] mt-1 uppercase tracking-[0.2em]">Sistem Kesiswaan SMK Muhammadiyah 2 Metro</p>
            </div>
         
        </div>

        {{-- FORM CARD --}}
        <div class="bg-white rounded-b-xl shadow-xl border-x border-b border-gray-100 overflow-hidden">
            
            {{-- Alert Error --}}
            @if ($errors->any())
                <div class="bg-red-50 border-b border-red-200 p-6">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800 uppercase">Terjadi Kesalahan:</h3>
                            <ul class="mt-1 text-xs text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.admin.store') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf

                

                <div class="relative group">
    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1">
        Nama Admin
    </label>

    <input
        type="text"
        name="nama"
        value="{{ old('nama') }}"
        required
        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30"
        placeholder="Masukkan nama admin">
</div>

                <div class="relative group">
    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1">
        Username
    </label>

    <input
        type="text"
        name="username"
        value="{{ old('username') }}"
        required
        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30"
        placeholder="Masukkan username">
</div>

<div class="relative group">
    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1">
        Email
    </label>

    <input
        type="email"
        name="email"
        value="{{ old('email') }}"
        required
        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30"
        placeholder="Masukkan email">
</div>

<div class="relative group">
    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1">
        Password
    </label>

    <input
        type="password"
        name="password"
        required
        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30">
</div>

<div class="relative group">
    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1">
        Konfirmasi Password
    </label>

    <input
        type="password"
        name="password_confirmation"
        required
        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30">
</div>

<div class="relative group">

<label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1">
Role Admin
</label>

<select
name="role"
required
class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 bg-gray-50/30">

<option value="">Pilih Role Admin</option>

<option value="admin_presensi">
Admin Presensi
</option>

<option value="admin_prestasi">
Admin Prestasi
</option>

</select>

</div>

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-row items-center gap-4 pt-6 mt-4 md:pt-10 md:mt-8 border-t border-gray-50">
                    <a href="{{ route('admin.admin.index') }}" 
                        class="flex-1 px-4 py-4 bg-slate-100 text-slate-500 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" 
                        class="flex-1 bg-[#004AAD] text-white py-4 px-4 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-900/10 active:scale-[0.98]">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            const target = document.getElementById('file-name');
            target.textContent = "File terpilih: " + fileName;
            target.classList.remove('text-slate-400');
            target.classList.add('text-green-600');
        }
    }
</script>
@endsection