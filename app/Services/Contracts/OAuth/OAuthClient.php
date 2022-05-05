<?php

namespace App\Services\Contracts\OAuth;

use App\Models\User;

interface OAuthClient
{
    /**
     * Returns the auth url to the OAuth server for request an authorization code.
     *
     * @return string
     */
    public function login(): string;

    /**
     * Swap with OAuth server the authorization code by an access token for the user.
     *
     * @return string
     */
    public function callback(string $authCode): string;

    /**
     * Fetch the user information from the OAuth server.
     *
     * @param  string $accessToken
     * @return array
     */
    public function fetchUser(string $accessToken): array;

    /**
     * Update or Create a user.
     *
     * @param  array $info
     * @return \App\Models\User
     */
    public function upsertUser(array $info): User;

    /**
     * Remove the user's access token.
     *
     * @param int $userId
     */
    public function logout(int $userId): void;
}
