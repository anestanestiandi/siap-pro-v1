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
        {{-- Skeleton Loading --}}
        <div x-show="loading" x-cloak class="space-y-6">
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
        {{-- Section 1: Jenis Kegiatan (White Card matching Mockup) --}}
        <div class="bg-white rounded-[1.5rem] shadow-[0_10px_30px_-5px_rgba(0,0,0,0.05)] border border-gray-100/50 p-5 md:p-6 mb-6 w-full mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-1 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-[#3B5286] tracking-tight pl-1">Jenis Kegiatan</h2>
                    <p class="text-[11px] font-semibold text-slate-400 pl-1 mt-1.5 uppercase tracking-wider">Statistik Berdasarkan Kategori Data</p>
                </div>
                
                {{-- Compact Filters --}}
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-4 w-full md:w-auto justify-end">
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

        {{-- Section 2: Anggota Dewan --}}
        <div class="pb-8 w-full mx-auto">
            <div class="flex items-center justify-between mb-6 px-2">
                <div>
                    <h2 class="text-2xl font-bold text-[#3B5286] tracking-tight pl-1">Anggota Dewan</h2>
                    <p class="text-[11px] font-semibold text-slate-400 pl-1 mt-1.5 uppercase tracking-wider">Statistik Berdasarkan Anggota Dewan</p>
                </div>
                <div class="flex gap-2 mr-2">
                    <button @click="scrollDewan('left')" class="w-9 h-9 rounded-full border border-gray-200 bg-white shadow-sm flex items-center justify-center text-gray-400 hover:text-slate-800 hover:shadow hover:bg-gray-50 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="scrollDewan('right')" class="w-9 h-9 rounded-full border border-gray-200 bg-white shadow-sm flex items-center justify-center text-gray-400 hover:text-slate-800 hover:shadow hover:bg-gray-50 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <div x-ref="dewanContainer" class="flex overflow-x-auto gap-4 md:gap-6 pb-6 no-scrollbar cursor-grab active:cursor-grabbing px-2 pt-2">
                @foreach($anggotaDewan as $index => $dewan)
                    <div class="flex-shrink-0 w-[220px] md:w-[240px] bg-white rounded-[1.5rem] shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)] border border-gray-100/50 p-5 md:p-6 flex flex-col items-center group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#3B5286]/10 hover:border-[#3B5286]/30 cursor-pointer">
                        <h3 class="text-[13px] md:text-[14px] leading-tight font-extrabold text-slate-700 mb-6 text-center line-clamp-2 px-1 group-hover:text-[#3B5286] transition-colors">{{ explode(',', $dewan['nama'])[0] }}</h3>
                        
                        <div class="relative w-36 h-36 flex-shrink-0 drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                            <canvas id="dewanChart-{{ $index }}"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-3xl font-black text-[#3B5286] leading-none">{{ $dewan['total'] }}</span>
                            </div>
                        </div>

                        <div class="w-full flex gap-3 mt-5">
                            <div class="flex-1 bg-gray-50/80 rounded-[0.8rem] p-2.5 text-center border border-gray-100/60 group-hover:bg-[#3B5286]/5 group-hover:border-[#3B5286]/20 transition-colors">
                                <p class="text-[14px] font-black text-[#3B5286] leading-none">{{ $dewan['total'] }}</p>
                                <p class="text-[7.5px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">Total</p>
                            </div>
                            <div class="flex-1 bg-gray-50/80 rounded-[0.8rem] p-2.5 text-center border border-gray-100/60 group-hover:bg-[#3B5286]/5 group-hover:border-[#3B5286]/20 transition-colors">
                                <p class="text-[14px] font-black text-[#3B5286] leading-none">{{ $dewan['tipe_count'] }}</p>
                                <p class="text-[7.5px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">Tipe</p>
                            </div>
                        </div>
                    </div>
                @endforeach
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
            
            Chart.register(barShadowPlugin);

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

            // 2. Anggota Dewan Donut Charts
            const dewanData = @json($anggotaDewan);

            dewanData.forEach((dewan, index) => {
                const ctx = document.getElementById(`dewanChart-${index}`);
                if (!ctx) return;

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
                            borderWidth: 3, 
                            borderColor: '#ffffff', 
                            hoverOffset: 6,
                            cutout: '72%', 
                            borderRadius: { outerRadius: 2, innerRadius: 2 }
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: { 
                                backgroundColor: '#ffffff',
                                titleColor: '#1e293b',
                                bodyColor: '#475569',
                                borderColor: '#e2e8f0',
                                borderWidth: 1,
                                padding: 10,
                                cornerRadius: 8,
                                titleFont: { size: 10, weight: 'bold' },
                                bodyFont: { size: 11, weight: 'bold' },
                                displayColors: true,
                                boxWidth: 6,
                                boxHeight: 6,
                                usePointStyle: true,
                                boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
                                // Wrap long labels
                                callbacks: {
                                    title: () => '', // Hide the title to avoid double text
                                    label: function(context) {
                                        let label = context.label || '';
                                        const value = context.formattedValue;
                                        
                                        // Simple wrapping logic (split by space every ~20 chars)
                                        const words = label.split(' ');
                                        let lines = [];
                                        let currentLine = '';
                                        
                                        words.forEach(word => {
                                            if ((currentLine + word).length > 22) {
                                                lines.push(currentLine.trim());
                                                currentLine = word + ' ';
                                            } else {
                                                currentLine += word + ' ';
                                            }
                                        });
                                        lines.push(currentLine.trim());
                                        
                                        // Last line gets the value
                                        lines[lines.length - 1] += ': ' + value;
                                        return lines;
                                    }
                                }
                            }
                        },
                        animation: { animateRotate: true, animateScale: true }
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
