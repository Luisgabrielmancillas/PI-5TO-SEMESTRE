<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Actuadores') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 bg-gray-50 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200 transition-colors duration-200">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h3 class="text-2xl font-semibold text-center sm:text-left">Actuadores del Sistema</h3>
        </div>

        <div class="space-y-3">
            @foreach($actuadores as $act)
                <div class="rounded-xl px-4 py-4 border bg-white/70 dark:bg-gray-800 transition-colors duration-200 hover:border-teal-400">

                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center space-x-3">
                            <!-- Icono genérico -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-teal-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1 4v1m-1-9h.01M12 18a6 6 0 110-12 6 6 0 010 12z"/>
                            </svg>

                            <span class="text-lg font-semibold">{{ ucfirst($act->nombre) }}</span>
                        </div>

                        <button 
                            onclick="toggleInfo({{ $act->id_actuador }})"
                            id="btn-{{ $act->id_actuador }}"
                            class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition-transform">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                    </div>

                    <!-- Detalles ocultos -->
                    <div id="info-{{ $act->id_actuador }}" class="hidden mt-4 bg-gray-100 dark:bg-gray-700 rounded-lg p-3 text-sm">

                        <h5 class="font-semibold mb-2 text-center">Detalles del Actuador</h5>

                        <table class="w-full text-left border-collapse">
                            <tbody>
                                <tr><td class="py-1 font-semibold">Tipo:</td> <td>{{ $act->tipo }}</td></tr>
                                <tr><td class="py-1 font-semibold">Bus:</td> <td>{{ $act->bus ?? 'No aplica' }}</td></tr>
                                <tr><td class="py-1 font-semibold">Dirección:</td> <td>{{ $act->address ?? 'No aplica' }}</td></tr>
                                <tr><td class="py-1 font-semibold">Modo de activación:</td> <td>{{ $act->modo_activacion }}</td></tr>
                                <tr>
                                    <td class="py-1 font-semibold">Estado inicial:</td>
                                    <td class="{{ $act->estado_inicial ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $act->estado_inicial ? 'Encendido' : 'Apagado' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-1 font-semibold">Estado actual:</td>
                                    <td class="{{ $act->estado_actual ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $act->estado_actual ? 'Encendido' : 'Apagado' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

    <script>
        function toggleInfo(id) {
            const info = document.getElementById(`info-${id}`);
            const btn = document.getElementById(`btn-${id}`);
            info.classList.toggle('hidden');
            btn.classList.toggle('rotate-180');
        }
    </script>
</x-app-layout>
