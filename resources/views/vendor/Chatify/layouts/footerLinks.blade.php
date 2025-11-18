@php
  $admin       = \App\Models\User::where('role','admin')->first();
  $fallbackUrl = route('dashboard');        // fallback del botón Home
  $isAdmin     = auth()->check() && auth()->user()->role === 'admin';
  $isUser      = auth()->check() && auth()->user()->role === 'usuario';
  $myId        = auth()->id();
@endphp

{{-- Librerías base --}}
<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
<script src="{{ asset('js/chatify/utils.js') }}"></script>

{{-- IMPORTANTE: define window.chatify ANTES de code.js --}}
<script>
  window.chatify = {
    name: "HydroBox",
    sounds: {!! json_encode(config('chatify.sounds')) !!},
    allowedImages: {!! json_encode(config('chatify.attachments.allowed_images')) !!},
    allowedFiles: {!! json_encode(config('chatify.attachments.allowed_files')) !!},
    maxUploadSize: {{ Chatify::getMaxUploadSize() }},
    pusher: {!! json_encode(config('chatify.pusher')) !!},
    pusherAuthEndpoint: '{{ route("pusher.auth") }}'
  };
  window.chatify.allAllowedExtensions =
    (window.chatify.allowedImages || []).concat(window.chatify.allowedFiles || []);
</script>

{{-- Núcleo de Chatify (lee window.chatify) --}}
<script src="{{ asset('js/chatify/code.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const LIST = document.querySelector('.messenger-listView');
  const MESSENGER = document.querySelector('.messenger');
  if (!LIST || !MESSENGER) return;

  // ========== 2) Ajustes por ROL ==========
  const IS_ADMIN = @json($isAdmin);
  const IS_USER  = @json($isUser);
  const MY_ID    = @json($myId);
  const ADMIN_ID = @json(optional($admin)->id);

  /* ---- ROL: USUARIO ---- */
  if (IS_USER) {
    // (a) Quitar "Saved Messages" y títulos de secciones (Your Space / All Messages)
    // El item de Saved Messages contiene .saved-messages (en el avatar)
    const savedAvatar = LIST.querySelector('.saved-messages');
    if (savedAvatar) {
      const table = savedAvatar.closest('table.messenger-list-item');
      table?.remove();
    }
    // Quitar títulos de secciones si existen
    const possibleTitles = LIST.querySelectorAll('div, p, span');
    possibleTitles.forEach(el => {
      const txt = (el.textContent || '').trim();
      if (txt === 'Your Space' || txt === 'All Messages') el.remove();
    });

    // (b) Auto-abrir chat con ADMIN si está en la lista (sin "anclar")
    if (ADMIN_ID) {
      const tryOpenAdmin = () => {
        const item = LIST.querySelector(`table.messenger-list-item[data-contact="${ADMIN_ID}"]`);
        if (item) { item.querySelector('tr')?.click(); return true; }
        return false;
      };
      // intenta ya y al refrescar la lista
      if (!tryOpenAdmin()) setTimeout(tryOpenAdmin, 400);
      const mo = new MutationObserver(() => setTimeout(tryOpenAdmin, 0));
      mo.observe(LIST, {childList:true, subtree:true});
    }
  }

  /* ---- ROL: ADMIN ---- */
  if (IS_ADMIN) {
    // Redirige a /chatify/1 preservando el ?back=... si viene en la URL
    (function gotoUserOne() {
      const TARGET_URL = @json(route('user', 1));   // genera /chatify/1 con tu prefijo actual
      const target = new URL(TARGET_URL, location.origin);

      // Si ya estamos en /chatify/1 no hagas nada
      if (location.pathname === target.pathname) return;

      // Preserva ?back=... (o usa referrer del mismo origen si no viene)
      const params = new URLSearchParams(location.search);
      let back = params.get('back');

      if (!back && document.referrer) {
        try {
          const ref = new URL(document.referrer);
          if (ref.origin === location.origin && !ref.pathname.startsWith('/chatify')) {
            back = ref.href;
          }
        } catch (_) {}
      }

      if (back) target.searchParams.set('back', back);

      // Navega directo al usuario 1
      window.location.replace(target.toString());
    })();

    // No ejecutes más lógica de auto-open aquí; la vista /chatify/1 ya abre ese hilo.
  }

});
</script>

