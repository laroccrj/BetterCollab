<?php


namespace App\Services;


use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use function base64_encode;
use function json_decode;
use function time;
use function var_dump;

class SpotifyApiService
{
    // move to config
    private $clientId = '6575ff02122e4769a7e1b06a11727bbc';
    private $clientSecret = 'fdc374c3d9ae4a939e1a043b3a358141';
    private $clientBearer;

    protected $spotifyApi;

    /**
     * SpotifyApiService constructor.
     */
    public function __construct()
    {
        $this->spotifyApi = new Client(['base_uri' => 'https://api.spotify.com/v1/']);
        $this->spotifyAccountApi = new Client(['base_uri' => 'https://accounts.spotify.com/api/']);
        $this->clientBearer = base64_encode($this->clientId . ':' . $this->clientSecret);
    }

    /**
     * @param User $user
     * @return User
     */
    public function checkRefresh(User $user): User
    {
        // Within a minute refresh
        if ($user->getExpires() < time() + 60) {
            $response = $this->spotifyAccountApi->post('token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $user->getRefreshToken(),
                ],
                'headers' => [
                    'Authorization'     => 'Basic ' . $this->clientBearer
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            $user->setAccessToken($result['access_token'])
                ->setExpires(time() + $result['expires_in'])
                ->save();
        }

        return $user;
    }

    public function getOAuthTokens($code, $redirectUrl) {
        $result = $this->spotifyAccountApi->post('token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirectUrl,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]
        ]);

        return json_decode($result->getBody()->getContents(), true);
    }

    public function getProfile(User $user)
    {
        $user = $this->checkRefresh($user);
        $result = $this->spotifyApi->get('me', [
            'headers' => [
                'Authorization'     => 'Bearer ' . $user->getAccessToken()
            ]
        ]);

        return json_decode($result->getBody()->getContents(), true);
    }

    /**
     * @param User $user
     * @param string $name
     * @return Playlist
     */
    public function createPlaylist(User $user, string $name)
    {
        $user = $this->checkRefresh($user);
        $result = $this->spotifyApi->post('users/' . $user->getSpotifyId() . '/playlists', [
            'headers' => [
                'Authorization'     => 'Bearer ' . $user->getAccessToken(),
                'Content-Type'     => 'application/json',
            ],
            RequestOptions::JSON => [
                'name' => $name,
            ]
        ]);

        $response = json_decode($result->getBody()->getContents(), true);
        $name = $response['name'];
        $spotifyPlaylistId = $response['id'];

        $playlist = new Playlist();
        $playlist->setUserId($user->getId())
            ->setName($name)
            ->setSpotifyPlaylistId($spotifyPlaylistId)
            ->setCollabLink(hash('sha256', $name . $spotifyPlaylistId))
            ->save();

        return $playlist;
    }

    public function trackSearch(User $user, string $query, int $offset = 0)
    {
        $user = $this->checkRefresh($user);
        $result = $this->spotifyApi->get('search', [
            'headers' => [
                'Authorization'     => 'Bearer ' . $user->getAccessToken()
            ],
            'query' => [
                'q' => $query,
                'type' => 'track',
                'offset' => $offset
            ]
        ]);

        $result = json_decode($result->getBody()->getContents(), true);

        $songs = [];
        $tracks = $result['tracks']['items'];

        foreach ($tracks as $track) {
            $songs[] = Song::fromSpotifyApi($track);
        }

        return $songs;
    }

    public function addSongToPlaylist(Playlist $playlist, Song $song)
    {
        $user = $this->checkRefresh($playlist->getUser());
        $result = $this->spotifyApi->post(
            'playlists/' . $playlist->getSpotifyPlaylistId() . '/tracks'
,           [
                'headers' => [
                    'Authorization'     => 'Bearer ' . $user->getAccessToken(),
                    'Content-Type'     => 'application/json',
                ],
                RequestOptions::JSON => [
                    'uris' => [$song->getSpotifyUri()]
                ]
            ]
        );

        return $result->getStatusCode() >= 200 && $result->getStatusCode() < 300;
    }

    /**
     * @param User $user
     * @param string $id
     * @return Song
     */
    public function getTrackById(User $user, string $id): Song
    {
        $user = $this->checkRefresh($user);
        $result = $this->spotifyApi->get('tracks/' . $id, [
            'headers' => [
                'Authorization'     => 'Bearer ' . $user->getAccessToken()
            ],
        ]);

        $track = json_decode($result->getBody()->getContents(), true);

        return Song::fromSpotifyApi($track);
    }
}
