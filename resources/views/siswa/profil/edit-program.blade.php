@extends('layouts.siswa')

@section('content')

<div id="konten-form" class="flex-1 bg-[#F8FAFC] p-4 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    <div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="bg-[#004AAD] p-4 text-left">
            <h2 class="text-white font-bold text-sm uppercase tracking-[0.2em]">
                Edit Program Keahlian
            </h2>
            <p class="text-blue-100/70 text-[10px] uppercase font-medium mt-1 tracking-wider">
                Sistem Kesiswaan SMK Muhammadiyah 2 Metro
            </p>
        </div>

        <form action="{{ route('siswa.profil.updateProgram') }}" method="POST" class="p-8 md:p-10">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                {{-- Program Keahlian --}}
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">
                        Program Keahlian
                    </label>

                    <select
                        name="id_program_keahlian"
                        id="program_keahlian"
                        class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all">

                        <option value="">Pilih Program Keahlian</option>

                        @foreach($programKeahlian as $program)
                            <option
                                value="{{ $program->id_program_keahlian }}"
                                data-konsentrasi='@json($program->konsentrasi_keahlian)'
                                {{ ($user->siswa->profil->id_program_keahlian ?? '') == $program->id_program_keahlian ? 'selected' : '' }}>
                                {{ $program->nama_program }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Konsentrasi Keahlian --}}
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">
                        Konsentrasi Keahlian
                    </label>

                    <select
                        name="konsentrasi_keahlian"
                        id="konsentrasi_keahlian"
                        class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all">
                    </select>
                </div>

            </div>

            {{-- Tombol --}}
            <div class="flex flex-row items-center gap-4 pt-6 mt-4 md:pt-10 md:mt-8 border-t border-gray-50">

                <a href="{{ route('siswa.profil.index') }}"
                   class="flex-1 px-4 py-4 bg-slate-100 text-slate-500 rounded-xl font-bold text-[10px] md:text-xs tracking-widest hover:bg-slate-200 transition-all text-center">
                    Batal
                </a>

                <button type="submit"
                    class="flex-1 bg-[#004AAD] text-white py-4 px-4 rounded-xl font-bold text-[10px] md:text-xs tracking-widest hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/10 active:scale-[0.98]">
                    Simpan
                </button>

            </div>

        </form>

    </div>
</div>
```

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const programSelect = document.getElementById('program_keahlian');
    const konsentrasiSelect = document.getElementById('konsentrasi_keahlian');

    const currentKonsentrasi =
        @json($user->siswa->profil->konsentrasi_keahlian ?? '');

    function loadKonsentrasi() {

        const selectedOption =
            programSelect.options[programSelect.selectedIndex];

        let konsentrasiData =
            selectedOption.getAttribute('data-konsentrasi');

        konsentrasiSelect.innerHTML = '';

        if (!konsentrasiData) {
            return;
        }

        try {

            const konsentrasiList = JSON.parse(konsentrasiData);

            konsentrasiList.forEach(function(item) {

                const option = document.createElement('option');
                option.value = item;
                option.textContent = item;

                if (item === currentKonsentrasi) {
                    option.selected = true;
                }

                konsentrasiSelect.appendChild(option);

            });

        } catch (error) {
            console.error(error);
        }
    }

    loadKonsentrasi();

    programSelect.addEventListener('change', function() {
        loadKonsentrasi();
    });

});
</script>

<style>
@media (max-width: 767px) {
    #konten-form .p-8 {
        padding: 1rem !important;
    }
}
</style>

@endsection
