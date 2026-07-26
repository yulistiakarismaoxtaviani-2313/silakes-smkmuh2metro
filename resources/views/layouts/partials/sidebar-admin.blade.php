<aside class="flex flex-col h-full bg-[#004AAD] font-poppins overflow-hidden w-full text-white transition-all duration-300" 
       x-data="{ 
           openMenu: '{{ request()->routeIs('admin.mapel.*') || request()->routeIs('admin.tahun-ajaran.*') || request()->routeIs('admin.semester.*') || request()->routeIs('admin.jenis-ujian.*') || request()->routeIs('admin.program-keahlian.*') ? 'manajemen' : '' }}' 
       }">
    
    @php
        // 1. Definisi Menu Utama
        $mainMenus = [
            ['name' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'route' => 'admin.dashboard', 'active' => request()->routeIs('admin.dashboard')],
            ['name' => 'Kelola Siswa', 'icon' => 'fas fa-user-graduate', 'route' => 'admin.siswa.index', 'active' => request()->routeIs('admin.siswa.*')],
            ['name' => 'Kelola Guru', 'icon' => 'fas fa-chalkboard-teacher', 'route' => 'admin.guru.index', 'active' => request()->routeIs('admin.guru.*')],
            ['name' => 'Kelola Kelas', 'icon' => 'fas fa-school', 'route' => 'admin.kelas.index', 'active' => request()->routeIs('admin.kelas.*')],
            ['name' => 'Kelola Presensi', 'icon' => 'fas fa-calendar-check', 'route' => 'admin.presensi.index', 'active' => request()->routeIs('admin.presensi.*')],
            ['name' => 'Kelola Pengumuman', 'icon' => 'fas fa-bullhorn', 'route' => 'admin.pengumuman.index', 'active' => request()->routeIs('admin.pengumuman.*')],
            ['name' => 'Kelola Jadwal', 'icon' => 'fas fa-calendar-alt', 'route' => 'admin.jadwal.index', 'active' => request()->routeIs('admin.jadwal.*')],
            ['name' => 'Prestasi Siswa', 'icon' => 'fas fa-trophy', 'route' => 'admin.prestasi.index', 'active' => request()->routeIs('admin.prestasi.*')],
            ['name' => 'Rekap Presensi', 'icon' => 'fas fa-file-invoice', 'route' => 'admin.rekap.index', 'active' => request()->routeIs('admin.rekap.*')],
        ];

        // 2. Definisi Sub Menu (Manajemen Data)
        $manajemenMenus = [
            ['name' => 'Mata Pelajaran', 'route' => 'admin.mapel.index', 'active' => request()->routeIs('admin.mapel.*')],
            ['name' => 'Jenis Ujian', 'route' => 'admin.jenis-ujian.index', 'active' => request()->routeIs('admin.jenis-ujian.*')],
            ['name' => 'Tahun Ajaran', 'route' => 'admin.tahun-ajaran.index', 'active' => request()->routeIs('admin.tahun-ajaran.*')],
            ['name' => 'Semester', 'route' => 'admin.semester.index', 'active' => request()->routeIs('admin.semester.*')],
            ['name' => 'Program Keahlian', 'route' => 'admin.program-keahlian.index', 'active' => request()->routeIs('admin.program-keahlian.*')],

        ];

        // 3. Logika Header Sama Persis dengan Siswa (Agar Header Berubah sesuai Sub-Menu)
        $allMenusForHeader = array_merge($mainMenus, $manajemenMenus);
        $currentMenu = collect($allMenusForHeader)->firstWhere('active', true) ?? ['name' => 'Dashboard'];

        $isManajemenActive = collect($manajemenMenus)->contains('active', true);
    @endphp

    <div class="w-full flex flex-col h-full">
        <!-- HEADER SIDEBAR -->
        <div class="px-6 py-3 flex items-center border-b border-white/10 flex-shrink-0">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 flex justify-center items-center flex-shrink-0 bg-white/10 rounded-xl p-2 shadow-inner">
                    <img src="{{ asset('img/logo-smk1.png') }}" alt="Logo SMK" class="h-full w-full object-contain">
                </div>
                
                <div class="flex flex-col relative justify-center">
                    <h1 class="text-[14px] font-bold text-white tracking-wider uppercase leading-tight">
                        {{ $currentMenu['name'] }}
                    </h1>
                    <div class="mt-1 w-4/4 h-[3px] bg-blue-400 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- NAVIGATION AREA -->
        <nav class="flex-1 overflow-y-auto mt-4 px-4 custom-scrollbar">
            <p class="text-[10px] font-bold text-blue-300/60 uppercase tracking-[0.2em] px-2 mb-4">
                Daftar Menu
            </p>

            <div class="flex flex-col gap-1">
                {{-- Render Menu Utama --}}
                @foreach($mainMenus as $menu)
                    <a href="{{ Route::has($menu['route']) ? route($menu['route']) : 'javascript:void(0)' }}" 
                       class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 group {{ $menu['active'] ? 'bg-white text-[#004AAD] font-bold shadow-lg shadow-blue-900/20' : 'hover:bg-white/10 text-blue-100' }}">
                        
                        <div class="w-6 flex justify-center">
                            <i class="{{ $menu['icon'] }} text-lg {{ $menu['active'] ? 'text-[#004AAD]' : 'text-blue-300 group-hover:text-white' }}"></i>
                        </div>
                        
                        <span class="text-[13px] font-reguler tracking-wide">
                            {{ $menu['name'] }}
                        </span>
                    </a>
                @endforeach

                {{-- Render Dropdown Manajemen Data --}}
                @include('layouts.partials.sidebar-item-dropdown', [
                    'id' => 'manajemen',
                    'name' => 'Manajemen Data',
                    'icon' => 'fas fa-database',
                    'isActive' => $isManajemenActive,
                    'subMenus' => $manajemenMenus
                ])
            </div>
        </nav>

        <!-- FOOTER SIDEBAR -->
        <div class="p-4 border-t border-white/5">
            <div class="bg-blue-800/40 rounded-lg p-3 text-center">
                <p class="text-[10px] text-blue-200 font-medium tracking-tight">SMK Muhammadiyah 2 Metro</p>
            </div>
        </div>
    </div>
</aside>