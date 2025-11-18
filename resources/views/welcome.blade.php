<!doctype html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HydroBox</title>
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

  {{-- Vite --}}
  @vite(['resources/css/app.css','resources/js/app.js'])

  {{-- Iconos --}}
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet"/>
  <!-- Favicons PNG -->
  <link rel="icon" href="{{ asset('favicon-32.png') }}" sizes="32x32" type="image/png">
  <link rel="icon" href="{{ asset('favicon-16.png') }}" sizes="16x16" type="image/png">

  <!-- iOS -->
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}" sizes="180x180">

  <!-- (Opcional) Compatibilidad extra -->
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
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
  <header class="sticky top-0 z-40 backdrop-blur supports-[backdrop-filter]:bg-white/70 dark:supports-[backdrop-filter]:bg-slate-950/60 border-b border-slate-200 dark:border-white/5 transition">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
      <a href="/" class="flex items-center gap-3">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 rounded-xl shadow-sm">
        <span class="text-sm sm:text-base font-semibold tracking-wide">HydroBox | Sistema Hidropónico</span>
      </a>

      <nav class="hidden md:flex items-center gap-6 text-sm text-slate-600 dark:text-slate-300">
        <a href="#funciona" data-scroll="#funciona" class="hover:text-slate-900 dark:hover:text-white">¿Cómo funciona?</a>
        <a href="#arquitectura" data-scroll="#arquitectura" class="hover:text-slate-900 dark:hover:text-white">Arquitectura</a>
        <a href="#rutinas" data-scroll="#rutinas" class="hover:text-slate-900 dark:hover:text-white">Rutinas</a>
        <a href="#monitoreo" data-scroll="#monitoreo" class="hover:text-slate-900 dark:hover:text-white">Hortaliza seleccionada</a>
        <a href="#guia" data-scroll="#guia" class="hover:text-slate-900 dark:hover:text-white">Guía de cultivo</a>
      </nav>

      <div class="flex items-center gap-2">
        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 rounded-xl border border-slate-200 dark:border-white/10 px-3 py-1.5 text-xs font-medium hover:bg-slate-50 dark:hover:bg-white/5 transition">
          <i class="ri-login-circle-line text-base opacity-90"></i> <span>Ingresar</span>
        </a>
        <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-gradient-to-tr from-emerald-400 to-sky-600 px-3 py-1.5 text-xs font-semibold shadow-lg shadow-emerald-900/20 hover:brightness-110 transition text-slate-900">
          <i class="ri-user-add-line text-base"></i> <span>Registrarse</span>
        </a>
        <x-theme-toggle />
      </div>
    </div>
  </header>
  {{-- Flash messages con autocierre --}}
  @if (session('error') || session('status'))
    <div x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition.opacity.duration.300ms
        class="border-b border-gray-200 dark:border-white/5 bg-white/70 dark:bg-gray-900/60 backdrop-blur">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">
        @if (session('error'))
          <div class="relative rounded-lg px-4 py-2 text-sm font-medium
                      bg-rose-50 text-rose-700 ring-1 ring-rose-200
                      dark:bg-rose-900/20 dark:text-rose-200 dark:ring-rose-800">
            {{ session('error') }}
            <button type="button" @click="show=false"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-rose-500 hover:text-rose-700">
              &times;
            </button>
          </div>
        @endif

        @if (session('status'))
          <div class="relative mt-2 rounded-lg px-4 py-2 text-sm font-medium
                      bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200
                      dark:bg-emerald-900/20 dark:text-emerald-200 dark:ring-emerald-800">
            {{ session('status') }}
            <button type="button" @click="show=false"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-emerald-600 hover:text-emerald-800">
              &times;
            </button>
          </div>
        @endif
      </div>
    </div>
  @endif

  <!-- Hero -->
  <section id="home" class="halo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-14 pb-12">
      <div class="grid lg:grid-cols-2 items-center gap-10">
        <div class="space-y-6">
          <h1 class="text-4xl sm:text-5xl font-black leading-tight">
            Hidroponía con <span class="bg-gradient-to-tr from-emerald-400 to-sky-400 bg-clip-text text-transparent">HydroBox</span>, control total y cosechas constantes.
          </h1>
          <p class="muted leading-relaxed">
            Depósito de <b>10 L</b> recirculado, <b>FloraMicro/FloraGrow/FloraBloom</b> dosificados 1 vez por semana, ventilación inteligente y luz programada <b>07:00–19:00</b>. Sensores de ambiente y agua para mantener pH, ORP, temperatura y nivel en rangos óptimos.
          </p>
          <div class="flex flex-wrap items-center gap-3">
            <a href="#funciona" class="rounded-xl bg-slate-50 px-4 py-2 text-sm ring-1 ring-slate-200 hover:bg-slate-100 transition dark:bg-white/10 dark:ring-white/15 dark:hover:bg-white/15">¿Cómo funciona?</a>
            <a href="#guia" class="rounded-xl bg-gradient-to-tr from-emerald-400 to-sky-600 px-4 py-2 text-sm font-semibold shadow-lg shadow-emerald-900/20 hover:brightness-110 transition text-slate-900">Guía de cultivo</a>
          </div>
        </div>

        <!-- Mockup de sistema -->
        <div class="relative">
          <div class="rounded-2xl card-soft shadow-2xl">
            <div class="rounded-xl card-soft">
              <div class="grid sm:grid-cols-2 gap-4">
                <div class="card">
                  <p class="kicker">Depósito</p>
                  <p class="mt-1 font-semibold">10L Recirculando</p>
                </div>
                <div class="card">
                  <p class="kicker">Luz</p>
                  <p class="mt-1 font-semibold">Periodo de tiempo automatizado</p>
                </div>
              </div>

              <div class="mt-5 card">
                <p class="kicker">Nutrientes</p>
                <p class="mt-1 font-semibold">FloraMicro | FloraGrow | FloraBloom</p>
                <div class="mt-3 muted text-sm">Dosificación semanal automatizada</div>
              </div>

              <div class="mt-5 card">
                <p class="text-sm muted">Sensores: Humedad - Temperatura ambiente - pH - ORP - Temperatura del agua - Nivel ultrasónico.</p>
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
        <p class="muted mt-2">Ciclo recirculado en grow tent: nutrientes, oxígeno y luz justo donde la planta los necesita.</p>
      </div>
      <div class="grid lg:grid-cols-4 gap-6">
        <article class="card">
          <div class="h-10 w-10 grid place-items-center rounded-lg bg-gradient-to-tr from-emerald-400 to-sky-400 text-slate-900 font-bold">1</div>
          <h3 class="mt-4 font-semibold">Depósito 10 L</h3>
          <p class="mt-2 text-sm muted">Mezcla agua + nutrientes. Mantén el tanque opaco para evitar algas.</p>
        </article>

        <article class="card">
          <div class="h-10 w-10 grid place-items-center rounded-lg bg-gradient-to-tr from-emerald-400 to-sky-400 text-slate-900 font-bold">2</div>
          <h3 class="mt-4 font-semibold">Recirculación</h3>
          <p class="mt-2 text-sm muted">Bomba impulsa la solución por canales.</p>
        </article>

        <article class="card">
          <div class="h-10 w-10 grid place-items-center rounded-lg bg-gradient-to-tr from-emerald-400 to-sky-400 text-slate-900 font-bold">3</div>
          <h3 class="mt-4 font-semibold">Ambiente controlado</h3>
          <p class="mt-2 text-sm muted">Luz 07–19 h, ventilador regula T° interna, circulación constante.</p>
        </article>

        <article class="card">
          <div class="h-10 w-10 grid place-items-center rounded-lg bg-gradient-to-tr from-emerald-400 to-sky-400 text-slate-900 font-bold">4</div>
          <h3 class="mt-4 font-semibold">Sensórica</h3>
          <p class="mt-2 text-sm muted">Humedad - Temperatura ambiente - pH - ORP - Temperatura del agua - Nivel ultrasónico.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- Arquitectura -->
  <section id="arquitectura" class="py-14 section-alt">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="grid lg:grid-cols-2 gap-8 items-stretch">
        <!-- Izquierda -->
        <div class="flex flex-col gap-6">
          <div class="mb-2">
            <h2 class="text-3xl font-bold">Arquitectura de HydroBox</h2>
            <p class="muted mt-2">Actuadores y sensores integrados para automatizar la estabilidad del cultivo.</p>
          </div>

          <!-- Actuadores -->
          <div class="card">
            <h3 class="font-semibold flex items-center gap-2">
              <i class="ri-toggle-line text-emerald-500 dark:text-emerald-300"></i> Actuadores
            </h3>
            <ul class="mt-4 space-y-3 text-sm muted">
              <li class="flex items-start gap-3"><i class="ri-water-flash-line text-emerald-500 dark:text-emerald-300 text-lg"></i>
                <span><b>Bomba de agua:</b> impulsa la solución desde el depósito hacia las plantas (recirculación).</span>
              </li>
              <li class="flex items-start gap-3"><i class="ri-windy-line text-emerald-500 dark:text-emerald-300 text-lg"></i>
                <span><b>Ventilador:</b> regula la temperatura interna del grow tent.</span>
              </li>
              <li class="flex items-start gap-3"><i class="ri-sun-line text-indigo-500 dark:text-indigo-300 text-lg"></i>
                <span><b>Lámpara:</b> encendido programado <b>07:00–19:00</b>.</span>
              </li>
              <li class="flex items-start gap-3"><i class="ri-contrast-drop-2-line text-sky-500 dark:text-sky-300 text-lg"></i>
                <span><b>Bombas dosificadoras (x3):</b> FloraMicro, FloraGrow y FloraBloom (dosificación semanal).</span>
              </li>
            </ul>
          </div>

          <!-- Sensores -->
          <div class="card">
            <h3 class="font-semibold flex items-center gap-2">
              <i class="ri-radar-line text-sky-500 dark:text-sky-300"></i> Sensores
            </h3>
            <ul class="mt-4 grid sm:grid-cols-2 gap-3 text-sm">
              <li class="rounded-lg p-3 ring-1 bg-white ring-slate-200 dark:bg-slate-800/40 dark:ring-white/10"><b>Humedad ambiente</b></li>
              <li class="rounded-lg p-3 ring-1 bg-white ring-slate-200 dark:bg-slate-800/40 dark:ring-white/10"><b>Temperatura ambiente</b></li>
              <li class="rounded-lg p-3 ring-1 bg-white ring-slate-200 dark:bg-slate-800/40 dark:ring-white/10"><b>pH del agua</b></li>
              <li class="rounded-lg p-3 ring-1 bg-white ring-slate-200 dark:bg-slate-800/40 dark:ring-white/10"><b>ORP del agua</b></li>
              <li class="rounded-lg p-3 ring-1 bg-white ring-slate-200 dark:bg-slate-800/40 dark:ring-white/10"><b>Temperatura del agua</b></li>
              <li class="rounded-lg p-3 ring-1 bg-white ring-slate-200 dark:bg-slate-800/40 dark:ring-white/10"><b>Nivel ultrasónico</b></li>
            </ul>
          </div>
        </div>

        <!-- Derecha -->
        <div class="w-full max-w-[520px] xl:max-w-[560px] ml-auto">
          <figure class="h-full rounded-2xl overflow-hidden shadow-2xl">
            <div class="h-[620px] min-h-[560px] bg-white dark:bg-transparent grid place-items-center">
              <img
                src="{{ asset('images/blender.jpg') }}"
                alt="Esquema 3D del sistema HydroBox"
                class="w-full h-full object-contain"
                width="960" height="1280" loading="eager" decoding="async"
              />
            </div>
          </figure>
          <figcaption class="mt-3 text-xs muted text-center">
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
        <p class="muted mt-2">¿Qué hace el sistema y qué debes revisar tú?</p>
      </div>

      <div class="grid lg:grid-cols-3 gap-6">
        <div class="card">
          <h3 class="font-semibold flex items-center gap-2"><i class="ri-sun-foggy-line text-indigo-500 dark:text-indigo-300"></i> Programa de luz</h3>
          <p class="mt-2 text-sm muted">Encendido <b>07:00–19:00</b>. Ajusta distancia LED para evitar quemaduras.</p>
          <div class="mt-4 text-xs text-slate-500 dark:text-slate-400">Sugerencia: 12–16 h totales según especie.</div>
        </div>
        <div class="card">
          <h3 class="font-semibold flex items-center gap-2"><i class="ri-flask-line text-emerald-500 dark:text-emerald-300"></i> Dosificación semanal</h3>
          <ul class="mt-2 space-y-2 text-sm muted">
            <li>• <b>FloraMicro</b></li>
            <li>• <b>FloraGrow</b></li>
            <li>• <b>FloraBloom</b></li>
          </ul>
          <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">Ajusta volúmenes según etapa (plántula, crecimiento, floración).</div>
        </div>
        <div class="card bg-gradient-to-b from-emerald-100 to-transparent dark:from-emerald-400/10">
          <h3 class="font-semibold flex items-center gap-2"><i class="ri-water-flash-line text-emerald-500 dark:text-emerald-300"></i> Depósito 10 L</h3>
          <p class="mt-2 text-sm muted">Mantén el nivel con agua baja en minerales. Recarga cuando el sensor ultrasónico marque bajo.</p>
        </div>
      </div>

      <!-- Checklist rápido -->
      <div class="mt-8 grid md:grid-cols-2 gap-6">
        <div class="card">
          <h4 class="font-semibold">Revisión diaria</h4>
          <ul class="mt-3 space-y-2 text-sm muted">
            <li>• Temperatura/humedad ambiente dentro de rango.</li>
            <li>• Flujo de recirculación continuo, sin obstrucciones.</li>
            <li>• Luz encendida en horario, ventilación funcionando.</li>
          </ul>
        </div>
        <div class="card">
          <h4 class="font-semibold">Revisión semanal</h4>
          <ul class="mt-3 space-y-2 text-sm muted">
            <li>• Dosificación de Micro/Grow/Bloom.</li>
            <li>• Medir y ajustar pH.</li>
            <li>• Verifica ORP.</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Monitoreo -->
  <section id="monitoreo" class="py-14 section-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="mb-4 text-center">
        <h2 class="text-3xl font-bold">Rangos óptimos</h2>
        <p class="muted mt-2">Indicadores con rangos de referencia para un crecimiento sano.</p>
      </div>

      {{-- Indicador de hortaliza seleccionada --}}
      <div class="flex justify-end mb-8">
        <span class="pill pill-emerald">
          Hortaliza seleccionada:
          @if(!empty($selectedCrop))
            <b>{{ $selectedCrop->nombre }}</b>
          @else
            <b>No hay</b>
          @endif
        </span>
      </div>

      {{-- Helpers locales para formatear (opcional) --}}
      @php
        $fmt = function($v) {
          // Quita ceros finales y punto sobrante (2 decimales como máximo)
          $s = number_format((float)$v, 2, '.', '');
          $s = rtrim(rtrim($s, '0'), '.');
          return $s;
        };
        $printRange = function($key) use ($ranges, $fmt) {
          if (isset($ranges[$key])) {
            $min = $fmt($ranges[$key]['min']);
            $max = $fmt($ranges[$key]['max']);
            $unit = $ranges[$key]['unit'] ?? '';
            return "{$min}–{$max}{$unit}";
          }
          return null; // sin datos
        };
      @endphp

      <!-- Fila 1 -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Temperatura ambiente --}}
        <article class="card">
          <header class="flex items-center justify-between">
            <p class="kicker">Temperatura ambiente</p>
            <i class="ri-temp-hot-line text-2xl text-emerald-500 dark:text-emerald-300"></i>
          </header>
          <div class="mt-3">
            @if($val = $printRange('temp_ambiente'))
              <p class="text-2xl font-extrabold">{{ $val }}</p>
            @else
              <p class="text-2xl font-extrabold">— °C</p>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sin datos</p>
            @endif
          </div>
        </article>

        {{-- Humedad ambiente --}}
        <article class="card">
          <header class="flex items-center justify-between">
            <p class="kicker">Humedad ambiente</p>
            <i class="ri-water-percent-line text-2xl text-sky-500 dark:text-sky-300"></i>
          </header>
          <div class="mt-3">
            @if($val = $printRange('humedad'))
              <p class="text-2xl font-extrabold">{{ $val }}</p>
            @else
              <p class="text-2xl font-extrabold">— %</p>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sin datos</p>
            @endif
          </div>
        </article>

        {{-- pH del agua --}}
        <article class="card">
          <header class="flex items-center justify-between">
            <p class="kicker">pH del agua</p>
            <i class="ri-test-tube-line text-2xl text-indigo-500 dark:text-indigo-300"></i>
          </header>
          <div class="mt-3">
            @if($val = $printRange('ph'))
              <p class="text-2xl font-extrabold">{{ $val }}</p>
            @else
              <p class="text-2xl font-extrabold">—</p>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sin datos</p>
            @endif
          </div>
        </article>
      </div>

      <!-- Fila 2 -->
      <div class="mt-6 max-w-3xl mx-auto grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- Temperatura del agua --}}
        <article class="card">
          <header class="flex items-center justify-between">
            <p class="kicker">Temperatura del agua</p>
            <i class="ri-temp-cold-line text-2xl text-emerald-500 dark:text-emerald-300"></i>
          </header>
          <div class="mt-3">
            @if($val = $printRange('temp_agua'))
              <p class="text-2xl font-extrabold">{{ $val }}</p>
            @else
              <p class="text-2xl font-extrabold">— °C</p>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sin datos</p>
            @endif
          </div>
        </article>

        {{-- ORP --}}
        <article class="card">
          <header class="flex items-center justify-between">
            <p class="kicker">ORP (potencial RedOx)</p>
            <i class="ri-bubble-chart-line text-2xl text-indigo-500 dark:text-indigo-300"></i>
          </header>
          <div class="mt-3">
            @if($val = $printRange('orp'))
              <p class="text-2xl font-extrabold">{{ $val }}</p>
            @else
              <p class="text-2xl font-extrabold">— mV</p>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sin datos</p>
            @endif
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
        <p class="muted mt-2">Rangos y tips por hortaliza.</p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Lechuga (id 1) --}}
        <article class="card">
          <header class="flex items-center justify-between">
            <h3 class="font-semibold">Lechuga</h3>
            <span class="pill pill-emerald">Fácil</span>
          </header>
          <ul class="mt-3 text-sm muted space-y-2">
            <li>• <b>Germinación:</b> 2–4 días en esponja.</li>
            <li>• <b>Trasplante:</b> raíz 1–2 cm.</li>
            <li>
              • <b>pH:</b> {{ $guide[1]['ph'] ?? '5.8–6.2' }}
              · <b>Temp agua:</b> {{ $guide[1]['temp_agua'] ?? '18–22°C' }}
            </li>
            <li>• <b>ORP:</b> {{ $guide[1]['orp'] ?? '250–400 mV' }}</li>
            <li>• <b>Luz:</b> 12–16 h · LED medio</li>
            <li>• <b>Nutrientes:</b> Micro/Grow/Bloom semanal (ligero)</li>
            <li>• <b>Cosecha:</b> 30–45 días</li>
          </ul>
        </article>

        {{-- Espinaca (id 2) --}}
        <article class="card">
          <header class="flex items-center justify-between">
            <h3 class="font-semibold">Espinaca</h3>
            <span class="pill pill-sky">Follaje</span>
          </header>
          <ul class="mt-3 text-sm muted space-y-2">
            <li>• <b>Germinación:</b> 5–8 días (remojo previo opcional).</li>
            <li>
              • <b>pH:</b> {{ $guide[2]['ph'] ?? '6.0–6.5' }}
              · <b>Temp agua:</b> {{ $guide[2]['temp_agua'] ?? '18–20°C' }}
            </li>
            <li>• <b>ORP:</b> {{ $guide[2]['orp'] ?? '250–400 mV' }}</li>
            <li>• <b>Luz:</b> 12–14 h · ambiente fresco</li>
            <li>• <b>Nutrientes:</b> Micro/Grow/Bloom semanal (moderado)</li>
            <li>• <b>Cosecha:</b> 35–50 días (corte por hojas)</li>
          </ul>
        </article>

        {{-- Acelga (id 3) --}}
        <article class="card">
          <header class="flex items-center justify-between">
            <h3 class="font-semibold">Acelga</h3>
            <span class="pill pill-amber">Rústica</span>
          </header>
          <ul class="mt-3 text-sm muted space-y-2">
            <li>• <b>Germinación:</b> 5–7 días.</li>
            <li>
              • <b>pH:</b> {{ $guide[3]['ph'] ?? '6.0–6.5' }}
              · <b>Temp agua:</b> {{ $guide[3]['temp_agua'] ?? '18–24°C' }}
            </li>
            <li>• <b>ORP:</b> {{ $guide[3]['orp'] ?? '300–400 mV' }}</li>
            <li>• <b>Luz:</b> 12–14 h · tolera ligeros calores</li>
            <li>• <b>Nutrientes:</b> Micro/Grow/Bloom semanal (moderado)</li>
            <li>• <b>Cosecha:</b> 45–60 días (cortes sucesivos)</li>
          </ul>
        </article>

        {{-- Rúcula / Arúgula (id 4) --}}
        <article class="card">
          <header class="flex items-center justify-between">
            <h3 class="font-semibold">Rúcula</h3>
            <span class="pill pill-indigo">Rápida</span>
          </header>
          <ul class="mt-3 text-sm muted space-y-2">
            <li>• <b>Germinación:</b> 2–3 días.</li>
            <li>
              • <b>pH:</b> {{ $guide[4]['ph'] ?? '5.8–6.2' }}
              · <b>Temp agua:</b> {{ $guide[4]['temp_agua'] ?? '18–22°C' }}
            </li>
            <li>• <b>ORP:</b> {{ $guide[4]['orp'] ?? '250–400 mV' }}</li>
            <li>• <b>Luz:</b> 12–16 h</li>
            <li>• <b>Nutrientes:</b> Micro/Grow/Bloom semanal (ligero)</li>
            <li>• <b>Cosecha:</b> 25–35 días</li>
          </ul>
        </article>

        {{-- Albahaca (id 5) --}}
        <article class="card">
          <header class="flex items-center justify-between">
            <h3 class="font-semibold">Albahaca</h3>
            <span class="pill pill-emerald">Aromática</span>
          </header>
          <ul class="mt-3 text-sm muted space-y-2">
            <li>• <b>Germinación:</b> 4–7 días.</li>
            <li>
              • <b>pH:</b> {{ $guide[5]['ph'] ?? '5.8–6.2' }}
              · <b>Temp agua:</b> {{ $guide[5]['temp_agua'] ?? '20–22°C' }}
            </li>
            <li>• <b>ORP:</b> {{ $guide[5]['orp'] ?? '250–400 mV' }}</li>
            <li>• <b>Luz:</b> 14–16 h · luz alta</li>
            <li>• <b>Nutrientes:</b> Micro/Grow/Bloom semanal (moderado)</li>
            <li>• <b>Cosecha:</b> 35–50 días (poda apical)</li>
          </ul>
        </article>

        {{-- Mostaza (id 6) --}}
        <article class="card">
          <header class="flex items-center justify-between">
            <h3 class="font-semibold">Mostaza</h3>
            <span class="pill pill-rose">Picante</span>
          </header>
          <ul class="mt-3 text-sm muted space-y-2">
            <li>• <b>Germinación:</b> 2–4 días.</li>
            <li>
              • <b>pH:</b> {{ $guide[6]['ph'] ?? '6.0–6.5' }}
              · <b>Temp agua:</b> {{ $guide[6]['temp_agua'] ?? '18–22°C' }}
            </li>
            <li>• <b>ORP:</b> {{ $guide[6]['orp'] ?? '250–400 mV' }}</li>
            <li>• <b>Luz:</b> 12–14 h</li>
            <li>• <b>Nutrientes:</b> Micro/Grow/Bloom semanal (moderado)</li>
            <li>• <b>Cosecha:</b> 25–40 días (cortes sucesivos)</li>
          </ul>
        </article>
      </div>

    </div>
  </section>



  <!-- Footer -->
  <footer class="border-t border-slate-200 dark:border-white/5 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-sm text-slate-600 dark:text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-3">
      <p>&copy; <span id="y"></span> HydroBox. Todos los derechos reservados.</p>
      <div class="flex items-center gap-4">
        <a href="{{ route('login') }}" class="hover:text-slate-900 dark:hover:text-slate-200">Ingresar</a>
        <a href="{{ route('register') }}" class="hover:text-slate-900 dark:hover:text-slate-200">Registrarse</a>
        <button type="button"
        id="scrollTopBtn"
        class="inline-flex items-center gap-1 hover:text-slate-900 dark:hover:text-slate-200">
          <i class="ri-arrow-up-line"></i> Arriba
        </button>
      </div>
    </div>
  </footer>
  @include('components.alerts-component')
</body>
</html>