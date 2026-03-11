<x-app-layout>
    {{-- Global Styles for interactivity --}}
    <style>
        .officer-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .officer-card.active {
            background-color: #f8fafc;
            border-color: #3B5286;
            transform: translateX(8px);
            box-shadow: 0 10px 15px -3px rgba(59, 82, 134, 0.1);
        }
        .progress-glow {
            box-shadow: 0 0 10px rgba(59, 82, 134, 0.2);
        }
        .detail-item {
            transition: all 0.2s ease;
        }
        .detail-item:hover {
            transform: scale(1.02);
            background-color: #f1f5f9;
        }
    </style>

    <div x-data="penugasanHandler()" x-init="init()" class="-mt-2 pb-12">
        {{-- Removed max-w-7xl container to match other modules width --}}

            {{-- Filter Bar --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
                <form action="{{ route('penugasan-protokol') }}" method="GET"
                    class="flex flex-col lg:flex-row gap-4 items-center">

                    {{-- Month Filter --}}
                    <div class="w-full lg:w-44">
                        <select name="month" onchange="this.form.submit()"
                            class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Year Filter --}}
                    <div class="w-full lg:w-36">
                        <select name="year" onchange="this.form.submit()"
                            class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg">
                            <option value="">Semua Tahun</option>
                            @foreach(range(date('Y'), 2020) as $y)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Search --}}
                    <div class="relative flex-1 w-full" x-data="{ searchQuery: '{{ request('search') }}' }">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" x-model="searchQuery"
                            class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Cari nama petugas...">
                        
                        {{-- Clear Button --}}
                        <button type="button" x-show="searchQuery.length > 0" 
                            @click="searchQuery = ''; $nextTick(() => $el.closest('form').submit())"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition" x-cloak>
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Reset Button --}}
                    <div class="w-full lg:w-auto">
                        <a href="{{ route('penugasan-protokol') }}"
                            class="inline-flex items-center justify-center w-full lg:w-auto gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition shadow-sm whitespace-nowrap border border-gray-200">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Skeleton Loading --}}
            <div x-show="pageLoading" x-cloak class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-pulse">
                {{-- Left Side Skeleton --}}
                <div class="lg:col-span-7 space-y-3">
                    @for($i = 0; $i < 6; $i++)
                        <div class="bg-white rounded-2xl p-4 border border-gray-100 flex items-center gap-4 shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100"></div>
                            <div class="flex-1 space-y-3">
                                <div class="flex justify-between items-center">
                                    <div class="h-4 bg-gray-200 rounded-md w-1/3"></div>
                                    <div class="h-6 bg-gray-100 rounded-md w-10"></div>
                                </div>
                                <div class="h-3 bg-gray-50 rounded-full w-full"></div>
                            </div>
                        </div>
                    @endfor
                </div>
                {{-- Right Side Skeleton --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="h-28 bg-white rounded-3xl border border-gray-100 shadow-sm"></div>
                        <div class="h-28 bg-gray-200 rounded-3xl shadow-sm opacity-50"></div>
                    </div>
                    <div class="h-[400px] bg-white rounded-3xl border border-gray-100 shadow-sm"></div>
                </div>
            </div>

            {{-- Main Content --}}
            <div x-show="!pageLoading" x-cloak class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- Left Column: List Petugas (Spans 7 columns) --}}
                <div class="lg:col-span-7 space-y-3">

                    @forelse($petugas as $index => $p)
                        @php
                            $style = $p->style;
                            $maxCount = $petugas->max('total_count') ?: 1;
                            $percentage = ($p->total_count / $maxCount) * 100;
                            $percentage = max($percentage, 8); 
                        @endphp

                        <div @click="selectOfficer({{ $p->id_petugas }}, '{{ $style['header'] }}', '{{ $style['hex_from'] }}', '{{ $style['hex_to'] }}')"
                            :class="{'active': selectedId === {{ $p->id_petugas }}}"
                            class="officer-card group relative bg-white rounded-2xl p-4 border border-gray-100 cursor-pointer hover:border-blue-200 shadow-sm overflow-hidden">
                            
                            {{-- Decorative Background Glow --}}
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-gray-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                            <div class="flex items-center gap-4 relative z-10">
                                {{-- Avatar with Initials --}}
                                <div class="relative">
                                    <div class="w-12 h-12 rounded-2xl {{ $style['bg'] }} {{ $style['text'] }} flex items-center justify-center text-lg font-black shadow-inner ring-4 ring-white">
                                        {{ strtoupper(substr($p->nama, 0, 1)) }}
                                    </div>
                                    <div x-show="selectedId === {{ $p->id_petugas }}" 
                                         class="absolute -bottom-1 -right-1 w-5 h-5 bg-blue-600 border-2 border-white rounded-full flex items-center justify-center shadow-sm">
                                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>

                                {{-- Details --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-end mb-2">
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-base group-hover:text-blue-600 transition truncate pr-4">
                                                {{ $p->nama }}
                                            </h3>
                                            <p class="text-xs text-gray-500 font-medium">Top Performer • {{ $p->total_count }} Penugasan</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-2xl font-black text-gray-900 leading-none">{{ $p->total_count }}</span>
                                        </div>
                                    </div>

                                    {{-- Custom Progress Bar --}}
                                    <div class="relative h-3 w-full bg-gray-100 rounded-full overflow-hidden shadow-inner">
                                        <div class="absolute inset-y-0 left-0 rounded-full bg-gradient-to-r {{ $style['bar'] }} transition-all duration-1000 ease-out progress-glow"
                                            style="width: {{ $percentage }}%">
                                            {{-- Subtle Shine --}}
                                            <div class="absolute inset-0 bg-white/20 skew-x-[-20deg] animate-pulse"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-200">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-gray-900 font-bold">Belum ada data</h3>
                            <p class="text-gray-400 text-sm mt-1">Coba sesuaikan kata kunci pencarian Anda.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Right Column: Stats & Detail (Spans 5 columns) --}}
                <div class="lg:col-span-5 space-y-6">

                    {{-- Dynamic Summary Bar --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex flex-col items-center justify-center text-center">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-1.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span class="text-xl font-black text-gray-900 leading-tight">{{ $totalPetugas }}</span>
                            <span class="text-[9px] uppercase tracking-wider text-gray-400 font-bold">Total Personel</span>
                        </div>
                        <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-4 rounded-3xl shadow-lg shadow-orange-200 flex flex-col items-center justify-center text-center text-white">
                            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center mb-1.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-xl font-black leading-tight">{{ $totalPenugasan }}</span>
                            <span class="text-[9px] uppercase tracking-wider text-orange-100 font-bold">Total Penugasan</span>
                        </div>
                    </div>

                    {{-- Detail Card (Dynamic) --}}
                    <div x-show="selectedId" 
                         x-transition:enter="transition ease-out duration-400"
                         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         class="bg-white rounded-3xl shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden relative"
                         style="display: none;">
                        
                        {{-- Force Tailwind to compile these classes --}}
                        <div class="hidden from-indigo-600 to-blue-700 from-emerald-500 to-teal-600 from-amber-500 to-orange-600 from-rose-500 to-pink-600 from-violet-500 to-purple-600 from-sky-500 to-indigo-600"></div>
                        
                        {{-- Loading Overlay --}}
                        <div x-show="isLoading" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-20 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto"></div>
                                <p class="text-xs text-gray-500 font-bold mt-4 animate-pulse uppercase tracking-widest">Sinkronisasi Data</p>
                            </div>
                        </div>

                        {{-- Card Header --}}
                        <div class="relative h-20 p-4 flex items-center bg-gradient-to-r"
                             :class="activeHeaderStyle"
                             :style="activeHexFrom ? `background: linear-gradient(to right, ${activeHexFrom}, ${activeHexTo})` : ''">
                            {{-- Pattern Overlay --}}
                            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 16px 16px;"></div>
                            
                            <div class="flex items-center gap-4 relative z-10">
                                <template x-if="detailData">
                                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-xl font-black border border-white/30 shadow-xl" x-text="detailData.petugas.initial"></div>
                                </template>
                                <div>
                                    <h3 class="text-white font-black text-lg leading-tight" x-text="detailData?.petugas.nama"></h3>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Rincian Per Kategori</h4>
                                <div class="h-[1px] flex-1 bg-gray-100 mx-4"></div>
                                <span class="bg-gray-50 text-gray-500 text-[10px] font-bold px-2 py-1 rounded-lg border border-gray-100" x-text="`${detailData?.breakdown.length || 0} Kategori`"></span>
                            </div>

                            <div class="space-y-2">
                                <template x-for="item in detailData?.breakdown" :key="item.kategori">
                                    <div class="detail-item group flex items-start justify-between p-3 rounded-2xl bg-gray-50 border border-transparent hover:border-blue-100 hover:shadow-sm">
                                        <div class="flex items-start gap-3 flex-1 min-w-0">
                                            <div :class="{
                                                'bg-blue-100 text-blue-600': item.kategori.includes('[Protokol]'),
                                                'bg-purple-100 text-purple-600': item.kategori.includes('[Sidang]'),
                                                'bg-amber-100 text-amber-600': item.kategori.includes('[Dinas]'),
                                                'bg-sky-100 text-sky-600': item.kategori.includes('[Kunker]')
                                            }" class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors shrink-0 mt-0.5">
                                                {{-- Protokol: Briefcase --}}
                                                <svg x-show="item.kategori.includes('[Protokol]')" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                {{-- Sidang: Building --}}
                                                <svg x-show="item.kategori.includes('[Sidang]')" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                                {{-- Dinas: Folder --}}
                                                <svg x-show="item.kategori.includes('[Dinas]')" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/></svg>
                                                {{-- Kunker: Paper Plane --}}
                                                <svg x-show="item.kategori.includes('[Kunker]')" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
                                            </div>
                                            <span class="text-xs font-bold text-gray-700 whitespace-normal leading-relaxed" x-text="item.kategori"></span>
                                        </div>
                                        <div class="flex items-center gap-2 ml-4">
                                            <span class="text-sm font-black text-gray-900" x-text="item.jumlah"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <template x-if="detailData?.breakdown.length === 0">
                                <div class="text-center py-8">
                                    <p class="text-sm text-gray-300 italic font-medium">Data rincian belum tersedia.</p>
                                </div>
                            </template>
                        </div>

                        {{-- Card Footer --}}
                        <div class="p-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Akumulasi Penugasan</span>
                            <div class="flex items-center gap-2">
                                <span class="text-2xl font-black text-gray-900" x-text="detailData?.total_penugasan"></span>
                                <span class="text-[10px] text-gray-400 font-bold leading-tight">Total<br>Tugas</span>
                            </div>
                        </div>
                    </div>

                    {{-- Empty Detail State --}}
                    <div x-show="!selectedId" class="bg-gray-50 rounded-3xl p-12 text-center border-2 border-dashed border-gray-200">
                        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>
                        <p class="text-gray-400 font-medium">Pilih salah satu petugas untuk melihat rincian penugasannya secara lengkap.</p>
                    </div>

                </div>
            </div>
        {{-- End of main content --}}
    </div>

    <script>
        function penugasanHandler() {
            return {
                pageLoading: true,
                selectedId: null,
                activeHeaderStyle: '',
                activeHexFrom: '',
                activeHexTo: '',
                isLoading: false,
                detailData: null,

                init() {
                    setTimeout(() => this.pageLoading = false, 800);
                    // Start with the first officer if available
                    @if($petugas->count() > 0)
                        @php $first = $petugas->first(); @endphp
                        this.selectOfficer({{ $first->id_petugas }}, '{{ $first->style['header'] }}', '{{ $first->style['hex_from'] }}', '{{ $first->style['hex_to'] }}');
                    @endif
                },

                selectOfficer(id, headerStyle, hexFrom, hexTo) {
                    if (this.selectedId === id && this.detailData) return;

                    this.selectedId = id;
                    this.activeHeaderStyle = headerStyle;
                    this.activeHexFrom = hexFrom;
                    this.activeHexTo = hexTo;
                    this.isLoading = true;
                    this.detailData = null;

                    // Build query params for filters
                    const month = "{{ request('month') }}";
                    const year = "{{ request('year') }}";
                    let url = `{{ url('/penugasan-protokol') }}/${id}`;
                    
                    const params = new URLSearchParams();
                    if (month) params.append('month', month);
                    if (year) params.append('year', year);
                    
                    if (params.toString()) {
                        url += `?${params.toString()}`;
                    }

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            this.detailData = data;
                            this.isLoading = false;
                        })
                        .catch(error => {
                            console.error('Error fetching officer details:', error);
                            this.isLoading = false;
                        });
                }
            }
        }
    </script>
</x-app-layout>
