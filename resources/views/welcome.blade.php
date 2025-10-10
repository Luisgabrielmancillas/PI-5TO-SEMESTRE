<!doctype html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HydroBox — Grow Tent Hidropónico</title>
  <meta name="description" content="Grow tent hidropónico con depósito de 10 L recirculado, nutrientes FloraMicro/FloraGrow/FloraBloom semanales, ventilación, luz 07:00–19:00, y sensores de ambiente y agua.">

  {{-- Anti-FOUC: fija el tema antes de pintar --}}
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

  {{-- Vite (Tailwind + JS de tu proyecto) --}}
  @vite(['resources/css/app.css','resources/js/app.js'])

  {{-- Iconos --}}
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet"/>

  <style>
    /* Glow del hero + evitar overflow horizontal */
    .halo{position:relative; overflow:hidden;}
    .halo::before{
      content:"";
      position:absolute; inset:-10% -10% auto -10%;
      height:380px; filter: blur(80px);
      background: radial-gradient(60% 60% at 50% 40%,
        rgba(16,185,129,.25), rgba(99,102,241,.18) 45%, transparent 70%);
      pointer-events:none;
    }
  </style>
</head>
<body class="antialiased selection:bg-emerald-400/30 selection:text-emerald-100 overflow-x-hidden
             bg-white text-slate-900
             dark:bg-slate-950 dark:text-slate-100">

  <!-- Navbar -->
  <header class="sticky top-0 z-40 backdrop-blur supports-[backdrop-filter]:bg-white/70 dark:supports-[backdrop-filter]:bg-slate-950/60 border-b border-black/5 dark:border-white/5 transition">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
      <a href="#home" class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-emerald-400 to-sky-400 grid place-items-center text-slate-900 font-black">HB</div>
        <span class="text-sm sm:text-base font-semibold tracking-wide">HydroBox | Sistema Hidropónico</span>
      </a>

      <nav class="hidden md:flex items-center gap-6 text-sm text-slate-600 dark:text-slate-300">
        <a href="#funciona" class="hover:text-slate-900 dark:hover:text-white">¿Cómo funciona?</a>
        <a href="#arquitectura" class="hover:text-slate-900 dark:hover:text-white">Arquitectura</a>
        <a href="#rutinas" class="hover:text-slate-900 dark:hover:text-white">Rutinas</a>
        <a href="#monitoreo" class="hover:text-slate-900 dark:hover:text-white">Monitoreo</a>
        <a href="#guia" class="hover:text-slate-900 dark:hover:text-white">Guía de cultivo</a>
      </nav>

      <div class="flex items-center gap-2">
        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 rounded-xl border border-black/10 dark:border-white/10 px-3 py-1.5 text-xs font-medium hover:bg-black/5 dark:hover:bg-white/5 transition">
          <i class="ri-login-circle-line text-base opacity-90"></i> <span>Login</span>
        </a>
        <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-gradient-to-tr from-emerald-400 to-sky-600 px-3 py-1.5 text-xs font-semibold shadow-lg shadow-emerald-900/20 hover:brightness-110 transition text-slate-900">
          <i class="ri-user-add-line text-base"></i> <span>Registro</span>
        </a>

        {{-- Botón de tema (usa tu componente existente) --}}
        <x-theme-toggle />
      </div>
    </div>
  </header>

  <!-- Hero -->
  <section id="home" class="halo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-14 pb-12">
      <div class="grid lg:grid-cols-2 items-center gap-10">
        <div class="space-y-6">
          <h1 class="text-4xl sm:text-5xl font-black leading-tight">
            Hidroponía con <span class="bg-gradient-to-tr from-emerald-400 to-sky-400 bg-clip-text text-transparent">HydroBox</span>, control total y cosechas constantes.
          </h1>
          <p class="text-slate-600 dark:text-slate-300/90 leading-relaxed">
            Depósito de <b>10 L</b> recirculado, <b>FloraMicro/FloraGrow/FloraBloom</b> dosificados 1 vez por semana, ventilación inteligente y luz programada <b>07:00–19:00</b>. Sensores de ambiente y agua para mantener pH, ORP, temperatura y nivel en rangos óptimos.
          </p>
          <div class="flex flex-wrap items-center gap-3">
            <a href="#funciona" class="rounded-xl bg-black/5 dark:bg-white/10 px-4 py-2 text-sm ring-1 ring-black/10 dark:ring-white/15 hover:bg-black/10 dark:hover:bg-white/15 transition">¿Cómo funciona?</a>
            <a href="#guia" class="rounded-xl bg-gradient-to-tr from-emerald-400 to-sky-600 px-4 py-2 text-sm font-semibold shadow-lg shadow-emerald-900/20 hover:brightness-110 transition text-slate-900">Guía de cultivo</a>
          </div>
        </div>

        <!-- Mockup de sistema -->
        <div class="relative">
          <div class="rounded-2xl border border-black/10 dark:border-white/10 bg-gradient-to-b from-white to-white/60 dark:from-slate-900/60 dark:to-slate-900/20 p-5 shadow-2xl">
            <div class="rounded-xl bg-white/70 dark:bg-slate-900/60 ring-1 ring-black/10 dark:ring-white/10 p-5">
              <div class="grid sm:grid-cols-2 gap-4">
                <div class="rounded-lg bg-black/[0.04] dark:bg-slate-800/60 p-4 ring-1 ring-black/10 dark:ring-white/10">
                  <p class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Depósito</p>
                  <p class="mt-1 font-semibold">10L Recirculando</p>
                </div>
                <div class="rounded-lg bg-black/[0.04] dark:bg-slate-800/60 p-4 ring-1 ring-black/10 dark:ring-white/10">
                  <p class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Luz</p>
                  <p class="mt-1 font-semibold">Periodo de tiempo automatizado</p>
                </div>
              </div>

              <div class="mt-5 rounded-lg bg-black/[0.04] dark:bg-slate-800/60 p-4 ring-1 ring-black/10 dark:ring-white/10">
                <p class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Nutrientes</p>
                <p class="mt-1 font-semibold">FloraMicro | FloraGrow | FloraBloom</p>
                <div class="mt-3 text-slate-600 dark:text-slate-300/80 text-sm">Dosificación semanal automatizada</div>
              </div>

              <div class="mt-5 rounded-lg bg-black/[0.04] dark:bg-slate-800/60 p-4 ring-1 ring-black/10 dark:ring-white/10">
                <p class="text-sm text-slate-700 dark:text-slate-300">Sensores: Humedad - Temperatura ambiente - pH - ORP - Temperatura del agua - Nivel ultrasónico.</p>
              </div>
            </div>
          </div>
        </div> <!-- /Mockup -->
      </div>
    </div>
  </section>

