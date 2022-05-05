<?php

namespace App\Services\Google;

use Google\Service\Oauth2;
use Google\Service\Calendar;

class Google
{
    /**
     * Returns a google client
     *
     * @return \Google_Client
     */
    protected function getClient()
    {
        $appName    = config('app.name');
        $authConfig = config('services.google');

        $client = new \Google_Client();

        $client->setAuthConfig($authConfig);
        $client->setApplicationName($appName);
        $client->setApprovalPrompt('force');
        $client->setAccessType('offline');

        $client->setScopes([
            Oauth2::USERINFO_PROFILE,
            Oauth2::USERINFO_EMAIL,
            Oauth2::OPENID,
            Calendar::CALENDAR_EVENTS,
        ]);

        $client->setIncludeGrantedScopes(true);

        return $client;
    }

    /**
     * Returns a google client that is logged into the current user
     *
     * @param \App\Models\User $user
     * @return \Google_Client
     */
    protected function getUserClient($user): \Google_Client
    {
        $accessToken = stripslashes($user->google_token);

        $client = $this->getClient();

        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());

            $client->setAccessToken($client->getAccessToken());

            $user->update(['google_token' => json_encode($client->getAccessToken())]);
        }

        return $client;
    }
}