{{-- ===== Botón "Home": volver a la página de origen (back/referrer/history/fallback) ===== --}}
<script>
document.addEventListener('click', (e) => {
  const a = e.target.closest('.messenger a');
  if (!a) return;

  const href = a.getAttribute('href') || '';
  const isHome = a.classList.contains('home') || a.title === 'Home'
                 || href === '/' || href === location.origin + '/';
  if (!isHome) return;

  e.preventDefault();

  const ORIGIN = location.origin;
  const LS_KEY = 'hb_chat_back';
  const params = new URLSearchParams(location.search);
  let target = params.get('back');

  if (!target) {
    try { target = localStorage.getItem(LS_KEY) || ''; } catch (_) {}
  }
  if (!target) {
    if (history.length > 1) return history.back();
  }
  try {
    const url = new URL(target);
    if (url.origin !== ORIGIN) throw new Error('cross-origin');
    window.location.href = target;
  } catch (_) {
    window.location.href = @json($fallbackUrl);
  }
}, { passive:false });

// Guardar "back" (query o referrer del mismo origen)
document.addEventListener('DOMContentLoaded', () => {
  const ORIGIN = location.origin;
  const LS_KEY = 'hb_chat_back';
  const params = new URLSearchParams(location.search);
  let back = params.get('back');

  if (!back && document.referrer) {
    try {
      const ref = new URL(document.referrer);
      if (ref.origin === ORIGIN && !ref.pathname.startsWith('/chatify')) back = ref.href;
    } catch (_) {}
  }
  if (back) { try { localStorage.setItem(LS_KEY, back); } catch (_) {} }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  if (window.jQuery) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (token) $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' } });
  }
});
</script>
{{-- <script>
document.addEventListener('click', function(e){
  const trash = e.target.closest('.message-card .fa-trash, .message-card .ri-delete-bin-line');
  if (!trash) return;

  e.preventDefault();
  const card = trash.closest('.message-card');
  const id = card?.getAttribute('data-id');
  if (!id) { console.warn('No se encontró data-id en .message-card'); return; }

  $.post(@json(route('message.delete')), { id })
    .done(() => { $(card).fadeOut(150, ()=> $(card).remove()); })
    .fail((xhr) => { console.error('Fallo al borrar:', xhr.status, xhr.responseText); });
});
</script> --}}
<script>
// Borrar mensaje SIN modal de confirmación.
// Evita que Chatify abra su popup (stopImmediatePropagation) y envía 1 solo POST.
document.addEventListener('click', function (e) {
  const trash = e.target.closest('.message-card .fa-trash, .message-card .ri-delete-bin-line');
  if (!trash) return;

  e.preventDefault();
  e.stopImmediatePropagation(); // <-- bloquea el handler interno de Chatify

  const card = trash.closest('.message-card');
  if (!card) return;

  // Chatify suele guardar el id en data-id
  const id = card.getAttribute('data-id');
  if (!id) { console.warn('No se encontró data-id del mensaje'); return; }

  // Evitar dobles clicks
  if (card.dataset.deleting === '1') return;
  card.dataset.deleting = '1';

  $.post(@json(route('message.delete')), { id })
    .done(function (resp) {
      // Chatify code.js suele mirar resp.deleted; devolvemos {deleted:true} en el controller
      $(card).fadeOut(150, () => $(card).remove());
    })
    .fail(function (xhr) {
      console.error('Error al borrar', xhr.status, xhr.responseText);
      card.dataset.deleting = '0';
    });
}, { capture: true }); // capture ayuda a bloquear handlers que se subscriben en bubble
</script>