<!-- Cómo funciona -->
  <section id="funciona" class="py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="mb-8">
        <h2 class="text-3xl font-bold">¿Cómo funciona el sistema?</h2>
        <p class="text-slate-300/80 mt-2">Ciclo recirculado en grow tent: nutrientes, oxígeno y luz justo donde la planta los necesita.</p>
      </div>
      <div class="grid lg:grid-cols-4 gap-6">
        <article class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10 hover:bg-white/[0.08] transition">
          <div class="h-10 w-10 grid place-items-center rounded-lg bg-gradient-to-tr from-emerald-400 to-sky-400 text-slate-900 font-bold">1</div>
          <h3 class="mt-4 font-semibold">Depósito 10 L</h3>
          <p class="mt-2 text-sm text-slate-300/80">Mezcla agua + nutrientes. Mantén el tanque opaco para evitar algas.</p>
        </article>
        <article class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10 hover:bg-white/[0.08] transition">
          <div class="h-10 w-10 grid place-items-center rounded-lg bg-gradient-to-tr from-emerald-400 to-sky-400 text-slate-900 font-bold">2</div>
          <h3 class="mt-4 font-semibold">Recirculación</h3>
          <p class="mt-2 text-sm text-slate-300/80">Bomba impulsa la solución por canales.</p>
        </article>
        <article class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10 hover:bg-white/[0.08] transition">
          <div class="h-10 w-10 grid place-items-center rounded-lg bg-gradient-to-tr from-emerald-400 to-sky-400 text-slate-900 font-bold">3</div>
          <h3 class="mt-4 font-semibold">Ambiente controlado</h3>
          <p class="mt-2 text-sm text-slate-300/80">Luz 07–19 h, ventilador regula T° interna, circulación constante.</p>
        </article>
        <article class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10 hover:bg-white/[0.08] transition">
          <div class="h-10 w-10 grid place-items-center rounded-lg bg-gradient-to-tr from-emerald-400 to-sky-400 text-slate-900 font-bold">4</div>
          <h3 class="mt-4 font-semibold">Sensórica</h3>
          <p class="mt-2 text-sm text-slate-300/80">Humedad - Temperatura ambiente - pH - ORP - Temperatura del agua - Nivel ultrasónico..</p>
        </article>
      </div>
    </div>
  </section>

  <!-- Arquitectura (imagen iguala altura de la columna izquierda) -->
  <section id="arquitectura" class="py-14 bg-gradient-to-b from-slate-950 to-slate-900 border-y border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="grid lg:grid-cols-2 gap-8 items-stretch">
        <!-- Izquierda -->
        <div class="flex flex-col gap-6">
          <div class="mb-2">
            <h2 class="text-3xl font-bold">Arquitectura de HydroBox</h2>
            <p class="text-slate-300/80 mt-2">Actuadores y sensores integrados para automatizar la estabilidad del cultivo.</p>
          </div>

          <!-- Actuadores -->
          <div class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10">
            <h3 class="font-semibold flex items-center gap-2">
              <i class="ri-toggle-line text-emerald-300"></i> Actuadores
            </h3>
            <ul class="mt-4 space-y-3 text-sm text-slate-300/90">
              <li class="flex items-start gap-3"><i class="ri-water-flash-line text-emerald-300 text-lg"></i>
                <span><b>Bomba de agua:</b> impulsa la solución desde el depósito hacia las plantas (recirculación).</span>
              </li>
              <li class="flex items-start gap-3"><i class="ri-windy-line text-emerald-300 text-lg"></i>
                <span><b>Ventilador:</b> regula la temperatura interna del grow tent.</span>
              </li>
              <li class="flex items-start gap-3"><i class="ri-sun-line text-indigo-300 text-lg"></i>
                <span><b>Lámpara:</b> encendido programado <b>07:00–19:00</b>.</span>
              </li>
              <li class="flex items-start gap-3"><i class="ri-contrast-drop-2-line text-sky-300 text-lg"></i>
                <span><b>Bombas dosificadoras (x3):</b> FloraMicro, FloraGrow y FloraBloom (dosificación semanal).</span>
              </li>
            </ul>
          </div>

          <!-- Sensores -->
          <div class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10">
            <h3 class="font-semibold flex items-center gap-2">
              <i class="ri-radar-line text-sky-300"></i> Sensores
            </h3>
            <ul class="mt-4 grid sm:grid-cols-2 gap-3 text-sm text-slate-300/90">
              <li class="rounded-lg bg-slate-800/40 p-3 ring-1 ring-white/10"><b>Humedad ambiente</b></li>
              <li class="rounded-lg bg-slate-800/40 p-3 ring-1 ring-white/10"><b>Temperatura ambiente</b></li>
              <li class="rounded-lg bg-slate-800/40 p-3 ring-1 ring-white/10"><b>pH del agua</b></li>
              <li class="rounded-lg bg-slate-800/40 p-3 ring-1 ring-white/10"><b>ORP del agua</b></li>
              <li class="rounded-lg bg-slate-800/40 p-3 ring-1 ring-white/10"><b>Temperatura del agua</b></li>
              <li class="rounded-lg bg-slate-800/40 p-3 ring-1 ring-white/10"><b>Nivel ultrasónico</b></li>
            </ul>
          </div>
        </div>

        <!-- Derecha -->
        <div class="w-full max-w-[520px] xl:max-w-[560px] ml-auto">
          <figure class="h-full rounded-2xl overflow-hidden shadow-2xl">
            <div class="h-[620px] min-h-[560px]">
              <img
                src="{{ asset('images/blender.jpg') }}"
                alt="Esquema 3D del sistema HydroBox"
                class="w-full h-full object-contain"
                width="960" height="1280" loading="eager" decoding="async"
              />
            </div>
          </figure>
          <figcaption class="mt-3 text-xs text-slate-400 text-center">
            Esquema ilustrativo del HydroBox.
          </figcaption>
        </div>
      </div>
    </div>
  </section>

  <!-- Rutinas de operación -->
  <section id="rutinas" class="py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="mb-8">
        <h2 class="text-3xl font-bold">Rutinas y automatización</h2>
        <p class="text-slate-300/80 mt-2">¿Qué hace el sistema y qué debes revisar tú?</p>
      </div>

      <div class="grid lg:grid-cols-3 gap-6">
        <div class="rounded-2xl p-6 ring-1 ring-white/10 bg-white/5">
          <h3 class="font-semibold flex items-center gap-2"><i class="ri-sun-foggy-line text-indigo-300"></i> Programa de luz</h3>
          <p class="mt-2 text-sm text-slate-300/90">Encendido <b>07:00–19:00</b>. Ajusta distancia LED para evitar quemaduras.</p>
          <div class="mt-4 text-xs text-slate-400">Sugerencia: 12–16 h totales según especie.</div>
        </div>
        <div class="rounded-2xl p-6 ring-1 ring-white/10 bg-white/5">
          <h3 class="font-semibold flex items-center gap-2"><i class="ri-flask-line text-emerald-300"></i> Dosificación semanal</h3>
          <ul class="mt-2 space-y-2 text-sm text-slate-300/90">
            <li>• <b>FloraMicro</b></li>
            <li>• <b>FloraGrow</b></li>
            <li>• <b>FloraBloom</b></li>
          </ul>
          <div class="mt-3 text-xs text-slate-400">Ajusta volúmenes según etapa (plántula, crecimiento, floración).</div>
        </div>
        <div class="rounded-2xl p-6 ring-1 ring-white/10 bg-gradient-to-b from-emerald-400/10 to-transparent">
          <h3 class="font-semibold flex items-center gap-2"><i class="ri-water-flash-line text-emerald-300"></i> Depósito 10 L</h3>
          <p class="mt-2 text-sm text-slate-300/90">Mantén el nivel con agua baja en minerales. Recarga cuando el sensor ultrasónico marque bajo.</p>
        </div>
      </div>

      <!-- Checklist rápido -->
      <div class="mt-8 grid md:grid-cols-2 gap-6">
        <div class="rounded-xl bg-white/5 p-5 ring-1 ring-white/10">
          <h4 class="font-semibold">Revisión diaria</h4>
          <ul class="mt-3 space-y-2 text-sm text-slate-300/90">
            <li>• Temperatura/humedad ambiente dentro de rango.</li>
            <li>• Flujo de recirculación continuo, sin obstrucciones.</li>
            <li>• Luz encendida en horario, ventilación funcionando.</li>
          </ul>
        </div>
        <div class="rounded-xl bg-white/5 p-5 ring-1 ring-white/10">
          <h4 class="font-semibold">Revisión semanal</h4>
          <ul class="mt-3 space-y-2 text-sm text-slate-300/90">
            <li>• Dosificación de Micro/Grow/Bloom.</li>
            <li>• Medir y ajustar pH.</li>
            <li>• Verifica ORP.</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Monitoreo (con indicador de hortaliza + 5 sensores, sin nivel de agua) -->
  <section id="monitoreo" class="py-14 bg-gradient-to-b from-slate-900 to-slate-950 border-y border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="mb-4 text-center">
        <h2 class="text-3xl font-bold">Rangos óptimos</h2>
        <p class="text-slate-300/80 mt-2">Indicadores con rangos de referencia para un crecimiento sano.</p>
      </div>

      <!-- Indicador: debajo del título y p, alineado a la derecha -->
      <div class="flex justify-end mb-8">
        <span class="text-[11px] sm:text-xs rounded-full bg-emerald-400/15 text-emerald-200 ring-1 ring-emerald-300/30 px-3 py-1">
          Hortaliza seleccionada: <b>No hay</b>
        </span>
      </div>

      <!-- Fila 1: 3 tarjetas -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Temperatura ambiente -->
        <article class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10">
          <header class="flex items-center justify-between">
            <p class="text-xs uppercase tracking-wider text-slate-400">Temperatura ambiente</p>
            <i class="ri-temp-hot-line text-2xl text-emerald-300"></i>
          </header>
          <div class="mt-3">
            <p class="text-2xl font-extrabold">— °C</p>
            <p class="text-xs text-slate-400 mt-1">Sin datos</p>
          </div>
        </article>

        <!-- Humedad ambiente -->
        <article class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10">
          <header class="flex items-center justify-between">
            <p class="text-xs uppercase tracking-wider text-slate-400">Humedad ambiente</p>
            <i class="ri-water-percent-line text-2xl text-sky-300"></i>
          </header>
          <div class="mt-3">
            <p class="text-2xl font-extrabold">— %</p>
            <p class="text-xs text-slate-400 mt-1">Sin datos</p>
          </div>
        </article>

        <!-- pH del agua -->
        <article class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10">
          <header class="flex items-center justify-between">
            <p class="text-xs uppercase tracking-wider text-slate-400">pH del agua</p>
            <i class="ri-test-tube-line text-2xl text-indigo-300"></i>
          </header>
          <div class="mt-3">
            <p class="text-2xl font-extrabold">—</p>
            <p class="text-xs text-slate-400 mt-1">Sin datos</p>
          </div>
        </article>
      </div>

      <!-- Fila 2: 2 tarjetas centradas -->
      <div class="mt-6 max-w-3xl mx-auto grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Temperatura del agua -->
        <article class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10">
          <header class="flex items-center justify-between">
            <p class="text-xs uppercase tracking-wider text-slate-400">Temperatura del agua</p>
            <i class="ri-temp-cold-line text-2xl text-emerald-300"></i>
          </header>
          <div class="mt-3">
            <p class="text-2xl font-extrabold">— °C</p>
            <p class="text-xs text-slate-400 mt-1">Sin datos</p>
          </div>
        </article>

        <!-- ORP -->
        <article class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10">
          <header class="flex items-center justify-between">
            <p class="text-xs uppercase tracking-wider text-slate-400">ORP (potencial RedOx)</p>
            <i class="ri-bubble-chart-line text-2xl text-indigo-300"></i>
          </header>
          <div class="mt-3">
            <p class="text-2xl font-extrabold">— mV</p>
            <p class="text-xs text-slate-400 mt-1">Sin datos</p>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- Guía de cultivo -->
  <section id="guia" class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold">Guía de cultivo por especie</h2>
        <p class="text-slate-300/80 mt-2">Pasos y rangos para Lechuga, Espinaca, Arúgula, Albahaca y Mostaza en HydroBox.</p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Lechuga -->
        <article class="rounded-2xl p-6 ring-1 ring-white/10 bg-white/5">
          <header class="flex items-center justify-between">
            <h3 class="font-semibold">Lechuga</h3>
            <span class="text-xs rounded-full bg-emerald-400/20 text-emerald-200 ring-1 ring-emerald-300/30 px-2 py-0.5">Fácil</span>
          </header>
          <ul class="mt-3 text-sm text-slate-300/90 space-y-2">
            <li>• <b>Germinación:</b> 2–4 días en esponja.</li>
            <li>• <b>Trasplante:</b> raíz 1–2 cm.</li>
            <li>• <b>pH:</b> 5.8–6.2 · <b>Temp agua:</b> 18–22°C</li>
            <li>• <b>ORP:</b> 250–400 mV</li>
            <li>• <b>Luz:</b> 12–16 h · LED medio</li>
            <li>• <b>Nutrientes:</b> Micro/Grow/Bloom semanal (ligero)</li>
            <li>• <b>Cosecha:</b> 30–45 días</li>
          </ul>
        </article>

        <!-- Espinaca -->
        <article class="rounded-2xl p-6 ring-1 ring-white/10 bg-white/5">
          <header class="flex items-center justify-between">
            <h3 class="font-semibold">Espinaca</h3>
            <span class="text-xs rounded-full bg-sky-400/20 text-sky-200 ring-1 ring-sky-300/30 px-2 py-0.5">Follaje</span>
          </header>
          <ul class="mt-3 text-sm text-slate-300/90 space-y-2">
            <li>• <b>Germinación:</b> 5–8 días (remojar semillas opcional).</li>
            <li>• <b>pH:</b> 6.0–6.5 · <b>Temp agua:</b> 18–20°C</li>
            <li>• <b>ORP:</b> 250–400 mV</li>
            <li>• <b>Luz:</b> 12–14 h · ambiente fresco</li>
            <li>• <b>Nutrientes:</b> Micro/Grow/Bloom semanal (moderado)</li>
            <li>• <b>Cosecha:</b> 35–50 días (corte por hojas)</li>
          </ul>
        </article>

        <!-- Arúgula -->
        <article class="rounded-2xl p-6 ring-1 ring-white/10 bg-white/5">
          <header class="flex items-center justify-between">
            <h3 class="font-semibold">Arúgula</h3>
            <span class="text-xs rounded-full bg-indigo-400/20 text-indigo-200 ring-1 ring-indigo-300/30 px-2 py-0.5">Rápida</span>
          </header>
          <ul class="mt-3 text-sm text-slate-300/90 space-y-2">
            <li>• <b>Germinación:</b> 2–3 días.</li>
            <li>• <b>pH:</b> 5.8–6.2 · <b>Temp agua:</b> 18–22°C</li>
            <li>• <b>ORP:</b> 250–400 mV</li>
            <li>• <b>Luz:</b> 12–16 h</li>
            <li>• <b>Nutrientes:</b> Micro/Grow/Bloom semanal (ligero)</li>
            <li>• <b>Cosecha:</b> 25–35 días</li>
          </ul>
        </article>

        <!-- Albahaca -->
        <article class="rounded-2xl p-6 ring-1 ring-white/10 bg-white/5">
          <header class="flex items-center justify-between">
            <h3 class="font-semibold">Albahaca</h3>
            <span class="text-xs rounded-full bg-emerald-400/20 text-emerald-200 ring-1 ring-emerald-300/30 px-2 py-0.5">Aromática</span>
          </header>
          <ul class="mt-3 text-sm text-slate-300/90 space-y-2">
            <li>• <b>Germinación:</b> 4–7 días.</li>
            <li>• <b>pH:</b> 5.8–6.2 · <b>Temp agua:</b> 20–22°C</li>
            <li>• <b>ORP:</b> 250–400 mV</li>
            <li>• <b>Luz:</b> 14–16 h · luz alta</li>
            <li>• <b>Nutrientes:</b> Micro/Grow/Bloom semanal (moderado)</li>
            <li>• <b>Cosecha:</b> 35–50 días (poda apical)</li>
          </ul>
        </article>

        <!-- Mostaza -->
        <article class="rounded-2xl p-6 ring-1 ring-white/10 bg-white/5">
          <header class="flex items-center justify-between">
            <h3 class="font-semibold">Mostaza (greens)</h3>
            <span class="text-xs rounded-full bg-sky-400/20 text-sky-200 ring-1 ring-sky-300/30 px-2 py-0.5">Picante</span>
          </header>
          <ul class="mt-3 text-sm text-slate-300/90 space-y-2">
            <li>• <b>Germinación:</b> 2–4 días.</li>
            <li>• <b>pH:</b> 6.0–6.5 · <b>Temp agua:</b> 18–22°C</li>
            <li>• <b>ORP:</b> 250–400 mV</li>
            <li>• <b>Luz:</b> 12–14 h</li>
            <li>• <b>Nutrientes:</b> Micro/Grow/Bloom semanal (moderado)</li>
            <li>• <b>Cosecha:</b> 25–40 días (cortes sucesivos)</li>
          </ul>
        </article>

        <!-- Pasos generales -->
        <article class="rounded-2xl p-6 ring-1 ring-white/10 bg-gradient-to-b from-emerald-400/10 to-transparent">
          <header class="flex items-center gap-2">
            <i class="ri-seedling-line text-emerald-300 text-xl"></i>
            <h3 class="font-semibold">Pasos generales</h3>
          </header>
          <ol class="mt-3 text-sm text-slate-300/90 space-y-2 list-decimal list-inside">
            <li>Hidrata esponjas, siembra 2–3 semillas por celda.</li>
            <li>Mantén humedad alta hasta emergencia; luego luz suave.</li>
            <li>Trasplanta al canal/cesta cuando raíz mida 1–2 cm.</li>
            <li>Mantén <b>ORP 250–400 mV</b> y pH en 5.8–6.5.</li>
            <li>Rellena con agua baja en minerales cuando el nivel baje.</li>
          </ol>
        </article>
      </div>

      <p class="mt-6 text-center text-xs text-slate-400">Ajusta la dosis según especie y etapa; dosifica Micro/Grow/Bloom 1 vez/semana.</p>
    </div>
  </section>

  <!-- Footer -->
  <footer class="border-t border-white/5 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-sm text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-3">
      <p>&copy; <span id="y"></span> HydroBox. Todos los derechos reservados.</p>
      <div class="flex items-center gap-4">
        <a href="{{ route('login') }}" class="hover:text-slate-200">Login</a>
        <a href="{{ route('register') }}" class="hover:text-slate-200">Registro</a>
        <a href="#home" class="inline-flex items-center gap-1 hover:text-slate-200"><i class="ri-arrow-up-line"></i> Arriba</a>
      </div>
    </div>
  </footer>

  
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
