@php
    // 1. Ambil ID User yang sedang login
    $userId = Auth::id();

    // 2. Cari data guru
    $dataGuru = \App\Models\Guru::where('id_user', $userId)->first();

    // 3. Cari data kelas berdasarkan id_guru
    $kelasPengampu = null;
    if ($dataGuru) {
        $kelasPengampu = \App\Models\Kelas::where('id_guru', $dataGuru->id_guru)->first();
    }
@endphp

<aside class="flex flex-col h-full bg-[#004AAD] font-poppins overflow-hidden w-full text-white transition-all duration-300" 
       x-data="{
    openMenu:
        '{{ request()->routeIs('walikelas.presensi.*','guru.presensi.*')
            ? 'presensi'
            : (request()->routeIs('walikelas.jadwal.*','walikelas.pengumuman.*')
                ? 'informasi'
                : '') }}'
}">
    
    @if($kelasPengampu)
        @php
            $mainMenus = [
                ['name' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'route' => 'walikelas.dashboard', 'active' => request()->routeIs('walikelas.dashboard')],
                ['name' => 'Data Siswa', 'icon' => 'fas fa-user-graduate', 'route' => 'walikelas.siswa.index', 'active' => request()->routeIs('walikelas.siswa.*')],
                ['name' => 'Rekap Presensi', 'icon' => 'fas fa-file-invoice', 'route' => 'walikelas.rekap.index', 'active' => request()->routeIs('walikelas.rekap.*')],
            ];

            $presensiMenus = [
    [
        'name' => 'Presensi Kelas',
        'route' => 'walikelas.presensi.kelas',
        'active' => request()->routeIs('walikelas.presensi.kelas*')
    ],
    [
        'name' => 'Presensi Mengajar',
        'route' => 'walikelas.presensi.mengajar', // sesuaikan dengan route guru
        'active' => request()->routeIs('walikelas.presensi.mengajar*')
    ],
];

            $academicMenus = [
                ['name' => 'Jadwal', 'route' => 'walikelas.jadwal.index', 'active' => request()->routeIs('walikelas.jadwal.*')],
                ['name' => 'Pengumuman', 'route' => 'walikelas.pengumuman.index', 'active' => request()->routeIs('walikelas.pengumuman.*')],
            ];

            $allMenus = array_merge($mainMenus, $presensiMenus, $academicMenus);
            $currentMenu = collect($allMenus)->firstWhere('active', true) ?? ['name' => 'Dashboard'];
            $isAcademicActive = collect($academicMenus)->contains('active', true);
            $isPresensiActive = collect($presensiMenus)->contains('active', true);
        @endphp

        <div class="w-full flex flex-col h-full overflow-hidden">
            <!-- HEADER -->
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

    {{-- Tampilkan dropdown Presensi setelah menu Data Siswa --}}
    @if($menu['name'] == 'Data Siswa')
        @include('layouts.partials.sidebar-item-dropdown', [
            'id' => 'presensi',
            'name' => 'Presensi',
            'icon' => 'fas fa-calendar-check',
            'isActive' => $isPresensiActive,
            'subMenus' => $presensiMenus
        ])
    @endif

@endforeach

                    @include('layouts.partials.sidebar-item-dropdown', [
                        'id' => 'informasi',
                        'name' => 'Informasi Akademik',
                        'icon' => 'fas fa-graduation-cap',
                        'isActive' => $isAcademicActive,
                        'subMenus' => $academicMenus
                    ])
                </div>
            </nav>

            <!-- FOOTER SIDEBAR -->
            <div class="p-4 border-t border-white/5 flex-shrink-0">
                <div class="bg-blue-800/40 rounded-lg p-3 text-center">
                    <p class="text-[10px] text-blue-200 font-medium tracking-tight">SMK Muhammadiyah 2 Metro</p>
                </div>
            </div>
        </div>
    @else
        {{-- Tampilan jika bukan wali kelas --}}
        <div class="flex flex-col items-center justify-center h-full p-8 text-center">
            <div class="bg-white/10 p-6 rounded-2xl border border-white/10 backdrop-blur-md">
                <i class="fas fa-user-shield text-4xl text-blue-300 mb-4"></i>
                <p class="text-white font-bold text-sm">Akses Terbatas</p>
                <p class="text-[11px] text-blue-200 mt-2 leading-relaxed">Akun Anda tidak terdaftar sebagai Wali Kelas di kelas manapun.</p>
                <a href="/guru/dashboard" class="mt-6 inline-block px-4 py-2 bg-white text-[#004AAD] rounded-lg text-[11px] font-bold uppercase tracking-wider">
                    Ke Dashboard Guru
                </a>
            </div>
        </div>
    @endif
</aside>