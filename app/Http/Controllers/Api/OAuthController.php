<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Contracts\OAuth\OAuthClient;
use App\Http\Requests\Api\Auth\CallbackRequest;

class OAuthController extends Controller
{
    /**
     * OAuth client service
     *
     * @var \App\Services\Contracts\OAuth\OAuthClient
     */
    protected $oAuthClient;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OAuthClient $oAuthClient)
    {
        $this->middleware('guest')->only('login', 'callback');
        $this->middleware('auth:sanctum')->only('logout', 'user');

        $this->oAuthClient = $oAuthClient;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $authUrl = $this->oAuthClient->login();

        return response()->json(compact('authUrl'));
    }

    /**
     * Handle the callback from the oauth server
     *
     * @param  \App\Http\Requests\Api\Auth\CallbackRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(CallbackRequest $request)
    {
        $authCode   = $request->get('authCode');
        $deviceName = $request->get('deviceName');

        $accessToken = $this->oAuthClient->callback($authCode);

        $userInfo = $this->oAuthClient->fetchUser($accessToken);

        $user = $this->oAuthClient->upsertUser($userInfo);

        $user->tokens()->where('name', $deviceName)->delete();

        $user->token = $user->createToken($deviceName)->plainTextToken;

        return response()->json($user->toArray());
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        $this->oAuthClient->logout($user->id);

        $user->currentAccessToken()->delete();

        return response()->json([], 204);
    }

    /**
     * Returns the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        $user = auth()->user();

        return response()->json($user);
    }
}
