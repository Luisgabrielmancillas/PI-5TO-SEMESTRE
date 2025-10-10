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
    <body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-200">

        <!-- Botón volver (arriba-izquierda) -->
        <div class="fixed left-4 top-4 z-50">
            <a href="/"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-300/60 dark:border-gray-700/60
                      bg-white/80 dark:bg-gray-800/80 backdrop-blur px-3 py-2 text-xs font-medium
                      text-gray-700 dark:text-gray-200 hover:bg-white/90 dark:hover:bg-gray-800 transition"
               aria-label="Volver al inicio">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="hidden sm:inline">Volver</span>
            </a>
        </div>

        <!-- Toggle de tema visible en guest (arriba-derecha) -->
        <div class="fixed right-4 top-4 z-50">
            <x-theme-toggle />
        </div>

        <div class="min-h-screen flex flex-col items-center justify-center px-4">
            <!-- Logo -->
            <a href="/" class="mt-10 mb-6 opacity-95 hover:opacity-100 transition">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-28 w-28 sm:h-40 sm:w-40">
            </a>

            <!-- Card -->
            <div class="mb-10 w-full sm:max-w-md bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm shadow-xl sm:rounded-2xl p-6 sm:p-8 border border-gray-200/60 dark:border-gray-700/60">
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

        <!-- Script: mostrar/ocultar contraseñas para cualquier botón con data-toggle-password -->
        <script>
        (function () {
          function hookPasswordToggles() {
            document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
              const selector = btn.getAttribute('data-toggle-password');
              const input = document.querySelector(selector);
              if (!input) return;

              const eye = btn.querySelector('[data-eye]');
              const eyeOff = btn.querySelector('[data-eye-off]');

              function sync() {
                const isPwd = input.type === 'password';
                eye?.classList.toggle('hidden', !isPwd);
                eyeOff?.classList.toggle('hidden', isPwd);
              }
              sync();

              btn.addEventListener('click', () => {
                input.type = input.type === 'password' ? 'text' : 'password';
                sync();
                input.focus({ preventScroll: true });
              });
            });
          }
          document.addEventListener('DOMContentLoaded', hookPasswordToggles);
        })();
        </script>
    </body>
</html>
