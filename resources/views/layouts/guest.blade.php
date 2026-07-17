<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden bg-gradient-to-br from-[#153e75] via-[#1e4e8c] to-[#0d2a54] animate-gradient-x">
            <!-- Decorative animated shapes -->
            <div class="absolute top-0 left-0 w-72 h-72 bg-white opacity-10 rounded-full mix-blend-overlay filter blur-xl animate-blob"></div>
            <div class="absolute top-0 right-0 w-72 h-72 bg-blue-300 opacity-10 rounded-full mix-blend-overlay filter blur-xl animate-blob" style="animation-delay: 2s"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-cyan-300 opacity-10 rounded-full mix-blend-overlay filter blur-xl animate-blob" style="animation-delay: 4s"></div>

            <div class="z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-white/10 dark:bg-gray-900/40 backdrop-blur-md shadow-2xl border border-white/20 sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
