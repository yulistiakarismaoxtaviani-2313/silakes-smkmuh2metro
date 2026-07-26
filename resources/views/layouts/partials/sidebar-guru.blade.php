<aside class="flex flex-col h-full bg-[#004AAD] font-poppins overflow-hidden w-full text-white transition-all duration-300"
       x-data="{
           openMenu: '{{ request()->routeIs('guru.jadwal.*', 'guru.pengumuman.*') ? 'informasi' : '' }}'
       }">

    @php
        // 1. Konfigurasi Menu Utama (Single Menu)
        $mainMenus = [
            [
                'name' => 'Dashboard', 
                'icon' => 'fas fa-tachometer-alt', 
                'route' => 'guru.dashboard', 
                'active' => request()->routeIs('guru.dashboard')
            ],
            [
                'name' => 'Presensi Siswa', 
                'icon' => 'fas fa-calendar-check', 
                'route' => 'guru.presensi.index', 
                'active' => request()->routeIs('guru.presensi.*')
            ],
        ];

        // 2. Menu di dalam Dropdown "Informasi Akademik"
        $academicMenus = [
            [
                'name' => 'Jadwal', 
                'route' => 'guru.jadwal.index', 
                'active' => request()->routeIs('guru.jadwal.*')
            ],
            [
                'name' => 'Pengumuman', 
                'route' => 'guru.pengumuman.index', 
                'active' => request()->routeIs('guru.pengumuman.*')
            ],
        ];

        // 3. Logika Penentuan Menu Aktif untuk Header & State
        $allMenus = array_merge($mainMenus, $academicMenus);
        $currentMenu = collect($allMenus)->firstWhere('active', true) ?? ['name' => 'Dashboard'];
        $isAcademicActive = collect($academicMenus)->contains('active', true);
    @endphp

    <div class="w-full flex flex-col h-full overflow-hidden">
        <!-- HEADER (Logo & Judul Menu Aktif) -->
        <div class="px-6 py-3 flex items-center border-b border-white/10 flex-shrink-0">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 flex justify-center items-center flex-shrink-0 bg-white/10 rounded-xl p-2 shadow-inner">
                    <img src="{{ asset('img/logo-smk1.png') }}" alt="Logo SMK" class="h-full w-full object-contain">
                </div>
                <div class="flex flex-col relative justify-center">
                    <h1 class="text-[14px] font-bold text-white tracking-wider uppercase leading-tight">
                        {{ $currentMenu['name'] }}
                    </h1>
                    <div class="mt-1 w-full h-[3px] bg-blue-400 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- NAVIGATION AREA -->
        <nav class="flex-1 overflow-y-auto mt-4 px-4 custom-scrollbar">
            <p class="text-[10px] font-bold text-blue-300/60 uppercase tracking-[0.2em] px-2 mb-4">
                Daftar Menu
            </p>

            <div class="flex flex-col gap-1 pb-24">
                {{-- Loop Menu Utama --}}
                @foreach($mainMenus as $menu)
                    <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}"
                       class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 group {{ $menu['active'] ? 'bg-white text-[#004AAD] font-bold shadow-lg shadow-blue-900/20' : 'hover:bg-white/10 text-blue-100' }}">
                        
                        <div class="w-6 flex justify-center">
                            <i class="{{ $menu['icon'] }} text-lg {{ $menu['active'] ? 'text-[#004AAD]' : 'text-blue-300 group-hover:text-white' }}"></i>
                        </div>
                        
                        <span class="text-[13px] font-reguler tracking-wide">
                            {{ $menu['name'] }}
                        </span>
                    </a>
                @endforeach

                {{-- Dropdown Informasi Akademik --}}
                @include('layouts.partials.sidebar-item-dropdown', [
                    'id' => 'informasi',
                    'name' => 'Informasi Akademik',
                    'icon' => 'fas fa-info-circle',
                    'isActive' => $isAcademicActive,
                    'subMenus' => $academicMenus
                ])
            </div>
        </nav>

        <!-- FOOTER SIDEBAR -->
        <div class="p-4 border-t border-white/5 flex-shrink-0">
            <div class="bg-blue-800/40 rounded-lg p-3 text-center">
                <p class="text-[10px] text-blue-200 font-medium tracking-tight uppercase">SMK Muhammadiyah 2 Metro</p>
            </div>
        </div>
    </div>
</aside>