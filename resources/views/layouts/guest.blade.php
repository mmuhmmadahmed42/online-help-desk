<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|ibm-plex-mono:500&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased">
        <div class="min-h-screen flex">
            <!-- Left brand panel -->
            <div class="hidden lg:flex lg:w-2/5 bg-brand-500 flex-col justify-between p-12 text-white">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-white/15 font-semibold text-sm">HD</span>
                    <span class="font-semibold text-lg">Help Desk</span>
                </div>

                <div>
                    <p class="text-3xl font-semibold leading-snug text-white/95">
                        Every issue tracked.<br>Every ticket resolved.
                    </p>
                    <p class="mt-4 text-brand-100/80 text-sm max-w-sm">
                        Log tickets, route them to the right team, and follow every fix from open to closed — all in one place.
                    </p>
                </div>

                <p class="text-xs text-brand-100/60 font-mono">HD-2026</p>
            </div>

            <!-- Right form panel -->
            <div class="flex-1 flex flex-col justify-center items-center bg-canvas px-6 py-12">
                <div class="lg:hidden mb-8 flex items-center gap-2">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-brand-500 text-white font-semibold text-sm">HD</span>
                    <span class="font-semibold text-lg text-brand-700">Help Desk</span>
                </div>

                <div class="w-full sm:max-w-md px-6 py-8 bg-white border border-slate-200 overflow-hidden rounded-xl">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>