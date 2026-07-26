{{-- 1. KUNCI PERTAMA: wire:key harus menggabungkan ID dan status Active agar identitasnya absolut --}}
<div class="relative mb-1" wire:key="dropdown-v3-{{ $id }}-{{ $isActive ? 'active' : 'idle' }}">
    
    {{-- Tombol Dropdown Utama --}}
    <button @click="openMenu = openMenu === '{{ $id }}' ? '' : '{{ $id }}'" 
            type="button"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group relative
            {{ $isActive ? 'bg-white text-[#004AAD]' : 'text-blue-100 hover:bg-white/10' }}">
        
        <div class="flex items-center gap-4">
            {{-- 2. KUNCI KEDUA: Paksa class ikon tetap (Hardcoded) di dalam atribut 'class' murni --}}
            <div class="w-6 h-6 flex-none flex items-center justify-center">
                @php
                    // Map ikon secara permanen berdasarkan ID
                    $fixedIcons = [
                        'profil'    => 'fas fa-user-circle',
                        'prestasi'  => 'fas fa-trophy',
                        'informasi' => 'fas fa-bullhorn'
                    ];
                    $displayIcon = $fixedIcons[$id] ?? $icon;
                @endphp
                
                {{-- Gunakan wire:ignore agar Livewire tidak menyentuh elemen <i> ini sama sekali --}}
                <i wire:ignore 
                   class="{{ $displayIcon }} text-lg {{ $isActive ? 'text-[#004AAD]' : 'text-blue-300 group-hover:text-white' }}"
                   style="transition: none !important;"></i>
            </div>
            
            <div class="relative flex items-center h-5 overflow-hidden">
                <span class="text-[13px] tracking-wide {{ $isActive ? 'font-bold' : 'font-medium' }}">
                    {{ $name }}
                </span>
            </div>
        </div>

        {{-- 3. KUNCI KETIGA: Gunakan x-bind:class yang lebih eksplisit untuk panah --}}
        <div class="flex-none w-5 h-5 flex items-center justify-center transition-transform duration-300 transform-gpu"
             :class="openMenu === '{{ $id }}' ? 'rotate-180' : 'rotate-0'">
            <svg class="w-4 h-4 {{ $isActive ? 'text-[#004AAD]' : 'text-blue-300/50 group-hover:text-white' }}" 
                 fill="none" 
                 stroke="currentColor" 
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </button>

    {{-- Sub-Menu --}}
    <div x-show="openMenu === '{{ $id }}'" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="mt-1 ml-6 border-l border-white/10 flex flex-col gap-1">
        
        @foreach($subMenus as $sub)
            <a href="{{ route($sub['route']) }}" 
               class="flex items-center pl-6 py-2 rounded-r-lg transition-all duration-200 
               {{ $sub['active'] ? 'text-white font-bold bg-white/10' : 'text-blue-200/70 hover:text-white hover:bg-white/5' }}">
                <span class="text-[12px]">{{ $sub['name'] }}</span>
            </a>
        @endforeach
    </div>
</div>