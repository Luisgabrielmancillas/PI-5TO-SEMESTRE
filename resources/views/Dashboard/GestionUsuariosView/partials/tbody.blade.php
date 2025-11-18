@php $start = ($items->currentPage() - 1) * $items->perPage(); @endphp

@forelse($items as $i => $u)
<tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40">
    <td class="px-4 py-3 text-center">{{ $start + $i + 1 }}</td>
    <td class="px-4 py-3 text-left">{{ $u->name }}</td>
    <td class="px-4 py-3 text-left">{{ $u->email }}</td>

    <td class="px-4 py-3 text-center">
        <div class="items-center h-8 justify-center inline-flex px-3
            @switch($u->estado)
                @case('Activo') bg-green-200 rounded-full text-green-800 @break
                @case('Inactivo') bg-gray-300 rounded-full text-gray-800 @break
                @case('Solicitado') bg-blue-200 rounded-full text-blue-800 @break
                @default bg-slate-200 rounded-full text-slate-800
            @endswitch
        "><b>{{ $u->estado }}</b></div>
    </td>

    <td class="px-4 py-3 text-center">
        <div class="inline-flex items-center">
            @if ($u->estado === 'Solicitado')
                <x-button-accept :action="route('gestion.accept', $u)" :itemId="$u->id"
                                tituloModal="Confirmar solicitud"
                                :messageAlert="'¿Aceptar a <b>'.e($u->name).'</b>?'" />

                <x-button-trash  :action="route('gestion.reject', $u)" :itemId="$u->id"
                                tituloModal="Rechazar solicitud"
                                :messageAlert="'¿Rechazar y eliminar a <b>'.e($u->name).'</b>?'" />
            @elseif ($u->estado === 'Activo')
                <x-button-cancel :action="route('gestion.suspend', $u)" :itemId="$u->id"
                                tituloModal="Suspender usuario"
                                :messageAlert="'¿Suspender a <b>'.e($u->name).'</b>?'" />
            @elseif ($u->estado === 'Inactivo')
                <x-button-accept :action="route('gestion.activate', $u)" :itemId="$u->id"
                                tituloModal="Activar usuario"
                                :messageAlert="'¿Activar a <b>'.e($u->name).'</b>?'" />
            @endif
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
        No hay usuarios que coincidan con los filtros.
    </td>
</tr>
@endforelse