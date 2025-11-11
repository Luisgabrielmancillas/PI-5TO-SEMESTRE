@php
  $admin = \App\Models\User::where('role','admin')->first();
@endphp
<script>
document.addEventListener('DOMContentLoaded', () => {
  const admin = {
    id: '{{ optional($admin)->id }}',
    name: @json(optional($admin)->name ?? 'Administrador'),
    avatar: @json(optional($admin)->avatar ?? ''),
  };
  if (!admin.id) return; // si aún no hay admin, no hacemos nada

  const LIST_SELECTOR = '.messenger-listView';
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
    const list = document.querySelector(LIST_SELECTOR);
    if (!list) return;

    let item = document.querySelector(ITEM_SELECTOR);
    if (!item){
      item = buildAdminItem();
      list.prepend(item);
    }else{
      item.classList.add('pinned-admin');
    }
  }

  // Intento inicial
  ensureAdminPinned();

  // Observa cambios en la lista (cuando Chatify la recarga) y vuelve a fijar
  const list = document.querySelector(LIST_SELECTOR);
  if (list){
    const mo = new MutationObserver(() => setTimeout(ensureAdminPinned, 0));
    mo.observe(list, {childList: true, subtree: true});
  }

  // Auto-abrir conversación con el admin al cargar
  setTimeout(() => {
    const pinned = document.querySelector(ITEM_SELECTOR);
    if (pinned){
      const row = pinned.querySelector('tr');
      if (row) row.click(); // Chatify escucha clicks delegados en <tr data-action="0">
    }
  }, 400);
});
</script>
<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
<script >
    // Gloabl Chatify variables from PHP to JS
    window.chatify = {
        name: "{{ config('chatify.name') }}",
        sounds: {!! json_encode(config('chatify.sounds')) !!},
        allowedImages: {!! json_encode(config('chatify.attachments.allowed_images')) !!},
        allowedFiles: {!! json_encode(config('chatify.attachments.allowed_files')) !!},
        maxUploadSize: {{ Chatify::getMaxUploadSize() }},
        pusher: {!! json_encode(config('chatify.pusher')) !!},
        pusherAuthEndpoint: '{{route("pusher.auth")}}'
    };
    window.chatify.allAllowedExtensions = chatify.allowedImages.concat(chatify.allowedFiles);
</script>
<script src="{{ asset('js/chatify/utils.js') }}"></script>
<script src="{{ asset('js/chatify/code.js') }}"></script>
