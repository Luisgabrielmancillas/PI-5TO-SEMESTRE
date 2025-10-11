@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-end gap-2">

        {{-- Botón Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium
                         bg-slate-100 text-slate-400 dark:bg-slate-900 dark:text-slate-600
                         ring-1 ring-slate-200/70 dark:ring-slate-700/60 select-none cursor-not-allowed"
                  aria-disabled="true" aria-label="@lang('pagination.previous')">
                {{-- ícono --}}
                <svg class="h-3.5 w-3.5 me-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.78 15.53a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06l4-4a.75.75 0 111.06 1.06L9.31 10l3.47 3.47a.75.75 0 010 1.06z" clip-rule="evenodd"/>
                </svg>
                <span>Anterior</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium
                      bg-white text-slate-700 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700/70
                      ring-1 ring-slate-200/80 dark:ring-slate-700/70 transition"
               aria-label="@lang('pagination.previous')">
                <svg class="h-3.5 w-3.5 me-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.78 15.53a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06l4-4a.75.75 0 111.06 1.06L9.31 10l3.47 3.47a.75.75 0 010 1.06z" clip-rule="evenodd"/>
                </svg>
                <span>Anterior</span>
            </a>
        @endif

        {{-- Números / elipsis --}}
        <div class="hidden sm:flex items-center gap-1">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-medium
                                 text-slate-400 dark:text-slate-500 select-none">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold
                                         bg-gradient-to-tr from-emerald-400/20 to-sky-400/20
                                         text-slate-900 dark:text-slate-100
                                         ring-1 ring-emerald-300/40 dark:ring-emerald-300/30 select-none">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium
                                      bg-white text-slate-700 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700/70
                                      ring-1 ring-slate-200/80 dark:ring-slate-700/70 transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Botón Siguiente --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium
                      bg-white text-slate-700 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700/70
                      ring-1 ring-slate-200/80 dark:ring-slate-700/70 transition"
               aria-label="@lang('pagination.next')">
                <span>Siguiente</span>
                <svg class="h-3.5 w-3.5 ms-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.22 4.47a.75.75 0 011.06 0l4 4c.3.3.3.77 0 1.06l-4 4a.75.75 0 11-1.06-1.06L10.69 10 7.22 6.53a.75.75 0 010-1.06z" clip-rule="evenodd"/>
                </svg>
            </a>
        @else
            <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium
                         bg-slate-100 text-slate-400 dark:bg-slate-900 dark:text-slate-600
                         ring-1 ring-slate-200/70 dark:ring-slate-700/60 select-none cursor-not-allowed"
                  aria-disabled="true" aria-label="@lang('pagination.next')">
                <span>Siguiente</span>
                <svg class="h-3.5 w-3.5 ms-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.22 4.47a.75.75 0 011.06 0l4 4c.3.3.3.77 0 1.06l-4 4a.75.75 0 11-1.06-1.06L10.69 10 7.22 6.53a.75.75 0 010-1.06z" clip-rule="evenodd"/>
                </svg>
            </span>
        @endif
    </nav>
@endif
