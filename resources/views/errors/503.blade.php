<x-app-layout>
  <main class="min-h-[70vh] grid place-items-center px-4">
    <section class="text-center max-w-xl">
      <div class="inline-flex items-center justify-center rounded-2xl h-16 w-16 bg-indigo-100 text-indigo-600 dark:bg-indigo-400/10 dark:text-indigo-300 mb-5">
        <span class="text-2xl font-black">503</span>
      </div>
      <h1 class="text-3xl sm:text-4xl font-extrabold">Volvemos enseguida</h1>
      <p class="mt-3 text-slate-600 dark:text-slate-400">
        Estamos realizando tareas de mantenimiento.
      </p>

      <div class="mt-6">
        <a href="{{ route('landing') }}"
           class="rounded-xl px-4 py-2 text-sm ring-1 ring-slate-200 dark:ring-white/10 hover:bg-slate-50 dark:hover:bg-white/5 transition">
          Ir al inicio
        </a>
      </div>
    </section>
  </main>
</x-app-layout>