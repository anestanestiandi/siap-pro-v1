<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />



    <!-- Flatpickr CSS (Material Blue Theme) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    
    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <style>
        /* Tom Select Customization */
        .ts-control {
            border-radius: 0.5rem;
            border-color: #d1d5db;
            padding: 0.6rem 0.75rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem !important;
        }
        .ts-control:focus {
            box-shadow: 0 0 0 3px rgba(59, 82, 134, 0.5); /* #3B5286 with opacity */
            border-color: #3B5286;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%233B5286' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        }
        .ts-wrapper.multi .ts-control > div {
            background-color: #eff6ff; /* blue-50 */
            color: #3B5286;
            border: 1px solid #dbeafe;
            border-radius: 0.375rem;
        }
        .ts-dropdown {
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            margin-top: 4px;
        }
        .ts-dropdown .active {
            background-color: #eff6ff !important;
            color: #3B5286 !important;
        }

        /* Custom Flatpickr Overrides for Time Picker */
        .flatpickr-calendar {
            box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-radius: 0.75rem;
            border: 1px solid #f3f4f6;
        }

        .flatpickr-time {
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .flatpickr-time input:hover,
        .flatpickr-time .flatpickr-am-pm:hover,
        .flatpickr-time input:focus,
        .flatpickr-time .flatpickr-am-pm:focus {
            background: #eff6ff;
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange,
        .flatpickr-day.selected:focus,
        .flatpickr-day.startRange:focus,
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover,
        .flatpickr-day.selected.prevMonthDay,
        .flatpickr-day.startRange.prevMonthDay,
        .flatpickr-day.endRange.prevMonthDay,
        .flatpickr-day.selected.nextMonthDay,
        .flatpickr-day.startRange.nextMonthDay,
        .flatpickr-day.endRange.nextMonthDay {
            background: #3B5286;
            border-color: #3B5286;
        }

        .flatpickr-months .flatpickr-month {
            background: #3B5286;
            color: #fff;
            fill: #fff;
            height: 3.5rem;
            padding-top: 5px;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: #3B5286;
            color: #fff;
        }

        .flatpickr-current-month input.cur-year {
            color: #fff;
        }

        .flatpickr-weekdays {
            background: #3B5286;
        }

        span.flatpickr-weekday {
            background: #3B5286;
            color: #fff;
        }

        /* Time Picker Styling */
        .flatpickr-time .numInputWrapper span.arrowUp:after {
            border-bottom-color: #3B5286;
        }

        .flatpickr-time .numInputWrapper span.arrowDown:after {
            border-top-color: #3B5286;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false, desktopSidebarOpen: true, profileOpen: false }" class="min-h-screen bg-gray-100">

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!--Main Content Area (right of sidebar) -->
        <div class="flex flex-col min-h-screen transition-all duration-300 lg:ml-72"
            :class="{ 'lg:ml-72': desktopSidebarOpen, 'lg:ml-20': !desktopSidebarOpen }">
            <!-- Header -->
            @if(!isset($hideHeader) || !$hideHeader)
                @include('layouts.header')
            @endif

            <!-- Page Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>

    </div>
    
    {{-- Global Success Notification --}}
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-[2px]"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-8 relative overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-95 opacity-0 translate-y-4"
                 x-transition:enter-end="scale-100 opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="scale-100 opacity-100 translate-y-0"
                 x-transition:leave-end="scale-95 opacity-0 translate-y-4">
                
                {{-- Decorative Background --}}
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-50 rounded-full opacity-50"></div>
                
                <div class="text-center relative z-10">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Berhasil!</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8">
                        {{ session('success') }}
                    </p>
                    
                    <button type="button" @click="show = false"
                            class="w-full px-6 py-3 bg-[#3B5286] text-white rounded-xl font-semibold hover:bg-[#2E4068] transition shadow-lg shadow-blue-900/20">
                        Oke, Mengerti
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    
    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <!-- Global Script for Form Cancellation / Sidebar Navigation Warning -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebarLinks = document.querySelectorAll('aside a');
            
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const modalWrapper = document.querySelector('[x-data*="showCancelModal"]');
                    
                    if (modalWrapper && window.Alpine) {
                        try {
                            const data = window.Alpine.$data(modalWrapper);
                            
                            // Jika berada di halaman form (punya data showCancelModal)
                            if (data && typeof data.showCancelModal !== 'undefined') {
                                e.preventDefault(); // Stop default navigation
                                
                                // Cari tombol konfirmasi batal ('Ya, Batalkan')
                                const anchorTags = modalWrapper.querySelectorAll('a');
                                let cancelBtn = null;
                                for (let a of anchorTags) {
                                    if (a.innerText.trim() === 'Ya, Batalkan' || a.textContent.trim() === 'Ya, Batalkan') {
                                        cancelBtn = a;
                                        break;
                                    }
                                }
                                
                                if (cancelBtn) {
                                    // Ubah target destinasi ke link sidebar yang barusan di klik
                                    cancelBtn.href = this.href;
                                    
                                    // Tampilkan pop-up batal
                                    data.showCancelModal = true;
                                    return;
                                }
                            }
                        } catch (err) {
                            console.error('Error applying sidebar trigger:', err);
                        }
                    }
                });
            });
        });
    </script>

    {{-- TailWind Safelist for Dynamic Controller Classes --}}
    <div class="hidden" aria-hidden="true">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 from-red-50 to-red-100 from-green-50 to-green-100 from-purple-50 to-purple-100 from-amber-50 to-amber-100 from-indigo-50 to-indigo-100 from-teal-50 to-teal-100 from-pink-50 to-pink-100 opacity-0"></div>
        <div class="border-blue-100 border-red-100 border-green-100 border-purple-100 border-amber-100 border-indigo-100 border-teal-100 border-pink-100 opacity-0"></div>
        <div class="from-blue-500 to-blue-600 from-red-500 to-red-600 from-green-500 to-green-600 from-purple-500 to-purple-600 from-amber-500 to-amber-600 from-indigo-500 to-indigo-600 from-teal-500 to-teal-600 from-pink-500 to-pink-600 opacity-0"></div>
        <div class="shadow-blue-500/30 shadow-red-500/30 shadow-green-500/30 shadow-purple-500/30 shadow-amber-500/30 shadow-indigo-500/30 shadow-teal-500/30 shadow-pink-500/30 opacity-0"></div>
        <div class="text-blue-600 text-red-600 text-green-600 text-purple-600 text-amber-600 text-indigo-600 text-teal-600 text-pink-600 opacity-0"></div>
        <div class="bg-blue-100 bg-red-100 bg-green-100 bg-purple-100 bg-amber-100 bg-indigo-100 bg-teal-100 bg-pink-100 opacity-0"></div>
    </div>

    @stack('scripts')
</body>

</html>