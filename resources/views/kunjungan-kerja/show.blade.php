<x-app-layout :item="$item">
    {{-- Main Container --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        
        {{-- Back Button --}}
        <div class="mb-4">
            <a href="{{ route('kunjungan-kerja') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-[#3B5286] transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- Left Column: Activity Details --}}
            <div class="lg:col-span-8 space-y-6">
                {{-- Main Info Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Informasi Utama</h3>
                        <span
                            class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                            {{ $item->jenisKunjungan?->nama_jenis ?? '-' }}
                        </span>
                    </div>
                    <div class="p-8">
                        <div class="space-y-8">
                            {{-- Title and Badges --}}
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 leading-tight mb-4">
                                    {{ $item->nama_kegiatan }}
                                </h1>
                                
                                <div class="flex flex-wrap gap-6 text-sm text-gray-500">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0h18m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                            </svg>
                                        </div>
                                        <span class="font-medium">
                                            @if($item->tanggal_kunjungan)
                                                {{ $item->tanggal_kunjungan->isoFormat('dddd, D MMMM Y') }}
                                                @if($item->tanggal_selesai && $item->tanggal_selesai->ne($item->tanggal_kunjungan))
                                                    - {{ $item->tanggal_selesai->isoFormat('dddd, D MMMM Y') }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </div>
                                        <span class="font-medium">
                                            {{ $item->waktu ? \Carbon\Carbon::parse($item->waktu)->format('H:i') . ' WIB' : '-' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                            </svg>
                                        </div>
                                        <span class="font-medium">
                                            @if($item->tipe_tujuan == 'dalam_negeri')
                                                {{ $item->provinsi->nama_provinsi ?? '-' }}
                                            @else
                                                Luar Negeri
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Anggota Dewan --}}
                            <div class="pt-8 border-t border-gray-50">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Anggota Dewan</h4>
                                <div class="space-y-4">
                                    @forelse($item->anggotaDewan as $anggota)
                                        <div class="flex items-center gap-4 group">
                                            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold border-2 border-white shadow-sm transition group-hover:scale-105">
                                                {{ substr($anggota->nama, 0, 1) }}
                                            </div>
                                            <div class="text-left">
                                                <p class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition truncate max-w-xs md:max-w-md">
                                                    {{ $anggota->nama }}
                                                </p>
                                                <p class="text-xs text-gray-500 font-medium">Anggota Dewan</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-400 italic">Tidak ada anggota dewan terdaftar.</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Petugas Protokol --}}
                            <div class="pt-8 border-t border-gray-50">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Petugas Protokol</h4>
                                <div class="flex flex-wrap gap-3">
                                    @forelse($item->petugas as $p)
                                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-50 text-purple-700 rounded-full border border-purple-100 hover:bg-purple-100 transition cursor-default shadow-sm group">
                                            <div class="w-5 h-5 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-purple-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <span class="text-sm font-bold">{{ $p->nama }}</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-400 italic">Belum ada petugas ditugaskan.</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Rombongan Tambahan --}}
                            @if(!empty($item->rombongan))
                                <div class="pt-8 border-t border-gray-50">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Rombongan Tambahan</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($item->rombongan as $r)
                                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium border border-gray-200">
                                                {{ $r }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Side Info / History --}}
            <div class="lg:col-span-4 space-y-6">
                {{-- Riwayat Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-8 flex items-center gap-2">
                        Riwayat Perubahan
                    </h3>

                    <div class="relative space-y-10 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[2px] before:bg-blue-50">
                        @forelse($item->historyLogs as $log)
                            <div class="relative pl-10">
                                {{-- Timeline Dot --}}
                                <div class="absolute left-0 top-1 w-6 h-6 rounded-full bg-white border-2 border-blue-400 flex items-center justify-center shadow-[0_0_10px_rgba(59,130,246,0.2)] z-10 transition group-hover:scale-110">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                                </div>

                                <div class="space-y-2">
                                    <p class="text-sm font-bold text-gray-800 leading-snug">
                                        {{ $log->description }}
                                    </p>
                                    <div class="flex flex-col gap-0.5">
                                        <p class="text-xs font-semibold text-gray-500">
                                            By: <span class="text-blue-600">{{ $log->user->nama_lengkap ?? 'System' }}</span>
                                        </p>
                                        <p class="text-[11px] font-medium text-gray-400">
                                            {{ $log->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-10 text-center grayscale opacity-50">
                                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-400 font-medium italic">Belum ada riwayat aktivitas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
