<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Help Desk') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|ibm-plex-mono:500&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-800 antialiased bg-canvas">

    <!-- Nav -->
    <header class="border-b border-slate-100 bg-white">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-brand-500 text-white font-semibold text-sm">HD</span>
                <span class="font-semibold text-brand-700">Help Desk</span>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center bg-brand-500 text-white px-4 py-2 rounded-lg hover:bg-brand-600 transition text-sm font-medium shadow-sm">
                            Sign up
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="max-w-6xl mx-auto px-6 pt-20 pb-16 text-center">
        <span class="inline-block text-xs font-mono uppercase tracking-wide text-brand-600 bg-brand-50 px-3 py-1 rounded-full mb-6">HD-2026</span>
        <h1 class="text-4xl sm:text-5xl font-semibold text-slate-900 leading-tight max-w-2xl mx-auto">
            Every issue tracked.<br>Every ticket resolved.
        </h1>
        <p class="mt-5 text-slate-500 max-w-xl mx-auto text-base">
            Log a ticket, route it to the right team, and follow it from open to closed — all in one simple place.
        </p>
        <div class="mt-8 flex items-center justify-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center bg-brand-500 text-white px-6 py-3 rounded-lg hover:bg-brand-600 transition text-sm font-medium shadow-sm">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center bg-brand-500 text-white px-6 py-3 rounded-lg hover:bg-brand-600 transition text-sm font-medium shadow-sm">
                    Log in
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center bg-white border border-slate-200 text-slate-700 px-6 py-3 rounded-lg hover:bg-slate-50 transition text-sm font-medium">
                        Create an account
                    </a>
                @endif
            @endauth
        </div>
    </section>

    <!-- Feature strip -->
    <section class="max-w-6xl mx-auto px-6 pb-24">
        <div class="grid sm:grid-cols-3 gap-6">
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="w-9 h-9 rounded-lg bg-brand-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800 mb-1">Submit a ticket</h3>
                <p class="text-sm text-slate-500">Describe your issue and attach a document or screenshot in seconds.</p>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="w-9 h-9 rounded-lg bg-accent-400/15 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-accent-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 3c0-1.657-3.134-3-7-3s-7 1.343-7 3" />
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800 mb-1">Routed to the right team</h3>
                <p class="text-sm text-slate-500">Your Project Manager reviews and assigns it to Backend or Frontend.</p>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="w-9 h-9 rounded-lg bg-brand-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800 mb-1">Tracked to resolution</h3>
                <p class="text-sm text-slate-500">Follow status and work history from submission to completion.</p>
            </div>
        </div>
    </section>

    <footer class="border-t border-slate-100 py-6">
        <p class="text-center text-xs text-slate-400 font-mono">HD-2026 Help Desk System</p>
    </footer>

</body>
</html>