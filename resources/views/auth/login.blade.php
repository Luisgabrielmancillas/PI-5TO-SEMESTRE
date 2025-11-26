<x-guest-layout>
    <!-- Encabezado -->
    <header class="mb-6 text-center">
        <h1 class="text-2xl font-semibold tracking-tight">Inicia sesión</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Accede a tu cuenta para continuar</p>
    </header>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST"
          action="{{ route('login') }}"
          class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="mt-1">
                <x-text-input id="email"
                              class="block w-full"
                              type="email"
                              name="email"
                              :value="old('email')"
                              required
                              autofocus
                              autocomplete="username"
                              placeholder="tu@correo.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password con toggle -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative mt-1">
                <x-text-input
                    id="password"
                    class="block w-full pr-11"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••" />
                <button type="button"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                        aria-label="Mostrar u ocultar contraseña"
                        data-toggle-password="#password">
                    <!-- eye -->
                    <svg data-eye xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="2" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3" stroke-width="2"/>
                    </svg>
                    <!-- eye-off -->
                    <svg data-eye-off xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="2" d="M3 3l18 18M10.6 10.6a3 3 0 104.2 4.2M9.88 4.25A10.9 10.9 0 0112 4c6.5 0 10 7 10 7a18 18 0 01-4.23 5.18M6.1 6.1A18 18 0 002 11s3.5 7 10 7c1.9 0 3.65-.5 5.2-1.32"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember + Forgot -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center select-none">
                <input id="remember_me"
                       type="checkbox"
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm
                              focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300"
                   href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- CTA -->
        <x-primary-button class="w-full justify-center">
            {{ __('Continuar') }}
        </x-primary-button>

        <!-- Divider + link a registro -->
        <div class="text-center text-sm text-gray-500 dark:text-gray-400">
            <span>¿No tienes cuenta?</span>
            <a href="{{ route('register') }}"
               class="font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                Regístrate
            </a>
        </div>
    </form>
</x-guest-layout>