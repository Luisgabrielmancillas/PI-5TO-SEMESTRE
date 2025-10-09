@props(['class' => ''])

<button
  {{ $attributes->merge(['class' =>
    "rounded-lg px-3 py-2 border border-gray-300 dark:border-gray-700
     bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700
     transition-colors ".$class]) }}
  type="button"
  aria-label="Cambiar tema claro/oscuro"
  data-theme-toggle
>
  <span data-theme-sun class="hidden">🌞</span>
  <span data-theme-moon class="hidden">🌙</span>
</button>
