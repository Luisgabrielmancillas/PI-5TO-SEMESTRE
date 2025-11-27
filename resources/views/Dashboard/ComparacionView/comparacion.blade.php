<x-app-layout title="Comparación | HydroBox">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Comparación') }}
            </h2>

            <span class="pill pill-emerald ml-auto inline-flex items-center">
                Hortaliza seleccionada:
                @if(!empty($selectedCrop))
                    <b class="ml-1">{{ $selectedCrop->nombre }}</b>
                @else
                    <b class="ml-1">No hay</b>
                @endif
            </span>
        </div>
    </x-slot>

    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            {{-- Selección actual --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-2xl font-bold">Última medición vs Rangos óptimos</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Se resaltan en <span class="text-amber-600 font-semibold">naranja</span> los valores cercanos a límite y en
                        <span class="text-rose-600 font-semibold">rojo</span> los fuera de rango.
                    </p>
                </div>
            </div>

            <div id="comparison-block">
                @include('Dashboard.ComparacionView._cards-block', [
                    'ranges' => $ranges,
                    'measurements' => $measurements,
                    'latest' => $latest,
                ])
            </div>

        </div>
    </section>

    {{-- Script para refrescar cada 10s --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const block = document.getElementById('comparison-block');
            if (!block) return;

            const url = @json(route('comparacion.block'));

            async function refreshComparison() {
                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) return;

                    const html = await response.text();
                    block.innerHTML = html;
                } catch (e) {
                    console.error('Error actualizando comparación:', e);
                }
            }

            setInterval(refreshComparison, 10000);
        });
    </script>
</x-app-layout>
