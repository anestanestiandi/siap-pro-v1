<x-app-layout :hideHeader="true">
    {{-- Main Container --}}
    <div class="max-w-4xl mx-auto" x-data="{ showCancelModal: false }">
        
        {{-- Header Section --}}
        <div class="bg-[#3B5286] rounded-t-xl p-6 text-white text-left">
            <h1 class="text-xl font-bold">Edit Kunjungan Kerja</h1>
            <p class="text-sm text-blue-100 mt-1">Perbarui detail kegiatan kunjungan kerja di bawah ini</p>
        </div>

        {{-- Form Section --}}
        <div class="bg-white rounded-b-xl shadow-lg p-8">
            <form action="{{ route('kunjungan-kerja.update', $item->id_kunjungan) }}" method="POST" enctype="multipart/form-data" 
                x-data="{ 
                    jenisKunjungan: '{{ old('id_jenis_kunjungan', $item->id_jenis_kunjungan) }}',
                    isLuarNegeri() { return this.jenisKunjungan == '2' || this.jenisKunjungan == '4'; },
                    get tipeTujuan() { return this.isLuarNegeri() ? 'luar_negeri' : 'dalam_negeri'; }
                }" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="tipe_tujuan" :value="tipeTujuan">
                
                <div class="space-y-6">
                    {{-- Row 1: Anggota Dewan & Rombongan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Ketua / Anggota Dewan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Ketua / Anggota Dewan
                            </label>
                            <select name="anggota_dewan_id[]" id="anggota_dewan_id" multiple class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10" placeholder="Pilih anggota dewan...">
                                @foreach($anggotaDewan as $ad)
                                    <option value="{{ $ad->id_anggota }}" 
                                        {{ (is_array(old('anggota_dewan_id', $item->anggotaDewan->pluck('id_anggota')->toArray())) && in_array($ad->id_anggota, old('anggota_dewan_id', $item->anggotaDewan->pluck('id_anggota')->toArray()))) ? 'selected' : '' }}>
                                        {{ $ad->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('anggota_dewan_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Rombongan Tambahan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Rombongan Tambahan (Opsional)
                            </label>
                            <select name="rombongan[]" id="rombongan" multiple class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10" placeholder="Pilih pelaksana...">
                                @foreach($masterPegawai as $pegawai)
                                    <option value="{{ $pegawai->nama_lengkap }}" 
                                        {{ (is_array(old('rombongan', $item->rombongan ?? [])) && in_array($pegawai->nama_lengkap, old('rombongan', $item->rombongan ?? []))) ? 'selected' : '' }}>
                                        {{ $pegawai->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rombongan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Row 2: Tanggal & Pukul --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Tanggal
                            </label>
                            <div class="relative">
                                <input type="text" id="tanggal_kunjungan" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan', $item->tanggal_kunjungan ? $item->tanggal_kunjungan->format('Y-m-d') : '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10 pl-4 bg-white" autocomplete="off">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            </div>
                            @error('tanggal_kunjungan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Pukul --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pukul (Opsional)
                            </label>
                            <div class="relative">
                                <input type="text" id="waktu" name="waktu_mulai" value="{{ old('waktu_mulai', $item->waktu ? \Carbon\Carbon::parse($item->waktu)->format('H:i') : '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10 pl-4 bg-white" autocomplete="off">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            @error('waktu_mulai') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Row 3: Jenis Kunjungan & Tujuan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Jenis Kunjungan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Jenis Kunjungan Kerja
                            </label>
                            <select name="id_jenis_kunjungan" id="id_jenis_kunjungan" x-model="jenisKunjungan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10" placeholder="Pilih jenis kunjungan...">
                                <option value="">Pilih jenis kunjungan</option>
                                @foreach($jenisKunjungan as $jk)
                                    <option value="{{ $jk->id_jenis_kunjungan }}" {{ old('id_jenis_kunjungan', $item->id_jenis_kunjungan) == $jk->id_jenis_kunjungan ? 'selected' : '' }}>{{ $jk->nama_jenis }}</option>
                                @endforeach
                            </select>
                            @error('id_jenis_kunjungan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tujuan Kunjungan Kerja --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Tujuan Kunjungan Kerja
                            </label>
                            
                            {{-- Dropdown Provinsi (Dinamis - DN) --}}
                            <div x-show="!isLuarNegeri()">
                                <select name="id_provinsi" id="id_provinsi" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10" placeholder="Cari Provinsi...">
                                    <option value="">Cari Provinsi...</option>
                                    @foreach($provinsi as $p)
                                        <option value="{{ $p->id_provinsi }}" {{ old('id_provinsi', $item->id_provinsi) == $p->id_provinsi ? 'selected' : '' }}>{{ $p->nama_provinsi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Manual Input (Dinamis - LN) --}}
                            <div x-show="isLuarNegeri()" x-cloak style="display: none;">
                                <input type="text" name="tujuan_luar_negeri" value="{{ old('tujuan_luar_negeri', $item->tujuan_luar_negeri) }}" placeholder="Masukkan negara/kota tujuan..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10 px-4">
                            </div>
                            @error('id_provinsi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            @error('tujuan_luar_negeri') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Row 4: Nama Kegiatan & Penugasan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Kegiatan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                Nama Kegiatan
                            </label>
                            <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $item->nama_kegiatan) }}" placeholder="Masukkan nama kegiatan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10 px-4">
                            @error('nama_kegiatan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Penugasan Pendampingan --}}
                        @if(auth()->user()->isSuperAdmin())
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Penugasan Pendampingan
                            </label>
                            <select name="petugas_id[]" id="petugas_id" multiple class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-50 h-10" placeholder="Pilih petugas protokol...">
                                @foreach($petugasProtokol as $pp)
                                    <option value="{{ $pp->id_petugas }}" 
                                        {{ (is_array(old('petugas_id', $item->petugas->pluck('id_petugas')->toArray())) && in_array($pp->id_petugas, old('petugas_id', $item->petugas->pluck('id_petugas')->toArray()))) ? 'selected' : '' }}>
                                        {{ $pp->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('petugas_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        @else
                        <div class="hidden"></div>
                        @endif
                    </div>

                    {{-- Row 5: Upload File --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Upload File
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:bg-gray-100 transition cursor-pointer relative">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-400 text-white mb-3">
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
                            <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX (Max. 10MB)</p>
                            <input type="file" id="file-upload" name="file_pendukung" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.doc,.docx">
                        </div>
                        @error('file_pendukung') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                        <button type="button" @click="showCancelModal = true" class="px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition shadow-sm">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-[#3B5286] text-white rounded-lg font-medium hover:bg-[#2E4068] transition shadow-sm flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Cancellation Modal --}}
        <div x-show="showCancelModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative" @click.outside="showCancelModal = false">
                <button type="button" @click="showCancelModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="text-center sm:text-left">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Batalkan Perubahan?</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8">Data yang sudah Anda isi tidak akan tersimpan jika Anda membatalkan proses ini.</p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('kunjungan-kerja') }}" class="flex-1 px-6 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition text-center">Ya, Batalkan</a>
                        <button type="button" @click="showCancelModal = false" class="flex-1 px-6 py-2.5 bg-[#3B5286] text-white rounded-xl font-semibold hover:bg-[#2E4068] transition shadow-lg shadow-blue-900/20">Tidak, Lanjutkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FlatPickr for Date
            flatpickr("#tanggal_kunjungan", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "l, j F Y",
                locale: "id",
                allowInput: true
            });

            // FlatPickr for Time
            flatpickr("#waktu", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                allowInput: true
            });

            // TomSelect Instances
            new TomSelect('#anggota_dewan_id', { plugins: ['remove_button'], placeholder: 'Pilih anggota dewan...' });
            new TomSelect('#rombongan', { 
                plugins: ['remove_button'], 
                create: true, 
                placeholder: 'Pilih pelaksana atau ketik nama baru...' 
            });
            @if(auth()->user()->isSuperAdmin())
            new TomSelect('#petugas_id', { plugins: ['remove_button'], placeholder: 'Pilih petugas protokol...' });
            @endif
            
            // Jenis Kunjungan TomSelect - fix Alpine.js v3 integration
            const selectJenis = document.getElementById('id_jenis_kunjungan');
            const tsJenis = new TomSelect('#id_jenis_kunjungan', { 
                placeholder: 'Pilih jenis kunjungan...',
                onChange: function(value) {
                    // Alpine.js v3: dispatch native 'change' event so x-model updates
                    selectJenis.value = value;
                    selectJenis.dispatchEvent(new Event('change', { bubbles: true }));

                    // Also update tipe_tujuan hidden field directly
                    const tipeTujuanInput = document.querySelector('input[name="tipe_tujuan"]');
                    if (tipeTujuanInput) {
                        tipeTujuanInput.value = (value == 2 || value == 4) ? 'luar_negeri' : 'dalam_negeri';
                    }
                }
            });

            // Set initial tipe_tujuan value on load
            const tipeTujuanInput = document.querySelector('input[name="tipe_tujuan"]');
            if (tipeTujuanInput && !tipeTujuanInput.value) {
                tipeTujuanInput.value = 'dalam_negeri';
            }
            
            new TomSelect('#id_provinsi', { placeholder: 'Cari Provinsi...' });

            // File Upload Preview
            const fileInput = document.getElementById('file-upload');
            const fileNameDisplay = document.getElementById('file-name');
            if(fileInput && fileNameDisplay) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        fileNameDisplay.innerHTML = `<span class="font-medium text-[#3B5286]">${file.name}</span> <span class="text-xs text-gray-500">(${(file.size/1024).toFixed(1)} KB)</span>`;
                    } else {
                        @if($item->file_path)
                            fileNameDisplay.innerHTML = 'File saat ini: <span class="font-medium text-[#3B5286]">{{ basename($item->file_path) }}</span><br><span class="text-xs text-gray-400">Drag & drop file baru untuk mengganti</span>';
                        @else
                            fileNameDisplay.innerHTML = 'Drag & drop file di sini atau <span class="text-blue-500 font-medium">klik untuk browse</span>';
                        @endif
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
