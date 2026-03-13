<x-app-layout :hideHeader="true">
    {{-- Main Container --}}
    <div class="max-w-4xl mx-auto">
        
        {{-- Header Section --}}
        <div class="bg-[#3B5286] rounded-t-xl p-6 text-white">
            <h1 class="text-xl font-bold">Edit Kegiatan Perjalanan Dinas</h1>
            <p class="text-sm text-blue-100 mt-1">Perbarui informasi administrasi perjalanan dinas</p>
        </div>

        {{-- Form Section --}}
        <div class="bg-white rounded-b-xl shadow-lg p-8">
            <form action="{{ route('administrasi-perjalanan-dinas.update', $item->id_adm_perjalanan_dinas) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6" x-data="{ 
                    jenisPerjalanan: '{{ $item->id_jenis_perjalanan_dinas }}', 
                    luarNegeriId: {{ $luarNegeriId ?? 'null' }},
                    isLuarNegeri() { return this.jenisPerjalanan == this.luarNegeriId; },
                    showCancelModal: false
                }">
                    
                    {{-- Row 1: Nama Kegiatan & Tanggal --}}
                    {{-- ... (Form fields unchanged for brevity) ... --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Kegiatan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                Nama Kegiatan
                            </label>
                            <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $item->nama_kegiatan) }}" placeholder="Masukkan nama kegiatan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10">
                            @error('nama_kegiatan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Tanggal
                            </label>
                            <div class="relative">
                                <input type="text" id="tanggal" name="tanggal" 
                                    value="{{ old('tanggal', $item->tanggal_mulai && $item->tanggal_selesai ? $item->tanggal_mulai.' to '.$item->tanggal_selesai : $item->tanggal_mulai) }}"
                                    placeholder="mm/dd/yyyy" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10 pl-4">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            </div>
                            @error('tanggal')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 2: Jenis Perjalanan & Tujuan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Jenis Perjalanan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Jenis Perjalanan Dinas
                            </label>
                            <select id="jenis_perjalanan" name="id_jenis_perjalanan_dinas" x-model="jenisPerjalanan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10" placeholder="Pilih jenis perjalanan" autocomplete="off">
                                <option value="">Pilih jenis perjalanan</option>
                                @foreach($jenisPerjalanan as $jenis)
                                    <option value="{{ $jenis->id_jenis_perjalanan }}" {{ old('id_jenis_perjalanan_dinas', $item->id_jenis_perjalanan_dinas) == $jenis->id_jenis_perjalanan ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                                @endforeach
                            </select>
                            @error('id_jenis_perjalanan_dinas')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tujuan Perjalanan Dinas (Dynamic) --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Tujuan Perjalanan Dinas
                            </label>
                            
                            {{-- Dropdown Provinsi (Default/Dalam Negeri) --}}
                            <div x-show="!isLuarNegeri()">
                                <select id="tujuan_provinsi" name="tujuan_provinsi" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10" placeholder="Cari Provinsi..." autocomplete="off">
                                    <option value="">Cari Provinsi...</option>
                                    @foreach($provinsi as $prov)
                                        <option value="{{ $prov->nama_provinsi }}" {{ old('tujuan_provinsi', $item->tujuan) == $prov->nama_provinsi ? 'selected' : '' }}>{{ $prov->nama_provinsi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Manual Input (Luar Negeri) --}}
                            <div x-show="isLuarNegeri()" style="display: none;">
                                <input type="text" name="tujuan_manual" value="{{ old('tujuan_manual', $item->tujuan) }}" placeholder="Masukkan negara/kota tujuan..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10">
                            </div>

                            @error('tujuan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 3: Petugas & Pelaksana Manual --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Petugas --}}
                        @if(auth()->user()->isSuperAdmin())
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Petugas Protokol
                            </label>
                            <select id="petugas" name="petugas_id[]" multiple class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 min-h-[40px]" placeholder="Pilih petugas protokol..." autocomplete="off">
                                <option value="">Pilih petugas protokol...</option>
                                @foreach($petugasProtokol as $petugas)
                                    <option value="{{ $petugas->id_petugas }}" {{ in_array($petugas->id_petugas, old('petugas_id', $item->petugas->pluck('id_petugas')->toArray())) ? 'selected' : '' }}>{{ $petugas->nama }}</option>
                                @endforeach
                            </select>
                            @error('petugas_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @else
                        <div class="hidden"></div>
                        @endif

                        {{-- Pelaksana (Dropdown) --}}
                        <div>
                            @php
                                $raw = $item->pelaksana;
                                $selectedPelaksana = [];
                                
                                if (strpos($raw, ';') !== false) {
                                    $selectedPelaksana = explode(';', $raw);
                                } else {
                                    $parts = explode(',', $raw);
                                    $current = "";
                                    foreach ($parts as $part) {
                                        $part = trim($part);
                                        if ($current && (str_contains($part, '.') || strlen($part) <= 5)) {
                                            $current .= ", " . $part;
                                        } else {
                                            if ($current) $selectedPelaksana[] = $current;
                                            $current = $part;
                                        }
                                    }
                                    if ($current) $selectedPelaksana[] = $current;
                                }
                                $selectedPelaksana = array_filter(array_map('trim', $selectedPelaksana));
                            @endphp
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Pelaksana
                            </label>
                            <select id="pelaksana" name="pelaksana[]" multiple class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 min-h-[40px]" placeholder="Pilih pelaksana..." autocomplete="off">
                                <option value="">Pilih pelaksana...</option>
                                @php
                                    $masterPegawaiNames = $masterPegawai->pluck('nama_lengkap')->toArray();
                                    $allPelaksana = old('pelaksana', $selectedPelaksana);
                                @endphp
                                
                                {{-- Master Pegawai Options --}}
                                @foreach($masterPegawai as $pegawai)
                                    <option value="{{ $pegawai->nama_lengkap }}" {{ in_array($pegawai->nama_lengkap, $allPelaksana) ? 'selected' : '' }}>
                                        {{ $pegawai->nama_lengkap }}
                                    </option>
                                @endforeach

                                {{-- Custom Names Options --}}
                                @foreach($allPelaksana as $name)
                                    @if(!in_array($name, $masterPegawaiNames))
                                        <option value="{{ $name }}" selected>{{ $name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('pelaksana')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Row 4: Upload File --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Update File
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:bg-gray-100 transition cursor-pointer relative">
                            <div id="file-icon" class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-400 text-white mb-3 transition-colors duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            </div>
                            <p id="file-name" class="text-sm text-gray-600">
                                @if($item->file_path)
                                    File saat ini: <span class="font-medium text-[#3B5286]">{{ basename($item->file_path) }}</span><br>
                                    <span class="text-xs text-gray-400">Drag & drop file baru untuk mengganti</span>
                                @else
                                    Drag & drop file di sini atau <span class="text-blue-500 font-medium">klik untuk browse</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX, JPEG, JPG, PNG, XLS, XLSX (Max. 10MB)</p>
                            <input type="file" id="file-upload" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.doc,.docx,.jpeg,.jpg,.png,.xls,.xlsx">
                        </div>
                        @error('file')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 pt-6">
                        <button type="button" @click="showCancelModal = true"
                            class="px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition shadow-sm">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-[#3B5286] text-white rounded-lg font-medium hover:bg-[#2E4068] transition shadow-sm flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Update Kegiatan
                        </button>
                    </div>

                    {{-- Cancellation Modal --}}
                    <template x-teleport="body">
                        <div x-show="showCancelModal" 
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         style="display: none;">
                        
                        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative"
                             @click.outside="showCancelModal = false"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="scale-95 opacity-0"
                             x-transition:enter-end="scale-100 opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="scale-100 opacity-100"
                             x-transition:leave-end="scale-95 opacity-0">
                            
                            {{-- Close Button --}}
                            <button @click="showCancelModal = false" type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            {{-- Modal Content --}}
                            <div class="text-center sm:text-left">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    Batalkan Perubahan Kegiatan?
                                </h3>
                                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                                    Perubahan yang Anda buat tidak akan tersimpan jika Anda membatalkan proses ini.
                                </p>

                                {{-- Modal Buttons --}}
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('administrasi-perjalanan-dinas') }}" 
                                       class="flex-1 px-6 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition text-center">
                                        Ya, Batalkan
                                    </a>
                                    <button @click="showCancelModal = false" type="button"
                                            class="flex-1 px-6 py-2.5 bg-[#3B5286] text-white rounded-xl font-semibold hover:bg-[#2E4068] transition shadow-lg shadow-blue-900/20">
                                        Tidak, Lanjutkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Date Picker (Single Input for Range)
            flatpickr("#tanggal", {
                mode: "range",
                dateFormat: "Y-m-d", // Format sent to server
                altInput: true,
                altFormat: "l, j F Y", // Format displayed to user
                locale: "id",
                allowInput: true
            });

            // Multi-Select Settings
            var multiSelectSettings = {
                plugins: ['remove_button'],
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: null,
                persist: false
            };

            // Pelaksana Select Settings (Allowing custom input)
            var pelaksanaSelectSettings = {
                plugins: ['remove_button'],
                create: true,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 100,
                maxOptions: 100,
                persist: false
            };

            // Single Select Settings
            var singleSelectSettings = {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1,
                persist: false
            };
            
            // Initialize TomSelects
            new TomSelect('#jenis_perjalanan', singleSelectSettings);
            @if(auth()->user()->isSuperAdmin())
            new TomSelect('#petugas', multiSelectSettings);
            @endif
            new TomSelect('#pelaksana', pelaksanaSelectSettings);
            new TomSelect('#tujuan_provinsi', singleSelectSettings);


            // File Upload Preview
            const fileInput = document.getElementById('file-upload');
            const fileNameDisplay = document.getElementById('file-name');
            const fileIcon = document.getElementById('file-icon');
            
            if(fileInput && fileNameDisplay && fileIcon) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        fileNameDisplay.innerHTML = `<span class="font-medium text-[#3B5286]">${file.name}</span> <span class="text-xs text-gray-500">(${(file.size/1024).toFixed(1)} KB)</span>`;
                        
                        const fileExt = file.name.split('.').pop().toLowerCase();
                        let iconColorClass = 'bg-gray-400';
                        if (['pdf'].includes(fileExt)) iconColorClass = 'bg-red-500';
                        else if (['doc', 'docx'].includes(fileExt)) iconColorClass = 'bg-blue-500';
                        else if (['xls', 'xlsx'].includes(fileExt)) iconColorClass = 'bg-green-500';
                        else if (['png', 'jpg', 'jpeg'].includes(fileExt)) iconColorClass = 'bg-yellow-500';
                        
                        fileIcon.className = `inline-flex items-center justify-center w-12 h-12 rounded-full text-white mb-3 transition-colors duration-200 ${iconColorClass}`;
                    } else {
                        @if($item->file_path)
                            fileNameDisplay.innerHTML = 'File saat ini: <span class="font-medium text-[#3B5286]">{{ basename($item->file_path) }}</span><br><span class="text-xs text-gray-400">Drag & drop file baru untuk mengganti</span>';
                            fileIcon.className = 'inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-400 text-white mb-3 transition-colors duration-200';
                        @else
                            fileNameDisplay.innerHTML = 'Drag & drop file di sini atau <span class="text-blue-500 font-medium">klik untuk browse</span>';
                            fileIcon.className = 'inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-400 text-white mb-3 transition-colors duration-200';
                        @endif
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
