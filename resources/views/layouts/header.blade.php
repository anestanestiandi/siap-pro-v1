{{-- Header Component --}}
{{-- Header: Always show full header bar with greeting on mobile & desktop --}}
<header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-4 flex-1 min-w-0">
        {{-- Hamburger Button (mobile only) --}}
        <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        {{-- Dynamic Content --}}
        <div class="flex-1 min-w-0">
            @if(request()->routeIs('dashboard'))
                <h1 class="text-xl font-bold text-gray-900">
                    Halo, {{ Auth::user()->nama_lengkap }} 🙌
                </h1>
                <p class="text-sm text-gray-500">Let's monitor today's workforce</p>
            @elseif(request()->routeIs('pelayanan-keprotokolan*'))
                <h1 class="text-xl font-bold text-gray-900">Pelayanan Keprotokolan</h1>
                <p class="text-sm text-gray-500">Kelola kegiatan dan penugasan protokol</p>
            @elseif(request()->routeIs('persidangan*'))
                <h1 class="text-xl font-bold text-gray-900">Persidangan</h1>
                <p class="text-sm text-gray-500">Kelola jadwal dan agenda persidangan</p>
            @elseif(request()->routeIs('kunjungan-kerja*'))
                <h1 class="text-xl font-bold text-gray-900">Kunjungan Kerja</h1>
                <p class="text-sm text-gray-500">Monitoring kegiatan kunjungan kerja</p>
            @elseif(request()->routeIs('administrasi-perjalanan-dinas*'))
                <h1 class="text-xl font-bold text-gray-900">Administrasi Perjalanan Dinas</h1>
                <p class="text-sm text-gray-500">Kelola administrasi perjalanan dinas</p>
            @elseif(request()->routeIs('penugasan-protokol*'))
                <h1 class="text-xl font-bold text-gray-900">Penugasan Protokol</h1>
                <p class="text-sm text-gray-500">Daftar penugasan tim protokol</p>
            @elseif(request()->routeIs('management-user*'))
                <h1 class="text-xl font-bold text-gray-900">Management User</h1>
                <p class="text-sm text-gray-500">Kelola data pengguna aplikasi</p>
            @elseif(request()->routeIs('history-log-activity*'))
                <h1 class="text-xl font-bold text-gray-900">History Log Activity</h1>
                <p class="text-sm text-gray-500">Monitor aktivitas dan log sistem</p>
            @else
                <h1 class="text-xl font-bold text-gray-900">{{ config('app.name') }}</h1>
            @endif
        </div>
    </div>

    {{-- Right Section: Actions & Search (Only on Index Pages) --}}
    <div class="flex items-center gap-3">
        @php
            $routeName = request()->route()->getName();
            $isEksternal = Auth::user()->role === 'eksternal';
        @endphp

        {{-- Pelayanan Keprotokolan --}}
        @if($routeName === 'pelayanan-keprotokolan')
            {{-- Search Bar --}}
            <div class="relative hidden sm:block">
                <form action="{{ route('pelayanan-keprotokolan') }}" method="GET">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kegiatan..."
                        class="w-64 pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-20 placeholder-gray-400 transition">
                    @if(request('date')) <input type="hidden" name="date" value="{{ request('date') }}"> @endif
                    @if(request('month')) <input type="hidden" name="month" value="{{ request('month') }}"> @endif
                    @if(request('year')) <input type="hidden" name="year" value="{{ request('year') }}"> @endif
                </form>
            </div>

            {{-- Add Button --}}
            @if(!$isEksternal)
                <a href="{{ route('pelayanan-keprotokolan.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#3B5286] hover:bg-[#2E4068] text-white text-sm font-semibold rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kegiatan
                </a>
            @endif
        @endif

        {{-- Persidangan --}}
        @if($routeName === 'persidangan')
            {{-- Search Bar --}}
            <div class="relative hidden sm:block">
                <form action="{{ route('persidangan') }}" method="GET">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kegiatan..."
                        class="w-64 pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-20 placeholder-gray-400 transition">
                    
                    @if(request('date')) <input type="hidden" name="date" value="{{ request('date') }}"> @endif
                    @if(request('month')) <input type="hidden" name="month" value="{{ request('month') }}"> @endif
                    @if(request('year')) <input type="hidden" name="year" value="{{ request('year') }}"> @endif
                </form>
            </div>

            {{-- Add Button --}}
            @if(!$isEksternal)
                <a href="{{ route('persidangan.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#3B5286] hover:bg-[#2E4068] text-white text-sm font-semibold rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kegiatan
                </a>
            @endif
        @endif

        {{-- Kunjungan Kerja --}}
        @if($routeName === 'kunjungan-kerja')
            {{-- Search Bar --}}
            <div class="relative hidden sm:block">
                <form action="{{ route('kunjungan-kerja') }}" method="GET">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kegiatan..."
                        class="w-64 pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-20 placeholder-gray-400 transition">
                    @if(request('date')) <input type="hidden" name="date" value="{{ request('date') }}"> @endif
                    @if(request('month')) <input type="hidden" name="month" value="{{ request('month') }}"> @endif
                    @if(request('year')) <input type="hidden" name="year" value="{{ request('year') }}"> @endif
                </form>
            </div>

            {{-- Add Button --}}
            @if(!$isEksternal)
                <a href="{{ route('kunjungan-kerja.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#3B5286] hover:bg-[#2E4068] text-white text-sm font-semibold rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kegiatan
                </a>
            @endif
        @endif

        {{-- Administrasi Perjalanan Dinas --}}
        @if($routeName === 'administrasi-perjalanan-dinas')
            {{-- Search Bar --}}
            <div class="relative hidden sm:block">
                <form action="{{ route('administrasi-perjalanan-dinas') }}" method="GET">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kegiatan..."
                        class="w-64 pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:border-[#3B5286] focus:ring focus:ring-[#3B5286] focus:ring-opacity-20 placeholder-gray-400 transition">
                    
                    @if(request('date')) <input type="hidden" name="date" value="{{ request('date') }}"> @endif
                    @if(request('month')) <input type="hidden" name="month" value="{{ request('month') }}"> @endif
                    @if(request('year')) <input type="hidden" name="year" value="{{ request('year') }}"> @endif
                </form>
            </div>

            {{-- Add Button --}}
            @if(!$isEksternal)
                <a href="{{ route('administrasi-perjalanan-dinas.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#3B5286] hover:bg-[#2E4068] text-white text-sm font-semibold rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kegiatan
                </a>
            @endif
        @endif

    </div>
</header>
