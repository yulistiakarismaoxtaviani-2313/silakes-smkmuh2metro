<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SMK Muhammadiyah 2 Metro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        .custom-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%231e3a8a' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.2em;
            padding-right: 2.5rem;
        }

        @media (max-width: 640px) {
        /* Menghilangkan padding body agar card menyentuh pinggir layar */
        body {
            padding: 0 !important;
            display: block !important;
        }

        /* Mengubah card menjadi full layar di HP */
        .max-w-2xl {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            border-radius: 0 !important; /* Sudut menjadi kotak tajam untuk kesan full-layar */
            min-height: 100vh; /* Memastikan card memenuhi tinggi layar */
            padding: 24px !important;
            box-shadow: none !important; /* Menghilangkan bayangan agar lebih bersih */
            border: none !important;
        }

        /* Menyesuaikan container agar konten di dalamnya tetap rapi */
        .text-center { margin-bottom: 2rem !important; }
    }
    </style>
</head>

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>Error Validasi:</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div x-data="{ 
            step: 1, 
            role: 'siswa',
            selectedProdi: '',
            photoPreview: null,
            previewPhoto(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => { this.photoPreview = e.target.result; };
                    reader.readAsDataURL(file);
                }
            }
        }" 
        x-cloak 
        class="max-w-2xl w-full bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-10 my-10">
        
        <div class="text-center mb-8">
            <img src="{{ asset('img/logo-smk1.png') }}" alt="Logo SMK" class="w-24 h-24 object-contain mx-auto mb-4">
            <h1 class="text-[#1e3a8a] font-black text-xl tracking-tight leading-none uppercase">
                Sistem Kesiswaan
            </h1>
            <h2 class="text-[#1e3a8a] font-bold text-sm tracking-tight mt-1 uppercase">
                SMK Muhammadiyah 2 Metro
            </h2>
            
            {{-- Stepper Progress --}}
            <div class="flex justify-center items-center gap-4 mt-8 mb-6">
                <div class="flex items-center gap-3">
                    <div :class="step === 1 ? 'bg-[#1e3a8a] shadow-lg' : 'bg-teal-500'" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold transition-all text-sm">
                        <template x-if="step === 1"><span>1</span></template>
                        <template x-if="step > 1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </template>
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] font-extrabold uppercase leading-none" :class="step === 1 ? 'text-[#1e3a8a]' : 'text-gray-400'">Biodata</p>
                        <p class="text-[9px] text-gray-400 font-medium">Data Diri</p>
                    </div>
                </div>
                <div class="w-12 h-0.5 bg-gray-200"></div>
                <div class="flex items-center gap-3">
                    <div :class="step === 2 ? 'bg-[#1e3a8a] shadow-lg' : 'bg-gray-200'" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold transition-all text-sm">2</div>
                    <div class="text-left">
                        <p class="text-[10px] font-extrabold uppercase leading-none" :class="step === 2 ? 'text-[#1e3a8a]' : 'text-gray-400'">Verifikasi</p>
                        <p class="text-[9px] text-gray-400 font-medium">Akun Login</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- enctype="multipart/form-data" WAJIB ADA untuk upload foto --}}
        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- STEP 1: BIODATA --}}
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Daftar Sebagai</label>
                        <select name="role" x-model="role" class="custom-select w-full px-4 py-3 border-2 border-blue-100 rounded-xl focus:border-blue-500 outline-none font-bold text-gray-700 bg-blue-50/30 transition-all">
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                        </select>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama sesuai ijazah" class="w-full px-4 py-3 border border-blue-400 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all shadow-sm">
                    </div>

                    {{-- FORM KHUSUS SISWA (Field asli dipertahankan semua) --}}
                    <template x-if="role === 'siswa'">
                        <div class="contents">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">NIS</label>
                                <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Masukkan NIS" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                            </div>

                        
   <div>
    <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">NIK (16 Digit)</label>
    <input type="text" 
           name="nik" 
           value="{{ old('nik') }}" 
           placeholder="Masukkan 16 digit NIK" 
           maxlength="16"
           inputmode="numeric"
           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
           class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
    @error('nik')
        <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
    @enderror
</div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Agama</label>
                                <select name="agama" class="custom-select w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm">
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Budha" {{ old('agama') == 'Budha' ? 'selected' : '' }}>Budha</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Kota Kelahiran" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="custom-select w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                             <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">No HP</label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Kelas</label>
                                <select name="id_kelas" class="custom-select w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm">
                                    <option value="">Pilih Kelas</option>
                                    @foreach($data_kelas as $k)
                                        <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-1 md:col-span-2">
    <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Program Keahlian</label>
    <select name="id_program_keahlian" x-model="selectedProdi" required class="custom-select w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm">
        <option value="">Pilih Program Keahlian</option>
        @foreach($data_prodi as $prodi)
            <option value="{{ $prodi->id_program_keahlian }}">{{ $prodi->nama_program }}</option>
        @endforeach
    </select>
</div>

<div class="col-span-1 md:col-span-2" x-show="selectedProdi !== ''" x-cloak>
    <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Konsentrasi Keahlian</label>
