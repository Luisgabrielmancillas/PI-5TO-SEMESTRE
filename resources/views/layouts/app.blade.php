@props(['title' => 'Panel de Control'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <title>{{ $title }}</title>

        <!-- Anti-FOUC: fija el tema antes de pintar -->
        <script>
        (function () {
          try {
            const t = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (t === 'dark' || (!t && prefersDark)) {
              document.documentElement.classList.add('dark');
            } else {
              document.documentElement.classList.remove('dark');
            }
          } catch (e) {}
        })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet"/>
        <!-- Favicons PNG -->
        <link rel="icon" href="{{ asset('favicon-32.png') }}" sizes="32x32" type="image/png">
        <link rel="icon" href="{{ asset('favicon-16.png') }}" sizes="16x16" type="image/png">

        <!-- iOS -->
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}" sizes="180x180">

        <!-- (Opcional) Compatibilidad extra -->
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            
            {{-- Flash (autocierre 5s con Alpine) --}}
            @if (session('error') || session('status'))
              <div x-data="{ show: true }"
                  x-init="setTimeout(() => show = false, 5000)"
                  x-show="show"
                  x-transition.opacity.duration.300ms
                  class="border-b border-slate-200 dark:border-white/5 bg-white/70 dark:bg-slate-900/60 backdrop-blur">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">
                  @if (session('error'))
                    <div class="relative rounded-lg px-4 py-2 text-sm font-medium
                                bg-rose-50 text-rose-700 ring-1 ring-rose-200
                                dark:bg-rose-900/20 dark:text-rose-200 dark:ring-rose-800">
                      {{ session('error') }}
                      <button type="button" @click="show=false"
                              class="absolute right-2 top-1/2 -translate-y-1/2 text-rose-500 hover:text-rose-700">&times;</button>
                    </div>
                  @endif
                  @if (session('status'))
                    <div class="relative mt-2 rounded-lg px-4 py-2 text-sm font-medium
                                bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200
                                dark:bg-emerald-900/20 dark:text-emerald-200 dark:ring-emerald-800">
                      {{ session('status') }}
                      <button type="button" @click="show=false"
                              class="absolute right-2 top-1/2 -translate-y-1/2 text-emerald-600 hover:text-emerald-800">&times;</button>
                    </div>
                  @endif
                </div>
              </div>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
