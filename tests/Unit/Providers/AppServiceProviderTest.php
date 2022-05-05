<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use App\Services\Contracts\OAuth\OAuthClient;
use App\Services\Google\OAuth\GoogleOAuthClient;
use App\Services\Contracts\Calendar\EventService;
use App\Services\Google\Calendar\GoogleEventService;

class AppServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function the_app_is_using_google_oauth_client_as_oauth_client()
    {
        $oAuthClient = $this->app->make(OAuthClient::class);

        $this->assertInstanceOf(GoogleOAuthClient::class, $oAuthClient);
    }

    /**
     * @test
     */
    public function the_app_is_using_google_event_service_as_event_service()
    {
        $eventService = $this->app->make(EventService::class);

        $this->assertInstanceOf(GoogleEventService::class, $eventService);
    }
}
