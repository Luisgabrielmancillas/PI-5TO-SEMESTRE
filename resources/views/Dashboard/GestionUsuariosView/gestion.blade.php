<x-app-layout title="Gestión de Usuarios | HydroBox">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de usuarios') }}
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

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <section class="bg-white dark:bg-slate-800 shadow-sm sm:rounded-lg border border-slate-200/60 dark:border-slate-700/60">
                <div class="p-4 sm:p-6">

                    {{-- Top bar: filtro izquierda, búsqueda derecha --}}
                    <header class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-slate-600 dark:text-slate-300">Estado</label>
                            <select id="estadoSelect"
                                    class="rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                            <option value="all" selected>Todos</option>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Solicitado">Solicitado</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <input id="qInput" type="search" placeholder="Buscar por nombre"
                                   value="{{ $filters['q'] ?? '' }}"
                                   class="rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 w-72" />
                        </div>
                    </header>

                    {{-- Tabla con el MISMO estilo que history --}}
                    <div id="usersTableWrap" class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-900/40">
                                <tr>
                                    <th class="px-4 py-3 text-center text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">N°</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">Nombre</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">Correo</th>
                                    <th class="px-4 py-3 text-center text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">Estado</th>
                                    <th class="px-4 py-3 text-center text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase">Acciones</th>
                                </tr>
                            </thead>

                            <tbody id="tableBody" class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                @include('Dashboard.GestionUsuariosView.partials.tbody', ['items' => $items])
                            </tbody>
                        </table>
                    </div>

                    <div id="tablePagination" class="mt-4">
                        @include('Dashboard.GestionUsuariosView.partials.pagination', ['items' => $items])
                    </div>
                </div>
            </section>
        </div>
    </div>


</x-app-layout>
