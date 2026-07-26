<header class="bg-white text-slate-800 px-4 py-3 flex justify-between items-center sticky top-0 z-50 w-full flex-shrink-0 font-poppins border-b border-slate-50">
    <div class="flex items-center gap-6">
        {{-- Tombol Toggle Sidebar --}}
        <button @click="sidebarOpen = !sidebarOpen" class="text-[#004AAD] hover:bg-blue-50 p-2 rounded-lg transition-colors focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <div class="flex items-center gap-4">
            <img src="{{ asset('img/logo-smk.png') }}" alt="Logo SMK" class="h-14 w-auto object-contain">
        </div>
    </div>

    <div class="flex items-center gap-4">
        {{-- Bagian Nama dan Role --}}
        <div class="flex items-center gap-4 pr-4 border-r border-slate-100">
            <div class="text-right hidden sm:block leading-tight">
                <p class="text-[10px] font-bold text-[#004AAD] uppercase tracking-widest leading-none">
                    @php
                        $roleDisplay = Auth::user()->role;
                        if(Auth::user()->role == 'guru') {
                            $guru = \App\Models\Guru::where('id_user', Auth::id())->first();
                            $isWali = $guru ? \App\Models\Kelas::where('id_guru', $guru->id_guru)->exists() : false;
                            $roleDisplay = $isWali ? 'Wali Kelas' : 'Guru';
                        }
                    @endphp
                    {{ $roleDisplay }}
                </p>
                <p class="text-xs font-black text-[#004AAD] uppercase tracking-tight mt-1">
                    {{ Auth::user()->nama }}
                </p>
            </div>
        </div>

        {{-- Dropdown Profile --}}
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <div @click="open = !open" class="flex items-center gap-2 cursor-pointer group">
                <div class="relative">
                    <div class="w-11 h-11 bg-slate-50 rounded-full flex items-center justify-center border border-slate-100 transition-all group-hover:scale-105 overflow-hidden">
                        @if(Auth::user()->photo && file_exists(public_path('storage/profil/' . Auth::user()->photo)))
                            <img src="{{ asset('storage/profil/' . Auth::user()->photo) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-[#004AAD] font-black text-sm uppercase">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    {{-- Indikator Status Online --}}
                    <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-[#00D084] border-2 border-white rounded-full"></div>
                </div>
                
                <svg class="w-4 h-4 text-slate-300 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </div>

            {{-- Menu Dropdown --}}
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 class="absolute right-0 mt-3 w-52 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50 overflow-hidden" 
                 style="display: none;">
                
                @php
    $targetRoute = '#';

    if (in_array(Auth::user()->role, ['admin_presensi', 'admin_prestasi'])) {
        $targetRoute = route('admin.profil.index');
    }
    elseif (Auth::user()->role == 'siswa') {
        $targetRoute = route('siswa.profil.index');
    }
    elseif (Auth::user()->role == 'guru') {
        $guru = \App\Models\Guru::where('id_user', Auth::id())->first();
        $isWali = $guru
            ? \App\Models\Kelas::where('id_guru', $guru->id_guru)->exists()
            : false;

        $targetRoute = $isWali
            ? route('walikelas.profil.index')
            : route('guru.profil.index');
    }
@endphp

                <a href="{{ $targetRoute }}" 
                   class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-[#004AAD] transition-colors uppercase">
                    <svg class="w-4 h-4 text-[#004AAD]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profil Saya
                </a>

                @if(in_array(Auth::user()->role, ['admin_presensi', 'admin_prestasi']))
    <a href="{{ route('admin.admin.index') }}"
       class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-[#004AAD] transition-colors uppercase">

        <svg class="w-4 h-4 text-[#004AAD]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-8 0v2m8 0H9m8-10a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>

        Kelola Admin
    </a>
@endif

                <hr class="my-1 border-slate-50">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-xs font-bold text-red-500 hover:bg-red-50 transition-colors uppercase text-left focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>