<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIAP-PRO') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    {{-- Navy Header Bar — Full width --}}
    <div class="bg-[#3B5286] px-8 py-3.5">
        <h1 class="text-lg font-bold text-white">{{ $title ?? 'SIAP-PRO' }}</h1>
        <p class="text-xs text-white/70 mt-0.5">{{ $subtitle ?? '' }}</p>
    </div>

    {{-- Content --}}
    <main class="max-w-4xl mx-auto px-6 py-3">
        {{ $slot }}
    </main>
</body>

</html>