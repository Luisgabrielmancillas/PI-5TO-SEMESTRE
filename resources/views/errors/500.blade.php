<!doctype html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>500 — Error del servidor</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-white text-slate-900 dark:bg-slate-950 dark:text-slate-100">
  <main class="min-h-screen grid place-items-center px-4">
    <section class="text-center max-w-xl">
      <div class="inline-flex items-center justify-center rounded-2xl h-16 w-16 bg-rose-100 text-rose-600 dark:bg-rose-400/10 dark:text-rose-300 mb-5">
        <span class="text-2xl font-black">500</span>
      </div>
      <h1 class="text-3xl sm:text-4xl font-extrabold">Ups, algo salió mal</h1>
      <p class="mt-3 text-slate-600 dark:text-slate-400">
        Ocurrió un problema inesperado. Intenta nuevamente en unos momentos.
      </p>

      <div class="mt-6 flex items-center justify-center gap-3">
        <a href="{{ route('landing') }}"
           class="rounded-xl px-4 py-2 text-sm font-semibold bg-emerald-500 text-white hover:brightness-110 transition">
          Volver al inicio
        </a>
        @auth
        <a href="{{ route('dashboard') }}"
           class="rounded-xl px-4 py-2 text-sm ring-1 ring-slate-200 dark:ring-white/10 hover:bg-slate-50 dark:hover:bg-white/5 transition">
          Ir al dashboard
        </a>
        @endauth
      </div>
    </section>
  </main>
</body>
</html>