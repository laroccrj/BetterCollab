<?php


namespace App\Http\Controllers\Api;


use App\Models\Contributor;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use App\Services\SpotifyApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use function is_numeric;
use function preg_replace;
use function print_r;
use function response;
use function var_dump;

class PlaylistController extends Controller
{
    public function postNewPlaylist(Request $request)
    {
        $name = $request->get("name");
        $api = new SpotifyApiService();
        $user = User::fromSession();
        $playlist = $api->createPlaylist($user, $name);
        $contributor = new Contributor();
        $contributor->setUserId($user->getId())
            ->setPlaylistId($playlist->getId())
            ->save();

        return response()->json($playlist);
    }

    public function getSql($builder) {
        $sql = $builder->toSql();
        foreach ( $builder->getBindings() as $binding ) {
            $value = is_numeric($binding) ? $binding : "'".$binding."'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }

    public function getPlaylists(Request $request)
    {
        $user = User::fromSession();
        $playlists = Playlist::where(['user_id' => $user->getId()])->get();
        return response()->json($playlists);
    }

    public function getPlaylist(Playlist $playlist)
    {
        return response()->json($playlist);
    }

    public function postAddSong(Request $request, Contributor $contributor)
    {
        $trackId = $request->get("spotifyId");
        $api = new SpotifyApiService();
        $user = User::fromSession();

        if ($user->getId() != $contributor->getUserId())
            return response('Logged in user / contributor miss-match', 403);

        /** @var Playlist $playlist */
        $playlist = Playlist::with([])->find($contributor->getPlaylistId());

        if ($contributor->getSongs()->count() >= $playlist->getSongLimit())
            return response('You\'ve already added the max amount of songs', 400);

        $song = $api->getTrackById($user, $trackId);
        if($api->addSongToPlaylist($playlist, $song)) {
            $song->setPlaylistId($playlist->getId())
                ->setContributorId($contributor->getId())
                ->save();

            $song = Song::find($song->getId());

            return response()->json($song);
        } else {
            return response()->setStatusCode(500, 'It didn\'t work');
        }
    }

    public function postPlaylistSettings(Request $request, Playlist $playlist)
    {
        $songLimit = $request->get('songLimit', $playlist->getSongLimit());
        $user = User::fromSession();

        if ($user->getId() != $playlist->getUserId())
            return response('Logged in user / playlist miss-match', 403);

        $playlist->setSongLimit($songLimit)
            ->save();

        return response()->json($playlist);
    }
}
