@php
  $admin       = \App\Models\User::where('role','admin')->first();
  $fallbackUrl = route('dashboard');
  $isAdmin     = auth()->check() && auth()->user()->role === 'admin';
@endphp

{{-- Libs base --}}
<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
<script src="{{ asset('js/chatify/utils.js') }}"></script>

{{-- IMPORTANTE: window.chatify ANTES de code.js --}}
<script>
  window.chatify = {
    name: "{{ config('chatify.name') }}",
    sounds: {!! json_encode(config('chatify.sounds')) !!},
    allowedImages: {!! json_encode(config('chatify.attachments.allowed_images')) !!},
    allowedFiles: {!! json_encode(config('chatify.attachments.allowed_files')) !!},
    maxUploadSize: {{ Chatify::getMaxUploadSize() }},
    pusher: {!! json_encode(config('chatify.pusher')) !!},
    pusherAuthEndpoint: '{{ route("pusher.auth") }}'
  };
  window.chatify.allAllowedExtensions = (window.chatify.allowedImages || []).concat(window.chatify.allowedFiles || []);
</script>

{{-- Core de Chatify (lee window.chatify) --}}
<script src="{{ asset('js/chatify/code.js') }}"></script>

{{-- ====== Auto-abrir: usuario -> ADMIN; admin -> primer contacto real ====== --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  try {
    const IS_ADMIN = @json($isAdmin);

    const LIST_SELECTOR = '.messenger-listView';
    const list = document.querySelector(LIST_SELECTOR);
    if (!list) return;

    // --- Usuario: fijar/abrir ADMIN ---
    if (!IS_ADMIN) {
      const admin = {
        id: '{{ optional($admin)->id }}',
        name: @json(optional($admin)->name ?? 'Administrador'),
        avatar: @json(optional($admin)->avatar ?? ''),
      };
      if (!admin.id) return;

      const ITEM_SELECTOR = `table.messenger-list-item[data-contact="${admin.id}"]`;

      function buildAdminItem(){
        const table = document.createElement('table');
        table.className = 'messenger-list-item pinned-admin';
        table.setAttribute('data-contact', admin.id);
        table.innerHTML = `
          <tr data-action="0">
            <td style="position: relative">
              <div class="avatar av-m" style="background-image:url('${admin.avatar}');"></div>
            </td>
            <td>
              <p data-id="${admin.id}" data-type="user">
                ${admin.name}
                <span class="badge-admin">Administrador</span>
              </p>
              <span><em>Inicia la conversación</em></span>
            </td>
          </tr>
        `;
        return table;
      }

      function ensureAdminPinned(){
        let item = document.querySelector(ITEM_SELECTOR);
        if (!item){
          item = buildAdminItem();
          list.prepend(item);
        }else{
          item.classList.add('pinned-admin');
        }
      }

      ensureAdminPinned();

      const mo = new MutationObserver(() => setTimeout(ensureAdminPinned, 0));
      mo.observe(list, {childList: true, subtree: true});

      // Auto-open admin
      setTimeout(() => {
        const pinned = document.querySelector(ITEM_SELECTOR);
        pinned?.querySelector('tr')?.click();
      }, 400);
      return;
    }

    // --- Admin: abrir PRIMER contacto real (excluye "Saved Messages") ---
    function firstRealContact(){
      const items = list.querySelectorAll('table.messenger-list-item[data-contact]');
      for (const el of items) {
        if (!el.querySelector('.saved-messages')) return el;
      }
      return null;
    }
    setTimeout(() => {
      const first = firstRealContact();
      first?.querySelector('tr')?.click();
    }, 400);

  } catch (e) {
    // console.warn('auto-open error', e);
  }
});
</script>

{{-- ====== Botón "Home": volver a la página de origen ====== --}}
<script>
document.addEventListener('click', (e) => {
  // Delegación: solo dentro del contenedor del chat
  const a = e.target.closest('.messenger a');
  if (!a) return;

  const ORIGIN = location.origin;
  const LS_KEY = 'hb_chat_back';

  // ¿Es el botón home? (clase, título o href a la raíz)
  const href = a.getAttribute('href') || '';
  const isHome = a.classList.contains('home') || a.title === 'Home' || href === '/' || href === ORIGIN + '/';
  if (!isHome) return;

  e.preventDefault();

  // 1) back de la query de esta página
  const params = new URLSearchParams(location.search);
  let target = params.get('back');

  // 2) localStorage de respaldo
  if (!target) {
    try { target = localStorage.getItem(LS_KEY) || ''; } catch (_) {}
  }

  // 3) history.back() si no hay target
  if (!target) {
    if (history.length > 1) return history.back();
  }

  // 4) validar mismo origen + fallback
  try {
    const url = new URL(target);
    if (url.origin !== ORIGIN) throw new Error('cross-origin');
    window.location.href = target;
  } catch (_) {
    window.location.href = @json($fallbackUrl);
  }
}, { passive:false });

// Guardar back si viene en la URL o por referrer (misma origen)
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