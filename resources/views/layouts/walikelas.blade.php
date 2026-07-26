<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Wali Kelas | SMK Muhammadiyah 2 Metro</title>
    
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/admin.css', 'resources/js/app.js'])

    {{-- Script Alpine diletakkan tanpa defer jika ingin eksekusi lebih cepat untuk UI --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* MENCEGAH FLICKER / KEDIP */
        [x-cloak] { 
            display: none !important; 
        }

        /* Mengunci rendering agar Chrome & Firefox identik */
        html {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            font-size: 16px; 
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.5;
        }

        /* Mengunci Lebar Sidebar agar tidak goyang */
        aside {
            width: 288px; 
            min-width: 288px;
            max-width: 288px;
            background-color: #004AAD; /* Paksa warna biru sejak awal load */
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* State Sidebar Tertutup */
        .sidebar-closed {
            width: 0px !important;
            min-width: 0px !important;
            max-width: 0px !important;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        /* Perbaikan Jarak Huruf agar teks Bold tidak melebar */
        p, span, a, div, button {
            letter-spacing: 0.01px; 
        }
    </style>
</head>
<body class="overflow-hidden h-screen" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024 }" 
      @resize.window="sidebarOpen = window.innerWidth >= 1024">
    <div class="relative flex h-full overflow-hidden">
        
        <!-- Sidebar Backdrop (Hanya muncul di Mobile) -->
        <div x-show="sidebarOpen" 
             x-transition:opacity
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
             x-cloak>
        </div>

        <!-- SIDEBAR CONTAINER -->
        <aside 
            :class="sidebarOpen ? '' : 'sidebar-closed'"
            class="flex-shrink-0 z-50 relative overflow-hidden border-r border-white/10 shadow-xl">
            
            <div class="w-72 h-full flex flex-col">
                {{-- Area Scrollable Sidebar --}}
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    {{-- Load Konten Sidebar --}}
                    @include('layouts.partials.sidebar-walikelas')
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 h-full relative overflow-hidden">
            
            <!-- Navbar Header -->
            <header class="flex-shrink-0 z-30 shadow-md w-full bg-[#004AAD]">
                @include('layouts.partials.navbar')
            </header>

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-[#F8FAFC] custom-scrollbar">
                <div class="max-w-[1600px] mx-auto min-h-full flex flex-col">
                    
                    {{-- Slot Konten Utama --}}
                    <div class="flex-1">
                        @yield('content')
                    </div>
                    
                    
                            </div>
                        </div>
                    
                </div>
            </main>
        </div>
    </div>

    {{-- Script Tambahan jika diperlukan di halaman spesifik --}}
    @stack('scripts')
</body>
</html>