<select name="konsentrasi_keahlian" required class="custom-select w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm">
    <option value="">Pilih Konsentrasi</option>
    
    @foreach($data_prodi as $prodi)
        @php
            $list = is_array($prodi->konsentrasi_keahlian) ? $prodi->konsentrasi_keahlian : json_decode($prodi->konsentrasi_keahlian, true);
        @endphp
        
        @if(!empty($list))
            @foreach($list as $item)
                {{-- Kita render semua option, tapi tambahkan atribut x-show untuk memfilter --}}
                <option value="{{ $item }}" 
                        x-show="selectedProdi == '{{ $prodi->id_program_keahlian }}'">
                    {{ $item }}
                </option>
            @endforeach
        @endif
    @endforeach
</select>
</div>
                           

                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Alamat Lengkap</label>
                                <textarea name="alamat" rows="2" placeholder="Alamat rumah lengkap" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">{{ old('alamat') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Nama Ayah</label>
                                <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" placeholder="Nama ayah" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Pekerjaan Ayah</label>
                                <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}" placeholder="Pekerjaan ayah" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Nama Ibu</label>
                                <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" placeholder="Nama ibu" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Pekerjaan Ibu</label>
                                <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}" placeholder="Pekerjaan ibu" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                            </div>
                        </div>
                    </template>

                    {{-- FORM KHUSUS GURU --}}
                    <template x-if="role === 'guru'">
                        <div class="contents">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 col-span-1 md:col-span-2">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">NBM</label>
                                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NBM" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="custom-select w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm transition-all font-medium">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 col-span-1 md:col-span-2">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">No HP Aktif</label>
                                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
                                </div>
                                <div class="col-span-1 md:col-span-2">
    <label class="block text-xs font-bold text-gray-500 mb-2 ml-1">
        Mata Pelajaran
    </label>

    <div class="w-full border border-blue-200 rounded-xl bg-gray-50 p-3 h-40 overflow-y-auto shadow-inner">
        <div class="grid grid-cols-1 gap-2">
            @foreach(\App\Models\Mapel::all() as $m)
                <label class="flex items-center p-2 hover:bg-blue-100 rounded-lg cursor-pointer transition-colors">
                    <input type="checkbox"
                           name="mapel[]"
                           value="{{ $m->id_mapel }}"
                           class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">

                    <span class="ml-3 text-sm text-gray-700 font-medium">
                        {{ $m->nama_mapel }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>
</div>
                    </template>
                </div>

                <div class="mt-10 flex justify-between gap-4">
                    <a href="/login" class="px-8 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl transition-all shadow-md text-xs uppercase tracking-wider">
                        Batal
                    </a>
                    <button type="button" @click="step = 2" class="px-10 py-3.5 bg-[#1e3a8a] hover:bg-blue-800 text-white font-bold rounded-xl transition-all shadow-lg flex items-center gap-2 text-xs uppercase tracking-wider">
                        Simpan & Lanjut
                    </button>
                </div>
            </div>

            {{-- STEP 2: AKUN LOGIN & FOTO --}}
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4">
                <div class="space-y-5">

                    {{-- Upload Foto Profil --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Foto Profil</label>
                        <div class="flex items-center gap-4 p-4 border-2 border-dashed border-blue-200 rounded-2xl bg-blue-50/30 hover:border-blue-400 transition-all">
                            <div class="shrink-0">
                                <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center overflow-hidden border-2 border-blue-100 shadow-sm">
                                    <template x-if="photoPreview">
                                        <img :src="photoPreview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!photoPreview">
                                        <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </template>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="photo" @change="previewPhoto" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#1e3a8a] file:text-white hover:file:bg-blue-800 cursor-pointer"/>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Email Aktif</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" required placeholder="Buat username login" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="relative">
                            <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Password</label>
                            <input type="password" id="passwordField" name="password" required placeholder="........" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all text-sm">
                            <button type="button" onclick="togglePassword('passwordField', 'eyeIcon')" class="absolute top-8 right-0 pr-4 flex items-center">
                                <img id="eyeIcon" src="{{ asset('img/icons/eye.png') }}" class="w-5 h-5 opacity-30 hover:opacity-100 transition-opacity" alt="toggle">
                            </button>
                        </div>
                        <div class="relative">
                            <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Ulangi Password</label>
                            <input type="password" id="confirmPasswordField" name="password_confirmation" required placeholder="........" class="w-full px-4 py-3 border border-blue-400 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all text-sm">
                            <button type="button" onclick="togglePassword('confirmPasswordField', 'eyeIconConfirm')" class="absolute top-8 right-0 pr-4 flex items-center">
                                <img id="eyeIconConfirm" src="{{ asset('img/icons/eye.png') }}" class="w-5 h-5 opacity-30 hover:opacity-100 transition-opacity" alt="toggle">
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-between gap-4">
                    <button type="button" @click="step = 1" class="px-8 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl transition-all shadow-md text-xs uppercase tracking-wider">
                        Kembali
                    </button>
                    <button type="submit" class="px-12 py-3.5 bg-[#1e3a8a] hover:bg-blue-800 text-white font-extrabold rounded-xl transition-all shadow-lg text-xs uppercase tracking-wider">
                        Selesaikan Pendaftaran
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(iconId);
            const pathEyeClosed = "{{ asset('img/icons/eye.png') }}";
            const pathEyeOpen = "{{ asset('img/icons/eye1.png') }}";

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.src = pathEyeOpen;
                eyeIcon.style.opacity = "1";
            } else {
                passwordField.type = 'password';
                eyeIcon.src = pathEyeClosed;
                eyeIcon.style.opacity = "0.3";
            }
        }
    </script>
</body>
</html>