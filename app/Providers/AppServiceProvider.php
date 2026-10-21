<?php

namespace App\Providers;
use Illuminate\Support\Facades\URL;
use App\Listeners\CreatePendingOrderAfterLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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
	URL::forceScheme('https');
        Event::listen(Login::class, CreatePendingOrderAfterLogin::class);
    }
}
