<x-app-layout title="Error 403 — Acceso denegado">
  <main class="min-h-[70vh] grid place-items-center px-4">
    <section class="text-center max-w-xl">
      <div class="inline-flex items-center justify-center rounded-2xl h-16 w-16 bg-amber-100 text-amber-600 dark:bg-amber-400/10 dark:text-amber-300 mb-5">
        <span class="text-2xl font-black">403</span>
      </div>
      <h1 class="text-3xl sm:text-4xl font-extrabold">Acceso denegado</h1>
      <p class="mt-3 text-slate-600 dark:text-slate-400">
        No tienes permisos para ver este recurso.
      </p>

      <div class="mt-6 flex items-center justify-center gap-3">
        <a href="{{ route('landing') }}"
           class="rounded-xl px-4 py-2 text-sm ring-1 ring-slate-200 dark:ring-white/10 hover:bg-slate-50 dark:hover:bg-white/5 transition">
          Volver al inicio
        </a>
        @guest
        <a href="{{ route('login') }}"
           class="rounded-xl px-4 py-2 text-sm font-semibold bg-emerald-500 text-white hover:brightness-110 transition">
          Iniciar sesión
        </a>
        @endguest
      </div>
    </section>
  </main>
</x-app-layout>