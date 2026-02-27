<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIAP-PRO') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Viewport-relative card sizing */
        .login-card {
            width: 80vw;
            max-width: 1100px;
            min-width: 320px;
            height: 85vh;
            max-height: 750px;
            min-height: 480px;
        }

        .login-logo {
            width: clamp(80px, 12vw, 150px);
            height: clamp(80px, 12vw, 150px);
        }

        .login-title {
            font-size: clamp(1.5rem, 2.5vw, 2.5rem);
        }

        .login-subtitle {
            font-size: clamp(0.75rem, 1.2vw, 1.125rem);
        }

        /* Stacked mode: let height be auto */
        @media (max-width: 1023px) {
            .login-card {
                width: 90vw;
                height: auto;
                max-height: none;
            }
        }

        @media (max-width: 639px) {
            .login-card {
                width: 100%;
                border-radius: 0;
            }
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-[#E5E7EB]">
    <!-- Full viewport, centered -->
    <div class="h-screen flex items-center justify-center p-4 lg:p-0">

        <!-- Card: viewport-relative sizing -->
        <div class="login-card bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col lg:flex-row">

            <!-- LEFT: Navy Branding (50%) -->
            <div
                class="w-full lg:w-1/2 bg-[#3B5286] flex flex-col items-center justify-center p-8 lg:p-12 text-center text-white">
                <div class="mb-5 lg:mb-8">
                    <img src="{{ asset('images/logo-wantimpres.png') }}" alt="Logo Wantimpres"
                        class="login-logo object-contain">
                </div>
                <h1 class="login-title font-bold tracking-wide mb-2 lg:mb-3">SIAP-PRO</h1>
                <p class="login-subtitle font-normal text-white/90 leading-relaxed">
                    Sistem Informasi Acara dan Persidangan Protokol
                </p>
            </div>

            <!-- RIGHT: Login Form (50%) -->
            <div class="w-full lg:w-1/2 bg-white flex items-center justify-center p-8 lg:p-12">
                <div class="w-full max-w-sm">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </div>
</body>

</html>