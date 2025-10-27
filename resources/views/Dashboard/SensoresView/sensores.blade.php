<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sensores') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 bg-gray-50 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200 transition-colors duration-200">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h3 class="text-2xl font-semibold text-center sm:text-left">Listado de Sensores</h3>
        </div>

        @if($sensores->isEmpty())
            <div class="text-center text-gray-500 dark:text-gray-400 mt-10">
                No hay sensores registrados.
            </div>
        @else
            <div class="space-y-3">
                @foreach($sensores as $sensor)
                    <div class="rounded-xl px-4 py-4 border transition-colors duration-200
                        bg-white/70 dark:bg-gray-800 border-gray-300 dark:border-gray-700 hover:border-gray-500 dark:hover:border-gray-400">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h4 class="text-lg font-semibold">{{ $sensor->nombre }}</h4>
                                <p class="text-sm text-gray-500">Bus: {{ $sensor->bus }}</p>
                                <p class="text-sm text-gray-500">Dirección: {{ $sensor->address }}</p>
                                <p class="text-sm text-gray-500">Tasa de flujo: {{ $sensor->tasa_flujo ?? '—' }}</p>
                                <p class="text-sm text-gray-500">Modo de salida: {{ $sensor->modo_salida ?? '—' }}</p>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($sensor->estado === 'activo')
                                    <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                        Activo
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                        Inactivo
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
