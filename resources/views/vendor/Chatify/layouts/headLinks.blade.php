<title>{{ config('chatify.name') }}</title>

{{-- Meta tags --}}
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="id" content="{{ $id }}">
<meta name="messenger-color" content="{{ $messengerColor }}">
<meta name="messenger-theme" content="{{ $dark_mode }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="url" content="{{ url('').'/'.config('chatify.routes.prefix') }}" data-user="{{ Auth::user()->id }}">

{{-- scripts --}}
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/chatify/font.awesome.min.js') }}"></script>
<script src="{{ asset('js/chatify/autosize.js') }}"></script>
{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
<script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>

{{-- styles --}}
<link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/>
<link href="{{ asset('css/chatify/style.css') }}" rel="stylesheet" />
<link href="{{ asset('css/chatify/'.$dark_mode.'.mode.css') }}" rel="stylesheet" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

<!-- Favicons PNG -->
<link rel="icon" href="{{ asset('favicon-32.png') }}" sizes="32x32" type="image/png">
<link rel="icon" href="{{ asset('favicon-16.png') }}" sizes="16x16" type="image/png">

<!-- iOS -->
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}" sizes="180x180">

<!-- (Opcional) Compatibilidad extra -->
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

{{-- Setting messenger primary color to css --}}
<style>
    :root {
        --primary-color: {{ $messengerColor }};
    }
</style>
{{-- === Admin fijado/“anclado” en la lista (estilos) === --}}
<style>
  /* Hace que el contacto del admin quede pegado arriba */
  .messenger-list-item.pinned-admin{
    position: sticky;
    top: 0;
    z-index: 9;
    background: #fff;
  }
  /* Si tu layout tiene modo oscuro, ajusta aquí */
  html.dark .messenger-list-item.pinned-admin{
    background: #0b0e14;
  }
  .badge-admin{
    margin-left:6px;
    font-size:11px;
    background:#eef2ff;
    color:#3f51b5;
    padding:2px 6px;
    border-radius:10px;
  }
</style>
<style>
  /* ========== THEME: que siga el dark/light global (html.dark) ========== */
  /* LIGHT (por defecto) */
  html:not(.dark) .messenger,
  html:not(.dark) .messenger .m-body,
  html:not(.dark) .messenger .messenger-listView,
  html:not(.dark) .messenger .messenger-messagingView,
  html:not(.dark) .messenger .messenger-infoView{
    background:#f8fafc; color:#111827;
  }
  html:not(.dark) .messenger .m-header,
  html:not(.dark) .messenger .messenger-listView .m-header,
  html:not(.dark) .messenger .messenger-messagingView .mc-top{
    background:#ffffff; color:#111827; border-bottom:1px solid #e5e7eb;
  }
  html:not(.dark) .messenger input[type="text"],
  html:not(.dark) .messenger textarea{
    background:#ffffff; color:#111827; border:1px solid #e5e7eb;
  }

  /* DARK (cuando tu app agrega .dark al <html>) */
  html.dark .messenger,
  html.dark .messenger .m-body,
  html.dark .messenger .messenger-listView,
  html.dark .messenger .messenger-messagingView,
  html.dark .messenger .messenger-infoView{
    background:#0b1220; color:#e5e7eb;
  }
  html.dark .messenger .m-header,
  html.dark .messenger .messenger-listView .m-header,
  html.dark .messenger .messenger-messagingView .mc-top{
    background:#111827; color:#e5e7eb; border-bottom:1px solid #1f2937;
  }
  html.dark .messenger input[type="text"],
  html.dark .messenger textarea{
    background:#111827; color:#e5e7eb; border:1px solid #1f2937;
  }

  /* Ocultar el switch de modo de Chatify si existiera */
  .messenger .mode, .messenger a[title="Switch Mode"], .messenger .dark-mode, .messenger .darkMode { display:none !important; }

  /* ========== BRANDING HYDROBOX (header izquierda) ========== */
  .hb-brand{
    display:flex; align-items:center; gap:.5rem; font-weight:600;
    letter-spacing:.2px;
  }
  .hb-brand img{ width:22px; height:22px; border-radius:6px; object-fit:contain; }
  .hb-brand .hb-title{ font-size:.95rem; }

  /* Ajustes generales ligeros */
  .badge-admin{ background:#eef2ff; color:#000 !important; padding:2px 6px; border-radius:10px; font-size:11px; }

  /* Layout: evita desbordes por si hay cards con overflow */
  .messenger{ position:relative; z-index:0; }

  /* ====== Cambios SOLO para role "usuario" (ocultar buscador y Saved Messages) ====== */
  @if(auth()->check() && auth()->user()->role === 'usuario')
    /* Ocultar buscador */
    .messenger-listView input[type="text"][placeholder="Search"]{ display:none !important; }
  @endif
</style>