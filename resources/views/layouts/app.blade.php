@props(['title' => 'Panel de Control'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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

        <script>
          (() => {
            const csrf   = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            const sel    = document.getElementById('estadoSelect');
            const q      = document.getElementById('qInput');
            const tbody  = document.getElementById('tableBody');
            const pag    = document.getElementById('tablePagination');
            const urlTbl = "{{ route('gestion.table') }}";

            let typing;
            window.currentUrl = null;

            function params() {
              const p = new URLSearchParams();
              if (sel) p.set('estado', sel.value);
              const term = (q?.value || '').trim();
              if (term !== '') p.set('q', term);
              return p;
            }

            async function refresh(url = null) {
              const pageScroll = window.scrollY;
              window.currentUrl = url ?? `${urlTbl}?${params().toString()}`;

              const res  = await fetch(window.currentUrl, { headers: { 'X-Requested-With':'XMLHttpRequest' }});
              const json = await res.json();

              if (tbody) tbody.innerHTML = json.tbody;
              if (pag)   pag.innerHTML   = json.pagination;

              window.scrollTo({ top: pageScroll, behavior: 'instant' });
            }
            window.refresh = refresh;

            // Filtros
            sel?.addEventListener('change', () => refresh());
            q?.addEventListener('input', () => {
              clearTimeout(typing);
              typing = setTimeout(() => refresh(), 300);
            });

            // Paginación AJAX
            pag?.addEventListener('click', (e) => {
              const a = e.target.closest('a[href]');
              if (!a) return;
              e.preventDefault();
              refresh(a.href);
            });

            // Confirmar (click en el botón) — SIN loader
            document.addEventListener('click', async (e) => {
              const btn  = e.target.closest('.js-confirm');
              if (!btn) return;

              const form = btn.closest('form.ajax-form');
              if (!form) return;

              e.preventDefault();              // no submit tradicional
              btn.disabled = true;             // evitar doble click

              const method = (form.querySelector('input[name=_method]')?.value || form.method || 'POST').toUpperCase();
              const fd     = new FormData(form);

              try {
                const res = await fetch(form.action, {
                  method,
                  headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                  },
                  body: fd,
                  credentials: 'same-origin',
                });

                if (res.ok) {
                  // Cerrar modal (Alpine) + fallback
                  const root    = form.closest('[x-data]');
                  const overlay = form.closest('.fixed.inset-0');
                  try { root?.dispatchEvent(new CustomEvent('modal:close', { bubbles:true })); } catch {}
                  if (overlay) overlay.style.display = 'none';

                  await refresh(window.currentUrl || null);

                  window.dispatchEvent(new CustomEvent('flash:show', {
                    detail:{ type:'success', text:'Operación realizada correctamente' }
                  }));
                } else {
                  window.dispatchEvent(new CustomEvent('flash:show', {
                    detail:{ type:'error', text:'No se pudo completar la acción.' }
                  }));
                }
              } catch {
                window.dispatchEvent(new CustomEvent('flash:show', {
                  detail:{ type:'error', text:'Error de red o del servidor.' }
                }));
              } finally {
                btn.disabled = false;
              }
            });
          })();
        </script>
        @auth
          @if (!request()->is('chatify*'))
            <a id="hb-fab"
              href="{{ route(config('chatify.routes.prefix')) }}?back={{ urlencode(request()->fullUrl()) }}"
              aria-label="Abrir chat con el administrador"
              title="Chat con administrador"
              class="hidden lg:inline-flex fixed bottom-4 right-4 lg:bottom-6 lg:right-6
                      z-50 items-center justify-center
                      w-12 h-12 lg:w-14 lg:h-14 rounded-full
                      bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                      text-white shadow-lg shadow-indigo-600/30
                      transition transform hover:-translate-y-0.5 active:scale-95
                      focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                      dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:active:bg-indigo-700
                      dark:focus:ring-indigo-400"
              style="right:1rem; bottom:1rem; left:auto;">
              <i class="ri-chat-3-fill text-xl lg:text-2xl leading-none"></i>

              <span id="chat-unread-badge"
                    class="hidden absolute -top-1 -right-1 min-w-[1.25rem] h-5 px-1
                          rounded-full bg-red-600 text-[10px] font-semibold
                          flex items-center justify-center"
                    aria-live="polite">0</span>

              <span class="sr-only">Abrir chat</span>
            </a>

            {{-- Si algún contenedor con transform afecta a "fixed", lo movemos al <body> --}}
            <script>
              document.addEventListener('DOMContentLoaded', () => {
                const fab = document.getElementById('hb-fab');
                if (fab && fab.parentElement !== document.body) {
                  document.body.appendChild(fab);
                }
              });
            </script>
          @endif
        @endauth
        
        @auth
          <script>
          document.addEventListener('DOMContentLoaded', () => {
            const badge = document.getElementById('chat-unread-badge');
            if (!badge) return;

            const url = "{{ route('chat.unread') }}";

            async function refreshUnread() {
              try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) return;
                const data = await res.json();
                const n = Number(data.unread || 0);
                if (n > 0) {
                  badge.textContent = n > 99 ? '99+' : String(n);
                  badge.classList.remove('hidden');
                } else {
                  badge.classList.add('hidden');
                }
              } catch (e) {
                // opcional: console.warn('unread badge error', e);
              }
            }

            // Primera carga + polling ligero
            refreshUnread();
            const interval = setInterval(refreshUnread, 20000); // cada 20s

            // Si la pestaña vuelve a estar visible, refresca
            document.addEventListener('visibilitychange', () => {
              if (!document.hidden) refreshUnread();
            });
          });
          </script>
        @endauth
        @stack('scripts')
        @include('components.alerts-component')
    </body>
</html>