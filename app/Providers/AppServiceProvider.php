<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\OAuth\OAuthClient;
use App\Services\Google\OAuth\GoogleOAuthClient;
use App\Services\Contracts\Calendar\EventService;
use App\Services\Google\Calendar\GoogleEventService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OAuthClient::class, GoogleOAuthClient::class);
        $this->app->bind(EventService::class, GoogleEventService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
