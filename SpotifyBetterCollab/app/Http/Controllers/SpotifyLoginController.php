<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SpotifyApiService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use function env;
use function json_decode;
use function time;
use function urlencode;
use function var_dump;

class SpotifyLoginController extends Controller
{

    public function login()
    {
        $appUrl = env('APP_URL', null);

        return Redirect::to(
            'https://accounts.spotify.com/authorize'
            . '?response_type=code'
            . '&client_id=' . '6575ff02122e4769a7e1b06a11727bbc'
            . '&scope=' . urlencode('playlist-modify-public playlist-modify-private')
            . '&redirect_uri=' . urlencode($appUrl. '/spotify/callback')
        );
    }

    public function logout()
    {
        if ($user = User::fromSession()) {
            $user->removeFromSession();
        }

        return Redirect::to('/');
    }

    public function spotifyCallback(Request $request)
    {
        $code = $request->get('code');
        $api = new SpotifyApiService();

        $response = $api->getOAuthTokens($code, '/spotify/callback');
        $accessToken = $response['access_token'];
        $expires = $response['expires_in'] + time();
        $refreshToken = $response['refresh_token'];

        $client = new Client(['base_uri' => 'https://api.spotify.com/v1/']);
        $result = $client->get('me', [
            'headers' => [
                'Authorization'     => 'Bearer ' . $accessToken
            ]
        ]);

        $response = json_decode($result->getBody()->getContents(), true);
        $id = $response['id'];

        /** @var User $user */
        $user = User::where(['spotify_id' => $id])->first();

        if (!$user) {
            $user = new User();
            $user->setSpotifyId($id);
        }

        new User();
        $user->setAccessToken($accessToken)
            ->setExpires($expires)
            ->setRefreshToken($refreshToken)
            ->save();

        $user->saveToSession();
        return Redirect::to('/');
    }
}
