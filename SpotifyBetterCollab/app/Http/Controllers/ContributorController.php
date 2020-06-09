<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\Playlist;
use App\Models\User;
use App\Services\SpotifyApiService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use function json_decode;
use function response;
use function time;
use function urlencode;
use function var_dump;

class ContributorController extends Controller
{

    public function getAddContributor(Request $request, $collabHash)
    {
        /** @var Playlist $playlist */
        $playlist = Playlist::where(['collab_link' => $collabHash])->first();

        if (!$playlist) {
            return response(null, 404);
        }

        $user = User::fromSession();

        if ($user) {
            $contributor = new Contributor();
            $contributor->setPlaylistId($playlist->getId())
                ->setUserId($user->getId())
                ->save();

            return Redirect::to('/playlist/' . $playlist->getId());
        } else {
            return Redirect::to(
                'https://accounts.spotify.com/authorize'
                . '?response_type=code'
                . '&client_id=' . '6575ff02122e4769a7e1b06a11727bbc'
                . '&scope=' . urlencode('playlist-modify-public playlist-modify-private')
                . '&redirect_uri=' . urlencode('http://localhost/collab/callback')
                . '&state=' . $collabHash
            );
        }
    }

    public function spotifyCallback(Request $request)
    {
        $hash = $request->get('state');
        $code = $request->get('code');
        $api = new SpotifyApiService();

        $response = $api->getOAuthTokens($code, 'http://localhost/collab/callback');
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
        return Redirect::to('/collab/' . $hash);
    }
}
