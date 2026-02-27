import re

with open(r"c:\Dokumen\ver.2 siappro\siappro\resources\views\layouts\sidebar.blade.php", "r", encoding="utf-8") as f:
    text = f.read()

# Replace aside tag
aside_old = """<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-[#3B5286] text-white flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0">"""
aside_new = """<aside :class="desktopSidebarOpen ? 'w-64' : 'w-20'"
    class="fixed inset-y-0 left-0 z-30 bg-[#3B5286] text-white flex flex-col transition-all duration-300 ease-in-out lg:translate-x-0 overflow-x-hidden"
    x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">"""
text = text.replace(aside_old, aside_new)

# Replace Logo area
logo_old = """    {{-- Logo Area --}}
    <div class="flex items-center gap-3 px-6 py-6">
        <img src="{{ asset('images/logo-wantimpres.png') }}" alt="Logo" class="w-9 h-9 object-contain">
        <span class="text-lg font-bold tracking-wide">SIAP-PRO</span>
    </div>"""
logo_new = """    {{-- Logo Area --}}
    <div @click="desktopSidebarOpen = !desktopSidebarOpen" class="flex items-center gap-3 px-6 h-[81px] border-b border-white/10 cursor-pointer hover:bg-white/5 transition-colors relative group" title="Toggle Sidebar (Expand/Collapse)">
        <img src="{{ asset('images/logo-wantimpres.png') }}" alt="Logo" class="w-9 h-9 object-contain flex-shrink-0">
        <span class="text-lg font-bold tracking-wide transition-all duration-300 whitespace-nowrap overflow-hidden"
              :class="desktopSidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0 hidden lg:block lg:opacity-0 lg:w-0'">SIAP-PRO</span>
        
        <!-- Hover Expand/Collapse Tooltip Icon (Desktop Only) -->
        <div class="absolute right-4 opacity-0 group-hover:opacity-100 transition-opacity hidden lg:block text-white/50">
            <svg x-show="desktopSidebarOpen" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
            </svg>
            <svg x-cloak x-show="!desktopSidebarOpen" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
            </svg>
        </div>
    </div>"""
text = text.replace(logo_old, logo_new)

def replacer(match):
    name = match.group(1).strip()
    return f"""</svg>
            <span :class="desktopSidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0 hidden lg:block lg:w-0 lg:opacity-0'" class="whitespace-nowrap transition-all duration-300 overflow-hidden">{name}</span>
        </a>"""

text = re.sub(r'</svg>\s+([A-Za-z\s]+?)\s+</a>', replacer, text)

# Also fix the profile button flex
prof_btn_old = """        <button @click="profileOpen = !profileOpen"
            class="w-full flex items-center gap-3 px-2 text-left focus:outline-none">
            {{-- Avatar --}}
            <div
                class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate">{{ Auth::user()->nama_lengkap }}</p>
                <p class="text-xs text-white/60 truncate">{{ Auth::user()->role }}</p>
            </div>
            {{-- Chevron (rotates when open) --}}
            <svg :class="profileOpen ? 'rotate-180' : ''"
                class="w-4 h-4 text-white/50 flex-shrink-0 transition-transform duration-200"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
            </svg>
        </button>"""
        
prof_btn_new = """        <button @click="profileOpen = !profileOpen"
            class="w-full flex items-center gap-3 px-2 text-left focus:outline-none" :class="desktopSidebarOpen ? '' : 'justify-center !px-0'">
            {{-- Avatar --}}
            <div
                class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold flex-shrink-0 transition-all duration-300"
                :class="desktopSidebarOpen ? '' : 'mx-auto'">
                {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0 transition-all duration-300 overflow-hidden"
                 :class="desktopSidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0 hidden lg:block lg:w-0 lg:opacity-0'">
                <p class="text-sm font-medium truncate">{{ Auth::user()->nama_lengkap }}</p>
                <p class="text-xs text-white/60 truncate">{{ Auth::user()->role }}</p>
            </div>
            {{-- Chevron (rotates when open) --}}
            <svg :class="[profileOpen ? 'rotate-180' : '', desktopSidebarOpen ? 'opacity-100 flex-shrink-0 w-4 h-4' : 'opacity-0 w-0 hidden lg:block lg:w-0 lg:opacity-0']"
                class="text-white/50 transition-all duration-200"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
            </svg>
        </button>"""
text = text.replace(prof_btn_old, prof_btn_new)

with open(r"c:\Dokumen\ver.2 siappro\siappro\resources\views\layouts\sidebar.blade.php", "w", encoding="utf-8") as f:
    f.write(text)

print("Updated sidebar")
