<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment(['local', 'testing', 'staging'])) {
            $to = config('mail.capture_to', env('MAIL_CAPTURE_TO'));
            if (!empty($to)) {
                Mail::alwaysTo($to);
            }
        }

        // 🔁 Mantener el idioma seleccionado en toda la sesión
        if (Session::has('idioma')) {
            App::setLocale(Session::get('idioma'));
        }
    }
}
