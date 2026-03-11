<x-app-layout>
    {{-- Hapus margin horizontal tambahan (mx-...) agar menggunakan padding native dari layout app (p-6)
         sehingga lebarnya sama persis (align) rata kiri dan kanan layaknya modul lain. --}}
    <div class="-mt-2" x-data="{
        loading: true,
        init() { setTimeout(() => this.loading = false, 800) },
        scrollDewan(direction) {
            const container = this.$refs.dewanContainer;
            const scrollAmount = 400;
            container.scrollBy({
                left: direction === 'right' ? scrollAmount : -scrollAmount,
                behavior: 'smooth'
            });
        }
    }">
        <style>
            @keyframes spin-slow {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .animate-spin-slow {
                animation: spin-slow 2s linear infinite;
            }
            .reveal-card {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
                transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            .reveal-card.active {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            .number-roll {
                transition: all 0.5s ease-out;
            }
        </style>
        {{-- Skeleton Loading --}}
        <div x-show="loading" x-cloak class="space-y-6">

            {{-- Your Events Skeleton --}}
            <div class="mb-10 animate-pulse">
                <div class="flex justify-between items-center mb-6 px-1">
                    <div class="h-8 bg-gray-200 rounded-lg w-40"></div>
                    <div class="flex gap-3">
                        <div class="h-9 bg-gray-100 rounded-full w-20"></div>
                        <div class="h-9 bg-gray-100 rounded-full w-32"></div>
                    </div>
                </div>
                <div class="space-y-4">
                    @for($i = 0; $i < 2; $i++)
                        <div class="bg-white rounded-[1.5rem] p-5 border border-gray-100 h-24 flex items-center gap-6">
                            <div class="w-16 h-full bg-gray-50 rounded-xl"></div>
                            <div class="flex-1 space-y-3">
                                <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                                <div class="flex gap-4">
                                    <div class="h-3 bg-gray-100 rounded w-20"></div>
                                    <div class="h-3 bg-gray-100 rounded w-24"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Section 1 Skeleton --}}

            <div class="bg-white rounded-[1.5rem] shadow-[0_10px_30px_-5px_rgba(0,0,0,0.05)] border border-gray-100/50 p-5 md:p-6 w-full mx-auto animate-pulse">
                <div class="flex justify-between items-center mb-8">
                    <div class="space-y-3">
                        <div class="h-8 bg-gray-200 rounded-lg w-48"></div>
                        <div class="h-3 bg-gray-100 rounded-lg w-64"></div>
                    </div>
                    <div class="flex gap-3">
                        <div class="h-8 bg-gray-100 rounded-full w-24"></div>
                        <div class="h-8 bg-gray-100 rounded-full w-32"></div>
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="flex-1 h-[240px] bg-gray-50 rounded-2xl border border-gray-100"></div>
                    <div class="w-full lg:w-[240px] space-y-4">
                        <div class="h-20 bg-gray-50 rounded-2xl border border-gray-100"></div>
                        @for($i = 0; $i < 4; $i++)
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-gray-200"></div>
                                <div class="h-3 bg-gray-100 rounded-lg w-full"></div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- Section 2 Skeleton --}}
            <div>
                <div class="flex justify-between items-center mb-6 px-2">
                    <div class="space-y-3">
                        <div class="h-8 bg-gray-200 rounded-lg w-48"></div>
                        <div class="h-3 bg-gray-100 rounded-lg w-64"></div>
                    </div>
                </div>
                <div class="flex gap-6 overflow-hidden px-2">
                    @for($i = 0; $i < 4; $i++)
                        <div class="flex-shrink-0 w-[240px] bg-white rounded-[1.5rem] border border-gray-100 p-6 space-y-6">
                            <div class="h-4 bg-gray-200 rounded-lg w-3/4 mx-auto"></div>
                            <div class="w-36 h-36 rounded-full bg-gray-50 mx-auto border-4 border-gray-100"></div>
                            <div class="flex gap-3 mt-auto">
                                <div class="flex-1 h-12 bg-gray-50 rounded-xl"></div>
                                <div class="flex-1 h-12 bg-gray-50 rounded-xl"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div x-show="!loading" x-cloak>

            <div x-data="{ showEvents: true }" class="mb-6 bg-white rounded-[1.5rem] shadow-[0_10px_30px_-5px_rgba(0,0,0,0.05)] border border-gray-100/50 overflow-hidden">
                <div class="flex items-center justify-between p-5 md:p-6 border-b border-gray-50 bg-white cursor-pointer select-none group" @click="showEvents = !showEvents">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <h2 class="text-2xl font-bold text-[#3B5286] tracking-tight pl-1">{{ $dateTitle }}</h2>
                            <svg class="w-5 h-5 text-[#3B5286] transition-all duration-300 transform opacity-0 group-hover:opacity-100" :class="showEvents ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>

                        {{-- Date Filter Dropdown --}}
                        <div x-data="{ open: false }" class="relative" @click.stop>
                            <button type="button" @click="open = !open" 
                                class="flex items-center justify-between gap-2 px-3 py-1 bg-white border border-gray-100 rounded-full text-[10px] font-black uppercase tracking-widest text-[#3B5286] shadow-sm hover:bg-gray-50 transition-all min-w-[90px]">
                                <span>{{ $dateFilter === 'tomorrow' ? 'Besok' : 'Hari Ini' }}</span>
                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="absolute left-0 mt-2 w-40 bg-white border border-gray-50 rounded-xl shadow-xl z-50 overflow-hidden p-1 ring-1 ring-black/5">
                                <form action="{{ route('dashboard') }}" method="GET">
                                    @foreach(['today' => 'Hari Ini', 'tomorrow' => 'Besok'] as $val => $label)
                                        <button type="submit" name="date_filter" value="{{ $val }}"
                                            class="w-full flex items-center justify-between px-3 py-2 text-[11px] font-bold rounded-lg transition-all {{ $dateFilter == $val ? 'bg-[#3B5286] text-white' : 'text-slate-600 hover:bg-[#3B5286]/5 hover:text-[#3B5286]' }}">
                                            <span>{{ $label }}</span>
                                            @if($dateFilter == $val)
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </button>
                                    @endforeach
                                    {{-- Preserve other filters if any --}}
                                    <input type="hidden" name="filter_type" value="{{ request('filter_type', 'all') }}">
                                    <input type="hidden" name="sort_year" value="{{ request('sort_year', 'desc') }}">
                                </form>
                            </div>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-[#3B5286] uppercase tracking-widest">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>

                <div x-show="showEvents" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-4"
                    class="overflow-x-auto max-h-[300px] overflow-y-auto custom-scrollbar-y">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pukul</th>
                                <th class="px-6 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Anggota Dewan</th>
                                <th class="px-6 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Kegiatan</th>
                                <th class="px-6 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tempat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($todayEvents as $event)
                                <tr class="group hover:bg-gray-50/50 transition-all duration-200">
                                    <td class="px-6 py-3.5">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-2.5 h-2.5 rounded-full {{ $event['is_past'] ? 'bg-slate-300' : 'bg-[#3B5286]/30 group-hover:bg-[#3B5286]' }} transition-all border-2 border-white shadow-sm"></div>
                                            <span class="text-sm font-bold text-slate-700">{{ $event['time'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 flex items-center justify-center text-[#3B5286] text-[11px] font-bold shadow-sm">
                                                {{ substr(!empty($event['attendees']) ? $event['attendees'][0] : 'AD', 0, 1) }}
                                            </div>
                                            <span class="text-sm font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">
                                                {{ !empty($event['attendees']) ? implode(', ', array_slice($event['attendees'], 0, 1)) . (count($event['attendees']) > 1 ? ' +'.(count($event['attendees'])-1) : '') : 'Anggota Dewan' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <span class="text-sm font-bold text-slate-800 group-hover:text-[#3B5286] transition-all line-clamp-1">
                                            {{ $event['title'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <div class="flex items-center gap-2 text-slate-500">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-sm font-medium">{{ $event['location'] ?: '—' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-slate-600">Tidak ada kegiatan hari ini</p>
                                            <p class="text-[12px] text-slate-400 mt-1">Jadwal kegiatan Anda akan muncul di sini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            {{-- Section 1: Jenis Kegiatan (White Card matching Mockup) --}}

        <div x-data="{ showJenis: true }" class="bg-white rounded-[1.5rem] shadow-[0_10px_30px_-5px_rgba(0,0,0,0.05)] border border-gray-100/50 p-5 md:p-6 mb-6 w-full mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-1 gap-4 cursor-pointer select-none group" @click="showJenis = !showJenis">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-2xl font-bold text-[#3B5286] tracking-tight pl-1">Rekapitulasi Kegiatan</h2>
                        <svg class="w-5 h-5 text-[#3B5286] transition-all duration-300 transform opacity-0 group-hover:opacity-100" :class="showJenis ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-400 pl-1 mt-1.5 uppercase tracking-wider">Statistik Berdasarkan Kategori Data Jenis Kegiatan</p>
                </div>
                
                {{-- Compact Filters --}}
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-4 w-full md:w-auto justify-end" @click.stop>
                    <div class="relative w-full md:w-auto">
                        <select name="filter_type" onchange="this.form.submit()" 
                            class="appearance-none !bg-none w-full bg-white border border-[#3B5286]/20 rounded-full text-xs font-semibold text-[#3B5286] py-1.5 px-3 pr-8 cursor-pointer hover:border-[#3B5286]/40 transition-all focus:ring-2 focus:ring-[#3B5286]/30 shadow-sm min-w-[110px]">
                            <option value="all" {{ request('filter_type') == 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="Pelayanan Keprotokolan" {{ request('filter_type') == 'Pelayanan Keprotokolan' ? 'selected' : '' }}>Protokol</option>
                            <option value="Persidangan" {{ request('filter_type') == 'Persidangan' ? 'selected' : '' }}>Persidangan</option>
                            <option value="Kunjungan Kerja" {{ request('filter_type') == 'Kunjungan Kerja' ? 'selected' : '' }}>Kunjungan Kerja</option>
                            <option value="Administrasi Perjalanan Dinas" {{ request('filter_type') == 'Administrasi Perjalanan Dinas' ? 'selected' : '' }}>Perjalanan Dinas</option>
                        </select>
                        <div class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-[#3B5286]">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <div class="relative w-full md:w-auto">
                        <select name="sort_year" onchange="this.form.submit()"
                            class="appearance-none !bg-none w-full bg-[#3B5286]/5 border border-[#3B5286]/20 rounded-full text-xs font-semibold text-[#3B5286] py-1.5 px-3 pr-8 cursor-pointer hover:bg-[#3B5286]/10 transition-all focus:ring-2 focus:ring-[#3B5286]/30 shadow-sm min-w-[130px]">
                            <option value="desc" {{ request('sort_year', 'desc') == 'desc' ? 'selected' : '' }}>Highest to Lowest</option>
                            <option value="asc" {{ request('sort_year') == 'asc' ? 'selected' : '' }}>Lowest to Highest</option>
                            <optgroup label="Tahun">
                                @foreach(range(date('Y'), 2020) as $y)
                                    <option value="{{ $y }}" {{ request('sort_year') == $y ? 'selected' : '' }}>Year {{ $y }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <div class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-[#3B5286]">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                        </div>
                    </div>
                </form>
            </div>

            <div x-show="showJenis" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4">
                <div class="flex flex-col lg:flex-row gap-6 items-start mt-2">
                    {{-- Chart Area with Sticky Y-Axis --}}
                    <div class="flex-1 w-full h-[240px] flex relative overflow-hidden">
                        {{-- Sticky Y-Axis Label --}}
                        <div class="w-10 h-full flex-shrink-0 pb-1">
                            <canvas id="yAxisChart"></canvas>
                        </div>
                        
                        {{-- Scrollable Data Container --}}
                        <div class="flex-1 h-full overflow-x-auto custom-scrollbar pb-2">
                            <div class="h-full" style="min-width: {{ max(500, count($allJenis) * 45) }}px">
                                <canvas id="jenisKegiatanChart"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Legend Container --}}
                    <div class="w-full lg:w-[240px] flex-shrink-0 flex flex-col gap-3 lg:pl-5 border-l border-gray-50 h-[240px]">
                        {{-- Small Total Kegiatan Card --}}
                        <div class="bg-white rounded-[1rem] p-3 border border-[#3B5286]/20 flex flex-col items-start relative overflow-hidden shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05),_0_10px_20px_-2px_rgba(0,0,0,0.02)] w-full flex-shrink-0">
                            {{-- Blue triangular shape at top right --}}
                            <div class="absolute right-0 top-0 w-20 h-20 bg-[#3B5286]/10 rounded-bl-[4rem] -mr-4 -mt-4 z-0 pointer-events-none transform rotate-12"></div>
                            <p class="text-[9px] font-extrabold text-[#3B5286]/70 uppercase tracking-widest mb-0.5 z-10">Total Kegiatan</p>
                            <h3 class="text-3xl font-black text-[#3B5286] tracking-tight z-10 mt-0.5 leading-none">{{ number_format($totalKegiatan) }}</h3>
                        </div>

                        @php
                            $softPaletteStr = ['#B39DDB', '#A5D6A7', '#FFCC80', '#F48FB1', '#80CBC4', '#90CAF9', '#FFF59D', '#CE93D8'];
                        @endphp
                        <div class="flex-1 overflow-y-auto custom-scrollbar-y pr-2 mt-1 flex flex-col gap-3">
                            @foreach($allJenis as $index => $jenis)
                                <div class="flex items-center gap-4">
                                    <div class="w-3 h-3 rounded-full flex-shrink-0 shadow-[inset_-2px_-2px_4px_rgba(0,0,0,0.1),_2px_2px_4px_rgba(0,0,0,0.08)]" style="background-color: {{ $softPaletteStr[$index % count($softPaletteStr)] }}"></div>
                                    <span class="text-[11px] font-medium text-slate-600 leading-tight">{{ $jenis['nama'] ?? $jenis->nama }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Anggota Dewan --}}
        <div x-data="{ showDewan: true }" class="mb-10 w-full mx-auto">
            {{-- Wrapped Header Only --}}
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/50 p-4 md:p-5 flex items-center justify-between mb-6 cursor-pointer select-none group" @click="showDewan = !showDewan">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl md:text-2xl font-bold text-[#3B5286] tracking-tight">Rekapitulasi Kegiatan</h2>
                        <svg class="w-5 h-5 text-[#3B5286] transition-all duration-300 transform opacity-0 group-hover:opacity-100" :class="showDewan ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    <p class="text-[10px] md:text-[11px] font-semibold text-slate-400 mt-1 uppercase tracking-wider">Statistik Berdasarkan Kategori Data Anggota Dewan</p>
                </div>
            </div>

            <div x-show="showDewan" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="relative group">
                
                {{-- Side Navigation Buttons (Netflix Style) --}}
                {{-- Left Button --}}
                <div class="absolute left-0 top-0 bottom-6 w-20 z-20 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500 bg-gradient-to-r from-gray-50/40 to-transparent rounded-l-[2rem]"></div>
                <button @click="scrollDewan('left')" 
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-30 ml-2 w-10 h-10 rounded-full bg-white/90 shadow-[0_4px_15px_rgba(0,0,0,0.1)] border border-gray-100 flex items-center justify-center text-[#3B5286] opacity-0 group-hover:opacity-100 hover:scale-110 active:scale-95 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>

                {{-- Right Button --}}
                <div class="absolute right-0 top-0 bottom-6 w-20 z-20 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500 bg-gradient-to-l from-gray-50/40 to-transparent rounded-r-[2rem]"></div>
                <button @click="scrollDewan('right')" 
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-30 mr-2 w-10 h-10 rounded-full bg-white/90 shadow-[0_4px_15px_rgba(0,0,0,0.1)] border border-gray-100 flex items-center justify-center text-[#3B5286] opacity-0 group-hover:opacity-100 hover:scale-110 active:scale-95 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>

                <div x-ref="dewanContainer" class="flex overflow-x-auto gap-4 md:gap-6 pb-6 no-scrollbar cursor-grab active:cursor-grabbing px-2 pt-2">
                    @foreach($anggotaDewan as $index => $dewan)
                        <div x-data="{ 
                                active: false, 
                                loadingData: true,
                                totalCount: 0, 
                                tipeCount: 0,
                                init() {
                                    this.$watch('loading', (val) => {
                                        if (!val) this.startAnimation();
                                    });
                                    if (!this.loading) this.startAnimation();
                                },
                                startAnimation() {
                                    setTimeout(() => {
                                        this.active = true;
                                        setTimeout(() => {
                                            this.loadingData = false;
                                            this.animateNumbers();
                                            if (window.initDewanChart) window.initDewanChart({{ $index }});
                                        }, 600);
                                    }, {{ $index * 150 }});
                                },
                                animateNumbers() {
                                    const duration = 1500;
                                    const start = performance.now();
                                    const animate = (now) => {
                                        const elapsed = now - start;
                                        const progress = Math.min(elapsed / duration, 1);
                                        const easeProgress = 1 - Math.pow(1 - progress, 3);
                                        this.totalCount = Math.floor(easeProgress * {{ $dewan['total'] }});
                                        this.tipeCount = Math.floor(easeProgress * {{ $dewan['tipe_count'] }});
                                        if (progress < 1) requestAnimationFrame(animate);
                                    };
                                    requestAnimationFrame(animate);
                                }
                            }"
                            :class="active ? 'active' : ''"
                            class="reveal-card flex-shrink-0 w-[215px] md:w-[235px] bg-white rounded-[1.5rem] shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)] border border-gray-100/50 p-5 md:p-6 flex flex-col items-center group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#3B5286]/10 hover:border-[#3B5286]/30 cursor-pointer">
                            
                            <h3 class="text-[13px] md:text-[14px] leading-tight font-extrabold text-slate-700 mb-6 text-center line-clamp-2 px-1 group-hover:text-[#3B5286] transition-colors">{{ explode(',', $dewan['nama'])[0] }}</h3>
                            
                            <div class="relative w-32 h-32 flex-shrink-0 drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                                {{-- Background Spinner (Visible only during initial load) --}}
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none z-0">
                                    <template x-if="loadingData">
                                        <div class="flex flex-col items-center">
                                            <div class="w-8 h-8 border-4 border-[#3B5286]/10 border-t-[#3B5286] rounded-full animate-spin-slow"></div>
                                        </div>
                                    </template>
                                </div>
                                
                                {{-- Chart Canvas (Always on top) --}}
                                <canvas id="dewanChart-{{ $index }}" class="relative z-10"></canvas>
                            </div>

                            <div class="w-full flex gap-3 mt-5">
                                <div class="flex-1 bg-gray-50/50 rounded-[0.8rem] p-2.5 text-center border border-gray-100/60 group-hover:bg-[#3B5286]/5 group-hover:border-[#3B5286]/20 transition-colors">
                                    <p class="text-[14px] font-black text-[#3B5286] leading-none" x-text="totalCount">0</p>
                                    <p class="text-[7.5px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">Total</p>
                                </div>
                                <div class="flex-1 bg-gray-50/50 rounded-[0.8rem] p-2.5 text-center border border-gray-100/60 group-hover:bg-[#3B5286]/5 group-hover:border-[#3B5286]/20 transition-colors">
                                    <p class="text-[14px] font-black text-[#3B5286] leading-none" x-text="tipeCount">0</p>
                                    <p class="text-[7.5px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">Tipe</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Shadow Plugin for 3D effect on bars matching the Legend (drop shadow)
            const barShadowPlugin = {
                id: 'barShadowPlugin',
                beforeDraw: (chart) => {
                    const ctx = chart.ctx;
                    chart.data.datasets.forEach((dataset, i) => {
                        const meta = chart.getDatasetMeta(i);
                        meta.data.forEach((bar) => {
                            ctx.save();
                            ctx.fillStyle = 'rgba(0, 0, 0, 0.08)';
                            ctx.shadowColor = 'rgba(0, 0, 0, 0.08)';
                            ctx.shadowBlur = 4;
                            ctx.shadowOffsetX = 2;
                            ctx.shadowOffsetY = 2;
                            // Fill a rect slightly offset to create shadow
                            const {x, y, base, width} = bar.getProps(['x', 'y', 'base', 'width']);
                            const height = base - y;
                            if (height > 0) {
                                ctx.fillRect(x - width/2, y, width, height);
                            }
                            ctx.restore();
                        });
                    });
                }
            };

            // Plugin to show values on top of bars
            const valueLabelPlugin = {
                id: 'valueLabelPlugin',
                afterDatasetsDraw: (chart) => {
                    if (chart.canvas.id !== 'jenisKegiatanChart') return;
                    const ctx = chart.ctx;
                    chart.data.datasets.forEach((dataset, i) => {
                        const meta = chart.getDatasetMeta(i);
                        meta.data.forEach((bar, index) => {
                            const value = dataset.data[index];
                            if (value > 0) {
                                ctx.save();
                                ctx.fillStyle = '#64748b'; // slate-500
                                ctx.font = 'bold 10px sans-serif';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'bottom';
                                ctx.fillText(value, bar.x, bar.y - 5);
                                ctx.restore();
                            }
                        });
                    });
                }
            };
            
            Chart.register(barShadowPlugin, valueLabelPlugin);

            // 1. Jenis Kegiatan Chart (With Sticky Y-Axis)
            const barDataRaw = @json($allJenis);
            const softPaletteStr = ['#B39DDB', '#A5D6A7', '#FFCC80', '#F48FB1', '#80CBC4', '#90CAF9', '#FFF59D', '#CE93D8'];
            
            // Calculate Max for sync
            const maxVal = Math.max(...barDataRaw.map(item => item.total), 5);
            const suggestedMax = Math.ceil(maxVal * 1.2 / 2) * 2; // Nice even number

            // Chart configuration shared between both
            const commonScales = {
                y: {
                    beginAtZero: true,
                    suggestedMax: suggestedMax,
                    border: { display: false },
                    grid: { display: false },
                    ticks: {
                        maxTicksLimit: 5,
                        font: { size: 10, weight: '600' },
                        color: '#94a3b8',
                        padding: 6
                    }
                }
            };

            // a. Fixed Y-Axis Chart (No data, only axis)
            new Chart(document.getElementById('yAxisChart'), {
                type: 'bar',
                data: { labels: [''], datasets: [] },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: { padding: { top: 10, bottom: 2 } },
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    scales: {
                        x: { display: false },
                        y: {
                            ...commonScales.y,
                            grid: { display: false },
                            border: { display: false }
                        }
                    }
                }
            });

            // b. Main Scrollable Bar Chart
            new Chart(document.getElementById('jenisKegiatanChart'), {
                type: 'bar',
                data: {
                    labels: barDataRaw.map(item => item.nama),
                    datasets: [{
                        data: barDataRaw.map(item => item.total),
                        backgroundColor: barDataRaw.map((_, i) => softPaletteStr[i % softPaletteStr.length]),
                        borderColor: 'rgba(255, 255, 255, 0.65)',
                        borderWidth: { top: 2, right: 0, bottom: 0, left: 2 },
                        borderRadius: { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 },
                        borderSkipped: false,
                        barThickness: 32,
                        minBarLength: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: { padding: { top: 10, bottom: 2 } },
                    plugins: { 
                        legend: { display: false },
                        tooltip: { 
                            backgroundColor: '#ffffff',
                            titleColor: '#1e293b',
                            bodyColor: '#475569',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 14, weight: 'bold' },
                            displayColors: false,
                            boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1)'
                        }
                    },
                    scales: {
                        y: { 
                            ...commonScales.y,
                            ticks: { display: false }, // Hide labels on the scrolling part
                            grid: { 
                                display: true,
                                color: '#f1f5f9', 
                                borderDash: [4, 4],
                                drawTicks: false 
                            }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { display: false },
                            border: { display: false }
                        }
                    }
                }
            });

            // 2. Anggota Dewan Donut Charts (Refactored for staggered init)
            const dewanData = @json($anggotaDewan);
            
            window.initDewanChart = function(index) {
                const dewan = dewanData[index];
                const ctx = document.getElementById(`dewanChart-${index}`);
                if (!ctx || !dewan) return;

                // Match colors to the top legend labels
                const jenisColorsMap = {};
                barDataRaw.forEach((item, i) => {
                    jenisColorsMap[item.nama] = softPaletteStr[i % softPaletteStr.length];
                });
                
                const mappedColors = dewan.breakdown.map(b => jenisColorsMap[b.label] || '#94a3b8');

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: dewan.breakdown.map(b => b.label),
                        datasets: [{
                            data: dewan.breakdown.map(b => b.value),
                            backgroundColor: mappedColors,
                            borderWidth: 2, 
                            borderColor: '#ffffff', 
                            hoverOffset: 12,
                            spacing: 3,
                            cutout: '58%', 
                            borderRadius: { outerRadius: 2, innerRadius: 2 }
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 2000,
                            easing: 'easeOutQuart',
                            animateRotate: true,
                            animateScale: true
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: { 
                                backgroundColor: '#ffffff',
                                titleColor: '#1e293b',
                                bodyColor: '#475569',
                                borderColor: '#e2e8f0',
                                borderWidth: 1,
                                padding: 10,
                                cornerRadius: 6,
                                titleFont: { size: 9, weight: 'bold' },
                                bodyFont: { size: 10, weight: 'bold' },
                                displayColors: true,
                                boxWidth: 5,
                                boxHeight: 5,
                                usePointStyle: true,
                                boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
                                callbacks: {
                                    title: () => '', 
                                    label: function(context) {
                                        let label = context.label || '';
                                        const value = context.formattedValue;
                                        const words = label.split(' ');
                                        let lines = [];
                                        let currentLine = '';
                                        
                                        words.forEach(word => {
                                            if ((currentLine + word).length > 16) {
                                                if (currentLine) lines.push(currentLine.trim());
                                                currentLine = word + ' ';
                                            } else {
                                                currentLine += word + ' ';
                                            }
                                        });
                                        if (currentLine) lines.push(currentLine.trim());
                                        
                                        // Tambahkan value di baris terakhir atau baris baru jika baris terakhir kepanjangan
                                        let lastLine = lines[lines.length - 1];
                                        if ((lastLine + ': ' + value).length > 18) {
                                            lines.push(': ' + value);
                                        } else {
                                            lines[lines.length - 1] = lastLine + ': ' + value;
                                        }
                                        return lines;
                                    }
                                }
                            }
                        }
                    }
                });
            };
        });
    </script>
    @endpush
</x-app-layout>
