<?php


namespace App\Http\Controllers\Api;


use App\Models\Contributor;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use App\Services\SpotifyApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use function array_pop;
use function array_shift;
use function array_values;
use function is_numeric;
use function preg_replace;
use function print_r;
use function rand;
use function response;
use function shuffle;
use function sizeof;
use function var_dump;
use const PHP_EOL;

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
        $playlist = Playlist::with(['songs'])->find($contributor->getPlaylistId());

        if ($contributor->getSongs()->count() >= $playlist->getSongLimit())
            return response('You\'ve already added the max amount of songs', 400);

        $song = $api->getTrackById($user, $trackId);
        if($api->addSongToPlaylist($playlist, $song)) {
            $song->setPlaylistId($playlist->getId())
                ->setContributorId($contributor->getId())
                ->setPriority($playlist->getSongs()->count())
                ->save();

            $song = Song::find($song->getId());

            return response()->json($song);
        } else {
            return response()->setStatusCode(500, 'It didn\'t work');
        }
    }

    public function postDeleteSong(Song $song)
    {
        $api = new SpotifyApiService();
        $user = User::fromSession();

        /** @var Contributor $contributor */
        $contributor = Contributor::find($song->getContributorId());

        if ($user->getId() != $contributor->getUserId())
            return response('Logged in user / contributor miss-match', 403);

        $song->delete();

        /** @var Playlist $playlist */
        $playlist = Playlist::with(['user', 'songs'])->find($contributor->getPlaylistId());
        $api->recreatePlaylist($playlist);
        $playlist = Playlist::find($playlist->getId());
        return response()->json($playlist);
    }

    public function postPlaylistSettings(Request $request, Playlist $playlist)
    {
        $songLimit = $request->get('songLimit', $playlist->getSongLimit());
        $user = User::fromSession();

        if ($user->getId() != $playlist->getUserId())
            return response('Logged in user / playlist miss-match', 401);

        $playlist->setSongLimit($songLimit)
            ->save();

        return response()->json($playlist);
    }

    public function postShuffle(Playlist $playlist)
    {
        $api = new SpotifyApiService();
        $user = User::fromSession();

        if ($user->getId() != $playlist->getUserId())
            return response('Not the owner of the playlist', 401);


        /** @var Contributor[] | Collection $contributors */
        $contributors = $playlist->getContributors()->shuffle();
        $songsByContributor = [];

        foreach ($contributors as $contributor) {
            $songsByContributor[] = $contributor->getSongs()->shuffle();
        }

        $priority = 0;
        $songsLeft = true;

        while ($songsLeft) {
            $songsLeft = false;

            /** @var Song[] | Collection $songs */
            foreach ($songsByContributor as $songs) {
                if ($songs->count() > 0) {
                    $song = $songs->pop();
                    $song->setPriority($priority++)->save();
                    $songsLeft = true;
                }
            }
        }

        $playlist = Playlist::find($playlist->getId());
        $api->recreatePlaylist($playlist);
        return response()->json($playlist);
    }
}
