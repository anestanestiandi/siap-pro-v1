<x-app-layout>

    {{-- Full Page Wrapper --}}
    <div x-data="{
        loading: true,
        init() { setTimeout(() => this.loading = false, 800) },
        isDown: false,
        startX: 0,
        scrollLeft: 0,
        handleMouseDown(e) {
            this.isDown = true;
            this.startX = e.pageX - this.$refs.scrollContainer.offsetLeft;
            this.scrollLeft = this.$refs.scrollContainer.scrollLeft;
        },
        handleMouseLeave() {
            this.isDown = false;
        },
        handleMouseUp() {
            this.isDown = false;
        },
        handleMouseMove(e) {
            if (!this.isDown) return;
            e.preventDefault();
            const x = e.pageX - this.$refs.scrollContainer.offsetLeft;
            const walk = (x - this.startX) * 2; // Scroll-fast
            this.$refs.scrollContainer.scrollLeft = this.scrollLeft - walk;
        }
    }">

        {{-- Summary Cards (Dynamic Horizontal Scroll) --}}
        <div class="-mt-2 mb-10">

            {{-- Skeleton Cards --}}
            <div x-show="loading" x-cloak class="flex gap-4 overflow-hidden pb-4 animate-pulse">
                @for($i = 0; $i < 3; $i++)
                    <div
                        class="w-60 flex-shrink-0 bg-white rounded-xl p-3 border border-gray-100 flex justify-between shadow-sm">
                        <div>
                            <div class="w-10 h-10 rounded-lg bg-gray-200 mb-3"></div>
                            <div class="h-8 bg-gray-200 rounded w-24 mb-2"></div>
                            <div class="h-4 bg-gray-200 rounded w-32"></div>
                        </div>
                        <div class="self-start">
                            <div class="h-6 bg-gray-200 rounded w-12"></div>
                        </div>
                    </div>
                @endfor
            </div>
            <div x-show="!loading" x-cloak x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100" x-ref="scrollContainer"
                @mousedown="handleMouseDown" @mouseleave="handleMouseLeave" @mouseup="handleMouseUp"
                @mousemove="handleMouseMove"
                class="flex overflow-x-auto pb-4 gap-4 no-scrollbar cursor-grab active:cursor-grabbing select-none">

                {{-- 1. Total Kegiatan (Biru) --}}
                <div
                    class="w-60 flex-shrink-0 bg-gradient-to-br from-blue-100 to-blue-200/50 rounded-xl p-3 border border-blue-200 flex justify-between hover:shadow-md transition">
                    <div>
                        <div
                            class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 mb-3">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($totalKegiatan) }}</div>
                        <div class="text-sm text-gray-500 mt-1">
                            Total Kegiatan
                            @if(request('start_date') && request('end_date'))
                                <span class="font-medium text-blue-600 block sm:inline">
                                    ({{ \Carbon\Carbon::parse(request('start_date'))->isoFormat('D MMM') }} - {{ \Carbon\Carbon::parse(request('end_date'))->isoFormat('D MMM Y') }})
                                </span>
                            @elseif(request('month'))
                                <span
                                    class="font-medium text-blue-600 block sm:inline">({{ \Carbon\Carbon::parse(request('month'))->isoFormat('MMMM Y') }})</span>
                            @elseif(request('year'))
                                <span class="font-medium text-blue-600 block sm:inline">({{ request('year') }})</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- 2. Dynamic Jenis Perjalanan Cards --}}
                @foreach($jenisPerjalananSummary as $jenis)
                    <div
                        class="w-60 flex-shrink-0 {{ $jenis->style['bg'] }} rounded-xl p-3 border {{ $jenis->style['border'] }} flex justify-between hover:shadow-md transition">
                        <div>
                            <div
                                class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $jenis->style['icon_bg'] }} flex items-center justify-center text-white shadow-lg {{ $jenis->style['shadow'] }} mb-3">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ $jenis->total }}</div>
                            <div class="text-sm text-gray-500 line-clamp-2" title="{{ $jenis->nama_jenis }}">
                                {{ $jenis->nama_jenis }}
                            </div>
                        </div>
                        <div class="self-start">
                            <span
                                class="text-xs font-medium {{ $jenis->style['text'] }} {{ $jenis->style['chip_bg'] }} px-2 py-1 rounded">{{ $jenis->label }}</span>
                        </div>
                    </div>
                @endforeach

                {{-- 3. Petugas Protokol (Ungu) --}}
                <div class="w-60 flex-shrink-0 bg-gradient-to-br from-purple-100 to-purple-200/50 rounded-xl p-3 border border-purple-200 flex justify-between hover:shadow-md transition">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30 mb-3">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($totalProtokol ?? 0) }}</div>
                        <div class="text-sm text-gray-500 mt-1">Petugas Protokol</div>
                    </div>
                </div>

            </div>

        <div x-data="{ 
                showDeleteModal: false, 
                deleteUrl: '',
                confirmDelete(url) {
                    this.deleteUrl = url;
                    this.showDeleteModal = true;
                }
             }">

            {{-- Daftar Kegiatan --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                {{-- Table Header --}}
                <div
                    class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <h2 class="text-base font-bold text-gray-900">Administrasi Perjalanan Dinas</h2>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#3B5286] text-white">
                            {{ now()->isoFormat('dddd, D MMMM Y') }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        {{-- Filter Dropdown --}}
                        <div x-data="{ open: false, filterType: '{{ request('start_date') ? 'range' : (request('month') ? 'month' : (request('year') ? 'year' : 'range')) }}' }"
                            class="relative" @click.outside="open = false">
                            <button @click="open = !open"
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">
                                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                                </svg>
                                Filter
                                <svg class="w-3 h-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 origin-top-right">
                                
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <h3 class="font-medium text-sm text-gray-900">Filter Data</h3>
                                </div>

                                <div class="p-4 space-y-1">
                                    {{-- Filter Type Tabs --}}
                                    <div class="flex p-1 bg-gray-50 rounded-lg mb-3">
                                         <button type="button" @click="filterType = 'range'"
                                             :class="filterType === 'range' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                             class="flex-1 py-1.5 text-xs font-medium rounded-md transition text-center">
                                             Rentang
                                         </button>
                                         <button type="button" @click="filterType = 'month'"
                                             :class="filterType === 'month' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                             class="flex-1 py-1.5 text-xs font-medium rounded-md transition text-center">
                                             Bulanan
                                         </button>
                                         <button type="button" @click="filterType = 'year'"
                                             :class="filterType === 'year' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                             class="flex-1 py-1.5 text-xs font-medium rounded-md transition text-center">
                                             Tahunan
                                         </button>
                                     </div>

                                    <form action="{{ route('administrasi-perjalanan-dinas') }}" method="GET">
                                        {{-- Preserve existing search if any --}}
                                        @if(request('search'))
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                        @endif

                                        {{-- Range Filter --}}
                                        <div x-show="filterType === 'range'">
                                            <div class="grid grid-cols-2 gap-2">
                                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                                    :disabled="filterType !== 'range'"
                                                    class="w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                                                    placeholder="Mulai">
                                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                                    :disabled="filterType !== 'range'"
                                                    class="w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                                                    placeholder="Selesai">
                                            </div>
                                        </div>

                                        {{-- Month Filter --}}
                                        <div x-show="filterType === 'month'">
                                            <input type="month" name="month" value="{{ request('month') }}"
                                                :disabled="filterType !== 'month'"
                                                class="w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                        </div>

                                        {{-- Year Filter --}}
                                        <div x-show="filterType === 'year'">
                                            <select name="year" :disabled="filterType !== 'year'"
                                                class="w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                                @foreach(range(date('Y'), 2020) as $year)
                                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                                        {{ $year }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-3 flex gap-2">
                                            <a href="{{ route('administrasi-perjalanan-dinas') }}"
                                                class="flex-1 px-3 py-2 text-xs font-medium text-center text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                                Reset
                                            </a>
                                            <button type="submit"
                                                class="flex-1 px-3 py-2 text-xs font-medium text-white bg-[#3B5286] rounded-lg hover:bg-[#354A79] transition shadow-sm">
                                                Terapkan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Export Button & Modal --}}
                        <div x-data="{ exportOpen: false, exportFormat: 'pdf', selectAll: true, columns: { tanggal: true, waktu: true, nama_kegiatan: true, jenis_perjalanan: true, tujuan: true, pelaksana: true } }" 
                             class="relative">
                            <button @click="exportOpen = true"
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition shadow-sm">
                                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                Export
                            </button>

                            {{-- Modal Backdrop --}}
                            <div x-show="exportOpen" 
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
                                style="display: none;">
                                
                                {{-- Modal Content --}}
                                <div @click.outside="exportOpen = false"
                                    class="bg-white rounded-2xl shadow-xl w-full max-w-2xl flex flex-col max-h-[90vh] overflow-hidden transform transition-all">
                                    
                                    {{-- Header --}}
                                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                                <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                                </svg>
                                                Export Preview & Settings
                                            </h3>
                                            <p class="text-xs text-gray-500 mt-1">Konfigurasi kolom data dan format file untuk penarikan laporan kegiatan</p>
                                        </div>
                                    <button @click="exportOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <form action="{{ route('administrasi-perjalanan-dinas') }}" method="GET" class="flex flex-col flex-1 overflow-hidden">
                                        {{-- Preserve filters --}}
                                        @if(request('date')) <input type="hidden" name="date" value="{{ request('date') }}"> @endif
                                        @if(request('month')) <input type="hidden" name="month" value="{{ request('month') }}"> @endif
                                        @if(request('year')) <input type="hidden" name="year" value="{{ request('year') }}"> @endif
                                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                                        <div class="p-5 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
                                            {{-- Info Alert --}}
                                            <div class="flex gap-3 p-4 bg-blue-50 text-blue-800 rounded-xl border border-blue-100 text-sm">
                                                <svg class="w-5 h-5 flex-shrink-0 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                </svg>
                                                <p>Kolom <span class="font-bold">'Nomor Urut'</span> akan ditambahkan secara otomatis sebagai identitas baris.</p>
                                            </div>

                                            {{-- Column Selection --}}
                                            <div>
                                                <div class="flex items-center justify-between mb-3">
                                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pilih Kolom Data</label>
                                                </div>
                                                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                                                    {{-- Fixed Column --}}
                                                    <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-not-allowed opacity-75">
                                                        <input type="checkbox" checked disabled class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-not-allowed">
                                                        <span class="text-sm font-medium text-gray-700">Nomor Urut</span>
                                                    </label>
                                                    {{-- Selection Columns --}}
                                                    <label class="flex items-center gap-2 p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition cursor-pointer">
                                                        <input type="checkbox" name="columns[]" value="tanggal" x-model="columns.tanggal" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <span class="text-sm font-medium text-gray-700">Tanggal</span>
                                                    </label>
                                                    <label class="flex items-center gap-2 p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition cursor-pointer">
                                                        <input type="checkbox" name="columns[]" value="waktu" x-model="columns.waktu" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <span class="text-sm font-medium text-gray-700">Pukul</span>
                                                    </label>
                                                    <label class="flex items-center gap-2 p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition cursor-pointer">
                                                        <input type="checkbox" name="columns[]" value="nama_kegiatan" x-model="columns.nama_kegiatan" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <span class="text-sm font-medium text-gray-700">Nama Kegiatan</span>
                                                    </label>
                                                    <label class="flex items-center gap-2 p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition cursor-pointer">
                                                        <input type="checkbox" name="columns[]" value="jenis_perjalanan" x-model="columns.jenis_perjalanan" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <span class="text-sm font-medium text-gray-700">Jenis Perjalanan</span>
                                                    </label>
                                                    <label class="flex items-center gap-2 p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition cursor-pointer">
                                                        <input type="checkbox" name="columns[]" value="tujuan" x-model="columns.tujuan" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <span class="text-sm font-medium text-gray-700">Tujuan</span>
                                                    </label>
                                                     <label class="flex items-center gap-2 p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition cursor-pointer">
                                                        <input type="checkbox" name="columns[]" value="pelaksana" x-model="columns.pelaksana" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <span class="text-sm font-medium text-gray-700">Pelaksana</span>
                                                    </label>
                                                    
                                                </div>
                                            </div>

                                            {{-- Format Selection --}}
                                            <div>
                                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-3">Pilih Format File</label>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    {{-- PDF --}}
                                                    <label class="relative group cursor-pointer">
                                                        <input type="radio" name="export" value="pdf" x-model="exportFormat" class="peer sr-only">
                                                        <div class="p-4 rounded-xl border-2 transition-all flex flex-col items-center text-center gap-3 peer-checked:border-blue-600 peer-checked:bg-blue-50/50 border-gray-200 hover:border-gray-300">
                                                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 mb-1">
                                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <div class="font-bold text-gray-900">PDF Document</div>
                                                                 <div class="text-xs text-gray-500 mt-1">Cocok untuk laporan cetak resmi</div>
                                                            </div>
                                                        </div>
                                                        <div class="absolute top-4 right-4 text-blue-600 opacity-0 peer-checked:opacity-100 transition">
                                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                    </label>

                                                    {{-- Excel --}}
                                                    <label class="relative group cursor-pointer">
                                                        <input type="radio" name="export" value="excel" x-model="exportFormat" class="peer sr-only">
                                                        <div class="p-4 rounded-xl border-2 transition-all flex flex-col items-center text-center gap-3 peer-checked:border-green-600 peer-checked:bg-green-50/50 border-gray-200 hover:border-gray-300">
                                                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 mb-1">
                                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <div class="font-bold text-gray-900">Excel Sheet</div>
                                                                <div class="text-xs text-gray-500 mt-1">Dapat diolah kembali untuk perhitungan data</div>
                                                            </div>
                                                        </div>
                                                        <div class="absolute top-4 right-4 text-green-600 opacity-0 peer-checked:opacity-100 transition">
                                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Footer --}}
                                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                                            <button type="button" @click="exportOpen = false"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-white bg-[#3B5286] rounded-lg hover:bg-[#2c3e66] transition shadow-md">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                                </svg>
                                                Export Sekarang
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                {{-- Table --}}
                <div class="overflow-x-auto rounded-b-xl overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="pl-6 pr-3 py-3 text-xs font-semibold text-gray-500 uppercase text-center w-[10%]">
                                    Tanggal
                                </th>
                                <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase text-center w-[7%]">Pukul
                                </th>

                                <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase text-left w-[15%]">Pelaksana
                                </th>
                                <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase text-left w-[25%]">Nama
                                    Kegiatan
                                </th>
                                <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase text-left w-[15%]">Tujuan
                                </th>
                                <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase text-left w-[12%]">Protokol
                                </th>
                                <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase text-left w-[12%]">Jenis
                                </th>
                                <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase text-center">File
                                </th>
                                <th class="px-3 py-3 text-xs font-semibold text-gray-500 uppercase text-left">
                                    Last
                                    Update
                                </th>
                            </tr>
                        </thead>
                        <tbody x-show="loading" x-cloak class="animate-pulse">
                            @for ($i = 0; $i < 5; $i++)
                                <tr class="border-b border-gray-100 last:border-b-0">
                                    {{-- Tanggal --}}
                                    <td class="px-3 py-4">
                                        <div class="h-4 bg-gray-200 rounded w-16 mx-auto"></div>
                                    </td>
                                    {{-- Pukul --}}
                                    <td class="px-3 py-4">
                                        <div class="h-6 bg-gray-200 rounded-full w-12 mx-auto"></div>
                                    </td>
                                    {{-- Pelaksana --}}
                                    <td class="px-3 py-4">
                                        <div class="h-4 bg-gray-200 rounded w-24"></div>
                                    </td>
                                    {{-- Kegiatan --}}
                                    <td class="px-3 py-4">
                                        <div class="h-4 bg-gray-200 rounded w-48 mb-2"></div>
                                        <div class="h-3 bg-gray-100 rounded w-32"></div>
                                    </td>
                                    {{-- Tujuan --}}
                                    <td class="px-3 py-4">
                                        <div class="h-4 bg-gray-200 rounded w-24"></div>
                                    </td>
                                    {{-- Protokol --}}
                                    <td class="px-3 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 bg-gray-200 rounded-full"></div>
                                            <div class="h-4 bg-gray-200 rounded w-20"></div>
                                        </div>
                                    </td>
                                    {{-- Jenis --}}
                                    <td class="px-3 py-4">
                                        <div class="h-6 bg-gray-200 rounded-lg w-20"></div>
                                    </td>
                                    {{-- File --}}
                                    <td class="px-3 py-4 text-center">
                                        <div class="h-5 w-5 bg-gray-200 rounded mx-auto"></div>
                                    </td>
                                    {{-- Update --}}
                                    <td class="pl-3 pr-6 py-4">
                                        <div class="h-4 bg-gray-200 rounded w-24 mb-1"></div>
                                        <div class="h-3 bg-gray-100 rounded w-12"></div>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>

                        <tbody class="divide-y divide-gray-100" x-show="!loading" x-cloak
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 transform scale-98"
                            x-transition:enter-end="opacity-100 transform scale-100">
                            @forelse($kegiatan as $item)
                                <tr class="hover:bg-[#EFF6FF] transition">
                                    {{-- Tanggal --}}
                                    <td class="px-3 py-4 whitespace-nowrap text-center text-xs">
                                        @php
                                            $today = now()->startOfDay();
                                            $start = $item->tanggal_mulai ? $item->tanggal_mulai->copy()->startOfDay() : null;
                                            $end = $item->tanggal_selesai ? $item->tanggal_selesai->copy()->startOfDay() : $start;
                                            
                                            $isOnProgress = $start && $today->between($start, $end);
                                            $isUpcoming = $start && $today->lt($start);
                                            
                                            $chipClass = $isOnProgress ? 'bg-blue-50 text-blue-700 border-blue-100' : ($isUpcoming ? 'bg-red-50 text-red-700 border-red-100' : 'text-gray-500 italic');
                                            $hasBg = $isOnProgress || $isUpcoming;
                                        @endphp
                                        <div class="inline-block px-2 py-1 rounded-lg font-semibold {{ $hasBg ? $chipClass . ' border' : $chipClass }}">
                                            {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/y') : '-' }}
                                            @if($item->tanggal_selesai && $item->tanggal_selesai != $item->tanggal_mulai)
                                                <span class="mx-0.5 opacity-50">-</span>{{ $item->tanggal_selesai->format('d/m/y') }}
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Pukul --}}
                                    <td class="px-3 py-4 text-center">
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 text-center min-w-[60px]">
                                            {{ $item->waktu ? \Carbon\Carbon::parse($item->waktu)->format('H:i') : '-' }}
                                        </span>
                                    </td>


                                     {{-- Pelaksana --}}
                                     <td class="px-3 py-4 text-left">
                                        @php
                                            $raw = $item->pelaksana;
                                            $p_list = [];
                                            
                                            if (strpos($raw, ';') !== false) {
                                                $p_list = explode(';', $raw);
                                            } else {
                                                $parts = explode(',', $raw);
                                                $current = "";
                                                foreach ($parts as $part) {
                                                    $part = trim($part);
                                                    if ($current && (str_contains($part, '.') || strlen($part) <= 5)) {
                                                        $current .= ", " . $part;
                                                    } else {
                                                        if ($current) $p_list[] = $current;
                                                        $current = $part;
                                                    }
                                                }
                                                if ($current) $p_list[] = $current;
                                            }
                                            $p_list = array_filter(array_map('trim', $p_list));
                                        @endphp
                                        @if(count($p_list) > 0)
                                            <div class="flex flex-col gap-1 text-gray-900">
                                                @foreach(array_slice($p_list, 0, 2) as $p_name)
                                                    <div class="text-sm font-medium">{{ $p_name }}</div>
                                                @endforeach
                                                @if(count($p_list) > 2)
                                                    <p class="text-[10px] text-gray-400 font-medium">+{{ count($p_list) - 2 }} lainnya</p>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    {{-- Nama Kegiatan --}}
                                    <td class="px-3 py-4 text-left">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $item->nama_kegiatan }}</p>
                                        </div>
                                    </td>

                                    {{-- Tujuan --}}
                                    <td class="px-3 py-4">
                                        <div class="flex items-center gap-1.5 text-gray-700">
                                            <svg class="w-3.5 h-3.5 text-red-400 flex-shrink-0"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                            </svg>
                                            {{ $item->tujuan ?? '-' }}
                                        </div>
                                    </td>
                                    
                                    {{-- Protokol --}}
                                    <td class="px-3 py-4 text-left">
                                        @if($item->petugas->count() > 0)
                                            <div class="flex flex-col gap-2">
                                                @foreach($item->petugas->take(2) as $petugas)
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 flex-shrink-0">
                                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                        <span class="text-sm font-medium text-gray-900">{{ $petugas->nama }}</span>
                                                    </div>
                                                @endforeach
                                                @if($item->petugas->count() > 2)
                                                    <p class="text-[10px] text-gray-400 mt-1 pl-11 font-medium">+{{ $item->petugas->count() - 2 }} lainnya</p>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>

                                    {{-- Jenis --}}
                                    <td class="px-3 py-4 text-left">
                                        <span class="text-sm text-gray-600">
                                            {{ $item->jenisPerjalananDinas?->nama_jenis ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- File --}}
                                    <td class="px-3 py-4 text-center">
                                        @if($item->file_path)
                                            <a href="{{ Storage::url($item->file_path) }}" target="_blank"
                                                class="inline-flex items-center justify-center text-blue-600 hover:text-blue-800 transition">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                </svg>
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    {{-- Last Update & Actions --}}
                                    <td class="px-3 py-4">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="text-left">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ $item->updater->nama_lengkap ?? $item->creator->nama_lengkap ?? 'System' }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $item->updated_at ? $item->updated_at->diffForHumans() : $item->created_at->diffForHumans() }}
                                                </p>
                                            </div>

                                            {{-- Action Dropdown --}}
                                            <div x-data="{ open: false, top: 0, left: 0 }" class="relative">
                                                <button
                                                    @click="open = !open; $nextTick(() => { const rect = $el.getBoundingClientRect(); top = rect.bottom; left = rect.right - 160; })"
                                                    @click.away="open = false"
                                                    class="text-gray-400 hover:text-gray-600 transition p-1.5 hover:bg-gray-100 rounded-full focus:outline-none">
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                                    </svg>
                                                </button>

                                                {{-- Dropdown Menu (Teleported) --}}
                                                <template x-teleport="body">
                                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="transform opacity-0 scale-95"
                                                        x-transition:enter-end="transform opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="transform opacity-100 scale-100"
                                                        x-transition:leave-end="transform opacity-0 scale-95"
                                                        class="fixed w-40 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-gray-100 z-[9999] py-1"
                                                        :style="`top: ${top}px; left: ${left}px`" style="display: none;">

                                                        {{-- View --}}
                                                        <a href="{{ route('administrasi-perjalanan-dinas.show', $item->id_adm_perjalanan_dinas) }}"
                                                            class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                                                            <span class="font-medium">View</span>
                                                            <svg class="w-5 h-5 text-blue-500/70" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </a>

                                                        @if(auth()->user()->canManageData())
                                                        {{-- Edit --}}
                                                        <a href="{{ route('administrasi-perjalanan-dinas.edit', $item->id_adm_perjalanan_dinas) }}"
                                                            class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 bg-gray-50/50 hover:bg-gray-100 transition-colors">
                                                            <span class="font-bold">Edit</span>
                                                            <svg class="w-5 h-5 text-blue-600/80" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>

                                                        {{-- Delete --}}
                                                        <button type="button"
                                                                @click="confirmDelete('{{ route('administrasi-perjalanan-dinas.destroy', $item->id_adm_perjalanan_dinas) }}')"
                                                                class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                                                            <span class="font-medium">Delete</span>
                                                            <svg class="w-5 h-5 text-red-500/80" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                        @endif
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <p class="text-base font-medium text-gray-900">Belum ada kegiatan</p>
                                            <p class="text-sm text-gray-400 mt-1">Silakan tambahkan kegiatan baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $kegiatan->links('vendor.pagination.custom') }}
                </div>
            </div>

            {{-- Delete Confirmation Modal --}}
            <div x-show="showDeleteModal" 
                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">
                
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative"
                     @click.outside="showDeleteModal = false"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="scale-95 opacity-0"
                     x-transition:enter-end="scale-100 opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="scale-100 opacity-100"
                     x-transition:leave-end="scale-95 opacity-0">
                    
                    <button type="button" @click="showDeleteModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="text-center sm:text-left">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto sm:mx-0 mb-6">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Kegiatan?</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-8">
                            Apakah Anda yakin ingin menghapus data kegiatan ini? Tindakan ini tidak dapat dibatalkan dan data akan hilang permanen.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="button" @click="showDeleteModal = false"
                                    class="flex-1 px-6 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition text-center">
                                Batal
                            </button>
                            <form :action="deleteUrl" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full px-6 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition shadow-lg shadow-red-900/20">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
