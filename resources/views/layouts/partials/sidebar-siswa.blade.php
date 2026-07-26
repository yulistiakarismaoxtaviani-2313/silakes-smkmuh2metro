<aside class="flex flex-col h-full bg-[#004AAD] font-poppins overflow-hidden w-full text-white transition-all duration-300" 
       x-data="{ 
            openMenu: '{{ 
                request()->routeIs('siswa.profil.*') ? 'profil' : 
                (request()->routeIs('siswa.prestasi.*') ? 'prestasi' : 
                (request()->routeIs('siswa.informasi.*') ? 'informasi' : '')) 
            }}' 
       }">
    
    @php
        // Definisi Menu Utama
        $mainMenus = [
            ['name' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'route' => 'siswa.dashboard', 'active' => request()->routeIs('siswa.dashboard')],
            ['name' => 'Presensi', 'icon' => 'fas fa-calendar-check', 'route' => 'siswa.presensi.index', 'active' => request()->routeIs('siswa.presensi.*')],
        ];

        // Definisi Sub Menu untuk Dropdown
        $profilMenus = [
            ['name' => 'Lihat Profil', 'route' => 'siswa.profil.index', 'active' => request()->routeIs('siswa.profil.index')],
            ['name' => 'Perbarui Profil', 'route' => 'siswa.profil.edit-foto', 'active' => request()->routeIs('siswa.profil.edit-foto')],
        ];

        $prestasiMenus = [
            ['name' => 'Unggah Prestasi', 'route' => 'siswa.prestasi.create', 'active' => request()->routeIs('siswa.prestasi.create')],
            ['name' => 'Lihat Prestasi', 'route' => 'siswa.prestasi.index', 'active' => request()->routeIs('siswa.prestasi.index')],
        ];

        $academicMenus = [
            ['name' => 'Jadwal', 'route' => 'siswa.informasi.jadwal', 'active' => request()->routeIs('siswa.informasi.jadwal')],
            ['name' => 'Pengumuman', 'route' => 'siswa.informasi.pengumuman.index', 'active' => request()->routeIs('siswa.informasi.pengumuman.index')],
        ];

        // Logika untuk menentukan judul header berdasarkan menu aktif
        $allSubMenus = array_merge($profilMenus, $prestasiMenus, $academicMenus);
        $currentMenu = collect(array_merge($mainMenus, $allSubMenus))->firstWhere('active', true) ?? ['name' => 'Dashboard'];

        $isProfilActive = collect($profilMenus)->contains('active', true);
        $isPrestasiActive = collect($prestasiMenus)->contains('active', true);
        $isAcademicActive = collect($academicMenus)->contains('active', true);
    @endphp

    <div class="w-full flex flex-col h-full">
        <!-- HEADER SIDEBAR: Sejajar dengan Navbar Putih (py-3 & h-14) -->
        <div class="px-6 py-3 flex items-center border-b border-white/10 flex-shrink-0">
            <div class="flex items-center gap-4">
                <!-- Logo: Ditambahkan bg putih transparan agar menonjol di bg biru -->
                <div class="h-14 w-14 flex justify-center items-center flex-shrink-0 bg-white/10 rounded-xl p-2 shadow-inner">
                    <img src="{{ asset('img/logo-smk1.png') }}" alt="Logo SMK" class="h-full w-full object-contain">
                </div>
                
                <div class="flex flex-col relative justify-center">
                    <h1 class="text-[14px] font-bold text-white tracking-wider uppercase leading-tight">
                        {{ $currentMenu['name'] }}
                    </h1>
                    <!-- Garis dekorasi bawah judul -->
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
                    <a href="{{ route($menu['route']) }}" 
                       class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 group {{ $menu['active'] ? 'bg-white text-[#004AAD] font-bold shadow-lg shadow-blue-900/20' : 'hover:bg-white/10 text-blue-100' }}">
                        
                        <div class="w-6 flex justify-center">
                            <i class="{{ $menu['icon'] }} text-lg {{ $menu['active'] ? 'text-[#004AAD]' : 'text-blue-300 group-hover:text-white' }}"></i>
                        </div>
                        
                        <span class="text-[13px] font-reguler tracking-wide">
                            {{ $menu['name'] }}
                        </span>
                    </a>
                @endforeach

                {{-- Render Dropdown Menus --}}
                @include('layouts.partials.sidebar-item-dropdown', [
                    'id' => 'profil',
                    'name' => 'Profil Siswa',
                    'icon' => 'fas fa-user-circle',
                    'isActive' => $isProfilActive,
                    'subMenus' => $profilMenus
                ])

                @include('layouts.partials.sidebar-item-dropdown', [
                    'id' => 'prestasi',
                    'name' => 'Prestasi',
                    'icon' => 'fas fa-trophy',
                    'isActive' => $isPrestasiActive,
                    'subMenus' => $prestasiMenus
                ])

                @include('layouts.partials.sidebar-item-dropdown', [
                    'id' => 'informasi',
                    'name' => 'Informasi Akademik',
                    'icon' => 'fas fa-bullhorn',
                    'isActive' => $isAcademicActive,
                    'subMenus' => $academicMenus
                ])
            </div>
        </nav>

        <!-- FOOTER SIDEBAR (Opsional) -->
        <div class="p-4 border-t border-white/5">
            <div class="bg-blue-800/40 rounded-lg p-3 text-center">
                <p class="text-[10px] text-blue-200 font-medium tracking-tight">SMK Muhammadiyah 2 Metro</p>
            </div>
        </div>
    </div>
</aside>