<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white dark:bg-gray-900 dark:text-gray-100 transition-colors duration-200">

        <!-- Toggle de tema visible en guest (arriba-derecha) -->
        <div class="fixed right-4 top-4 z-50">
            <x-theme-toggle />
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500 dark:text-gray-300" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg transition-colors duration-200">
                {{ $slot }}
            </div>
        </div>

        <!-- Conector: habilita todos los toggles con data-theme-toggle -->
        <script>
        (function () {
          function syncIcon(btn) {
            const isDark = document.documentElement.classList.contains('dark');
            btn.querySelector('[data-theme-sun]')?.classList.toggle('hidden', isDark);
            btn.querySelector('[data-theme-moon]')?.classList.toggle('hidden', !isDark);
          }

          function bindToggle(btn) {
            syncIcon(btn);
            btn.addEventListener('click', () => {
              const el = document.documentElement;
              const nowDark = el.classList.toggle('dark');
              localStorage.setItem('theme', nowDark ? 'dark' : 'light');
              document.querySelectorAll('[data-theme-toggle]').forEach(syncIcon);
            });
          }

          document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-theme-toggle]').forEach(bindToggle);
          });
        })();
        </script>
    </body>
</html>
