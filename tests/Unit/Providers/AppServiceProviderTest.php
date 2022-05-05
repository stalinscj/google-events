<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use App\Services\Contracts\OAuth\OAuthClient;
use App\Services\Google\OAuth\GoogleOAuthClient;

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

}
