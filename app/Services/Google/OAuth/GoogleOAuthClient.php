<?php

namespace App\Services\Google\OAuth;

use App\Models\User;
use Google\Service\Oauth2;
use Illuminate\Support\Arr;
use App\Services\Google\Google;
use App\Services\Contracts\OAuth\OAuthClient;

class GoogleOAuthClient extends Google implements OAuthClient
{
    /**
     * Returns the auth url to the OAuth server for request an authorization code.
     *
     * @return string
     */
    public function login(): string
    {
        $client = $this->getClient();

        $authUrl = $client->createAuthUrl();

        return $authUrl;
    }

    /**
     * Swap with oauth server the authorization code to an access token for the user.
     *
     * @return string
     */
    public function callback(string $authCode): string
    {
        $client = $this->getClient();

        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        return json_encode($accessToken);
    }

    /**
     * Fetch the user information from the oauth server.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fetchUser(string $accessToken): array
    {
        $client = $this->getClient();

        $client->setAccessToken($accessToken);

        $service = new Oauth2($client);

        $userFromGoogle = $service->userinfo->get();

        return [
            'name'         => $userFromGoogle->name,
            'email'        => $userFromGoogle->email,
            'google_id'    => $userFromGoogle->id,
            'google_token' => $accessToken,
        ];
    }

    /**
     * Authenticate the user and upsert him.
     *
     * @param  array $info
     * @return \App\Models\User
     */
    public function upsertUser(array $info): User
    {
        $user = User::updateOrCreate(
            Arr::only($info, 'google_id'),
            Arr::only($info, ['name', 'email', 'google_token']),
        );

        return $user;
    }

    /**
     * Remove the user's access token.
     *
     * @param  int $userId
     */
    public function logout(int $userId): void
    {
        User::where('id', $userId)
            ->update(['google_token' => null]);
    }
}
