@php
    $start = ($items->currentPage() - 1) * $items->perPage();
@endphp

@forelse($items as $i => $row)
<tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40">
    <td class="px-4 py-3 text-sm">{{ $start + $i + 1 }}</td>
    <td class="px-4 py-3 text-sm">{{ $row->hum_value ?? '—' }}</td>
    <td class="px-4 py-3 text-sm">{{ $row->tam_value ?? '—' }}</td>
    <td class="px-4 py-3 text-sm">{{ $row->ph_value ?? '—' }}</td>
    <td class="px-4 py-3 text-sm">{{ $row->ce_value ?? '—' }}</td>
    <td class="px-4 py-3 text-sm">{{ $row->tagua_value ?? '—' }}</td>
    <td class="px-4 py-3 text-sm">{{ $row->us_value ?? '—' }}</td>
    <td class="px-4 py-3 text-sm">{{ optional($row->fecha)->format('Y-m-d H:i') }}</td>
</tr>
@empty
<tr>
    <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
        No hay registros para este rango.
    </td>
</tr>
@endforelse
