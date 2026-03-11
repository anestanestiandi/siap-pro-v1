<x-app-layout :hideHeader="true">
    {{-- Main Container --}}
    <div class="max-w-4xl mx-auto" x-data="{ showCancelModal: false }">
        
        {{-- Header Section --}}
        <div class="bg-[#3B5286] rounded-t-xl p-6 text-white">
            <h1 class="text-xl font-bold">Tambah Kegiatan Baru</h1>
            <p class="text-sm text-blue-100 mt-1">Isi formulir di bawah untuk menambahkan kegiatan persidangan</p>
        </div>

        {{-- Form Section --}}
        <div class="bg-white rounded-b-xl shadow-lg p-8">
            <form action="{{ route('persidangan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div class="space-y-6">
                    
                    {{-- ... (Form fields unchanged for brevity, keeping all existing logic) ... --}}
                    {{-- Anggota Dewan --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Anggota Dewan
                        </label>
                        <select id="anggota_dewan" name="anggota_dewan_id[]" multiple class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50" placeholder="Pilih Anggota Dewan..." autocomplete="off">
                            <option value="">Pilih anggota dewan</option>
                            @foreach($anggotaDewan as $anggota)
                                <option value="{{ $anggota->id_anggota }}">{{ $anggota->nama }}</option>
                            @endforeach
                        </select>
                        @error('anggota_dewan_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal & Pukul --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Tanggal
                            </label>
                            <div class="relative">
                                <input type="text" id="tanggal" name="tanggal_persidangan" placeholder="Pilih tanggal" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10 pl-4">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            </div>
                            @error('tanggal_persidangan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pukul
                            </label>
                            <input type="text" id="waktu" name="waktu" placeholder="--:--" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10">
                            @error('waktu')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Jenis Persidangan & Tempat --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Jenis Persidangan
                            </label>
                            <select id="jenis_persidangan" name="id_jenis_persidangan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50" placeholder="Pilih Jenis Persidangan..." autocomplete="off">
                                <option value="">Pilih jenis persidangan</option>
                                @foreach($jenisPersidangan as $jenis)
                                    <option value="{{ $jenis->id_jenis_persidangan }}">{{ $jenis->nama_jenis }}</option>
                                @endforeach
                            </select>
                            @error('id_jenis_persidangan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Tempat
                            </label>
                            <input type="text" name="tempat" placeholder="Masukkan tempat kegiatan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10">
                            @error('tempat')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Nama Kegiatan & Petugas --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                Nama Kegiatan
                            </label>
                            <input type="text" name="nama_persidangan" placeholder="Masukkan nama kegiatan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10">
                            @error('nama_persidangan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @if(auth()->user()->isSuperAdmin())
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Petugas
                            </label>
                            <select id="petugas" name="petugas_id[]" multiple class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50" placeholder="Pilih Petugas..." autocomplete="off">
                                <option value="">Pilih Petugas Protokol</option>
                                @foreach($petugasProtokol as $petugas)
                                    <option value="{{ $petugas->id_petugas }}">{{ $petugas->nama }}</option>
                                @endforeach
                            </select>
                            @error('petugas_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @else
                        <div class="hidden"></div>
                        @endif
                    </div>

                    {{-- Upload File --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Upload File
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:bg-gray-100 transition cursor-pointer relative">
                            <div id="file-icon" class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-400 text-white mb-3 transition-colors duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            </div>
                            <p id="file-name" class="text-sm text-gray-600">
                                Drag & drop file di sini atau <span class="text-blue-500 font-medium">klik untuk browse</span>
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
                        <button type="button" @click="showCancelModal = true" class="px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition shadow-sm">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-[#3B5286] text-white rounded-lg font-medium hover:bg-[#2E4068] transition shadow-sm flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Simpan Kegiatan
                        </button>
                    </div>

                </div>
            </form>
        </div>

        {{-- Cancellation Modal --}}
        <div x-show="showCancelModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
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
                <button type="button" @click="showCancelModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Modal Content --}}
                <div class="text-center sm:text-left">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        Batalkan Pembuatan Kegiatan?
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8">
                        Data yang sudah Anda isi tidak akan tersimpan jika Anda membatalkan proses ini.
                    </p>

                    {{-- Modal Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('persidangan') }}" 
                           class="flex-1 px-6 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition text-center">
                            Ya, Batalkan
                        </a>
                        <button type="button" @click="showCancelModal = false"
                                class="flex-1 px-6 py-2.5 bg-[#3B5286] text-white rounded-xl font-semibold hover:bg-[#2E4068] transition shadow-lg shadow-blue-900/20">
                            Tidak, Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Date Picker
            flatpickr("#tanggal", {
                dateFormat: "Y-m-d", // Format sent to server
                altInput: true,
                altFormat: "l, j F Y", // Format displayed to user (e.g. Senin, 19 Januari 2026)
                locale: "id",
                allowInput: true
            });

            // Time Picker
            flatpickr("#waktu", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                allowInput: true
            });

            // Multi-Select Settings
            function getMultiSelectSettings(placeholder) {
                return {
                    plugins: ['remove_button'],
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    maxItems: null,
                    persist: false,
                    placeholder: placeholder,
                    hideSelected: true,
                };
            }

            // Single Select Settings
            var singleSelectSettings = {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1,
                persist: false,
                placeholder: 'Pilih jenis persidangan...',
                hideSelected: true,
            };
            
            new TomSelect('#anggota_dewan', getMultiSelectSettings('Pilih Anggota Dewan...'));
            new TomSelect('#jenis_persidangan', singleSelectSettings);
            @if(auth()->user()->isSuperAdmin())
            new TomSelect('#petugas', getMultiSelectSettings('Pilih Petugas Protokol...'));
            @endif

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
                        fileNameDisplay.innerHTML = 'Drag & drop file di sini atau <span class="text-blue-500 font-medium">klik untuk browse</span>';
                        fileIcon.className = 'inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-400 text-white mb-3 transition-colors duration-200';
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
