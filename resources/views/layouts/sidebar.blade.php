{{-- Sidebar Component --}}
{{-- Usage: @include('layouts.sidebar') inside app.blade.php --}}
{{-- Alpine.js: expects `sidebarOpen` from parent x-data --}}

<aside class="fixed inset-y-0 left-0 z-30 bg-[#3B5286] text-white flex flex-col transition-all duration-300 ease-in-out lg:translate-x-0 overflow-x-hidden w-72"
    :class="{ 'w-72': desktopSidebarOpen, 'w-20': !desktopSidebarOpen }"
    x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    {{-- Logo Area --}}
    <div @click="desktopSidebarOpen = !desktopSidebarOpen" class="flex items-center gap-3 px-6 h-[81px] border-b border-white/10 flex-shrink-0 cursor-pointer hover:bg-white/5 transition-colors relative group">
        <img src="{{ asset('images/logo-wantimpres.png') }}" alt="Logo" class="w-9 h-9 object-contain flex-shrink-0">
        <span class="text-lg font-bold tracking-wide transition-all duration-300 whitespace-nowrap overflow-hidden"
              :class="desktopSidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'">SIAP-PRO</span>
        
        <!-- Toggle Icon (Desktop Only) -->
        <div class="absolute right-4 opacity-0 group-hover:opacity-100 transition-opacity hidden lg:block text-white/50">
            <svg x-show="desktopSidebarOpen" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
            </svg>
            <svg x-cloak x-show="!desktopSidebarOpen" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
            </svg>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex-1 px-4 py-2 space-y-1 overflow-y-auto">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            :class="desktopSidebarOpen ? '' : 'justify-center'">
            <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
            </svg>
            <span x-show="desktopSidebarOpen" class="transition-all duration-300">Dashboard</span>
        </a>

        {{-- Pelayanan Keprotokolan --}}
        <a href="{{ route('pelayanan-keprotokolan') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('pelayanan-keprotokolan') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            :class="desktopSidebarOpen ? '' : 'justify-center'">
            <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
            </svg>
            <span x-show="desktopSidebarOpen" class="transition-all duration-300">Pelayanan Keprotokolan</span>
        </a>

        {{-- Persidangan --}}
        <a href="{{ route('persidangan') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('persidangan*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            :class="desktopSidebarOpen ? '' : 'justify-center'">
            <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
            </svg>
            <span x-show="desktopSidebarOpen" class="transition-all duration-300">Persidangan</span>
        </a>

        {{-- Kunjungan Kerja --}}
        <a href="{{ route('kunjungan-kerja') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('kunjungan-kerja') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            :class="desktopSidebarOpen ? '' : 'justify-center'">
            <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
            </svg>
            <span x-show="desktopSidebarOpen" class="transition-all duration-300">Kunjungan Kerja</span>
        </a>

        {{-- Administrasi Perjalanan Dinas --}}
        <a href="{{ route('administrasi-perjalanan-dinas') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('administrasi-perjalanan-dinas') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            :class="desktopSidebarOpen ? '' : 'justify-center'">
            <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
            </svg>
            <span x-show="desktopSidebarOpen" class="transition-all duration-300">Administrasi Perjalanan Dinas</span>
        </a>

        {{-- Penugasan Protokol — Hidden for `eksternal` --}}
        @if(Auth::user()->role !== 'eksternal')
            <a href="{{ route('penugasan-protokol') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                                      {{ request()->routeIs('penugasan-protokol') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                :class="desktopSidebarOpen ? '' : 'justify-center'">
                <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span x-show="desktopSidebarOpen" class="transition-all duration-300">Penugasan Protokol</span>
            </a>
        @endif
    </nav>

    {{-- User Profile (Bottom) with Dropdown --}}
    <div class="px-4 py-4 border-t border-white/10 relative" x-data="{ profileOpen: false }">

        {{-- Dropdown Popup (Opens Upward) --}}
        {{-- Dropdown Popup (Opens Upward) --}}
        <div x-cloak x-show="profileOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2" @click.outside="profileOpen = false"
            class="absolute bottom-full left-4 right-4 mb-2 bg-white rounded-lg shadow-xl py-2 z-50">
            {{-- Log Out --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                    <span>Log Out</span>
                    <svg class="w-5 h-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                </button>
            </form>

            {{-- History Log Activity — super_admin & admin only --}}
            @if(in_array(Auth::user()->role, ['super_admin', 'admin']))
                <a href="{{ route('history-log-activity') }}"
                    class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                    <span>History Log Activity</span>
                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </a>
            @endif

            {{-- Management User — super_admin only --}}
            @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('management-user.index') }}"
                    class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                    <span>Management User</span>
                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </a>
            @endif
        </div>

        {{-- Profile Button (Click to toggle) --}}
        <button @click="profileOpen = !profileOpen"
            class="w-full flex items-center gap-3 px-2 text-left focus:outline-none"
            :class="desktopSidebarOpen ? '' : 'justify-center'">
            {{-- Avatar --}}
            <div
                class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0" x-show="desktopSidebarOpen">
                <p class="text-sm font-medium truncate">{{ Auth::user()->nama_lengkap }}</p>
                <p class="text-xs text-white/60 truncate">{{ Auth::user()->role }}</p>
            </div>
            {{-- Chevron (rotates when open) --}}
            <svg x-show="desktopSidebarOpen" :class="profileOpen ? 'rotate-180' : ''"
                class="w-4 h-4 text-white/50 flex-shrink-0 transition-transform duration-200"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
            </svg>
        </button>
    </div>
</aside>

{{-- Mobile Overlay --}}
<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 lg:hidden">
</div>