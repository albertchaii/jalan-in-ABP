<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="jalan.in — Laporkan dan perbaiki infrastruktur jalan kita bersama.">

        <title>{{ config('app.name', 'jalan.in') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased antigravity-bg min-h-screen flex flex-col">

        {{-- Header Logo --}}
        <header class="w-full py-6 sm:py-8 text-center relative z-10">
            <a href="/" class="inline-block">
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight italic" style="color: var(--maroon);">
                    jalan.in
                </h1>
            </a>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 flex items-start sm:items-center justify-center px-4 sm:px-6 lg:px-8 pb-8 relative z-10">
            <div class="w-full max-w-md sm:max-w-lg">
                {{ $slot }}
            </div>
        </main>

        {{-- Footer --}}
        <footer class="w-full py-5 sm:py-6 px-4 sm:px-8 relative z-10 border-t border-gray-200/40">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs sm:text-sm text-gray-400">
                    &copy; {{ date('Y') }} Civic Curatorial Infrastructure. All rights reserved.
                </p>
                <nav class="flex items-center gap-4 sm:gap-6">
                    <a href="#" class="text-xs sm:text-sm text-gray-400 hover:text-gray-600 transition-colors">Privacy Policy</a>
                    <a href="#" class="text-xs sm:text-sm text-gray-400 hover:text-gray-600 transition-colors">Terms of Service</a>
                    <a href="#" class="text-xs sm:text-sm text-gray-400 hover:text-gray-600 transition-colors">Accessibility</a>
                    <a href="#" class="text-xs sm:text-sm text-gray-400 hover:text-gray-600 transition-colors">Contact</a>
                </nav>
            </div>
        </footer>

    </body>
</html>
