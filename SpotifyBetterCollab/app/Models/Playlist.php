<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use function session;

class Playlist extends Model
{
    protected $table = 'playlist';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'spotify_playlist_id', 'name', 'collab_link'];
    protected $with = ['user', 'contributors'];

    /**
     *@deprecated Use getUser instead
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * @deprecated Use getSongs instead
     */
    public function songs()
    {
        return $this->hasMany('App\Models\Song', 'playlist_id', 'id');
    }

    public function contributors()
    {
        return $this->hasMany('App\Models\Contributor', 'playlist_id', 'id');
    }

    /**
     * @return Contributor[] | Collection
     */
    public function getContributors()
    {
        return $this->contributors;
    }

    /**
     * @param int $userId
     * @return Contributor | null
     */
    public function getContributorByUserId(int $userId): ?Contributor
    {
        foreach ($this->getContributors() as $contributor) {
            if ($contributor->getUserId() == $userId)
                return $contributor;
        }

        return null;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Song[] | Collection
     */
    public function getSongs()
    {
        return $this->songs;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $userId
     * @return Playlist
     */
    public function setUserId(int $userId): Playlist
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpotifyPlaylistId(): string
    {
        return $this->spotify_playlist_id;
    }

    /**
     * @param string $spotify_playlist_id
     * @return Playlist
     */
    public function setSpotifyPlaylistId(string $spotify_playlist_id): Playlist
    {
        $this->spotify_playlist_id = $spotify_playlist_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Playlist
     */
    public function setName(string $name): Playlist
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollabLink(): string
    {
        return $this->collab_link;
    }

    /**
     * @param string $collab_link
     * @return Playlist
     */
    public function setCollabLink(string $collab_link): Playlist
    {
        $this->collab_link = $collab_link;
        return $this;
    }

    /**
     * @return int
     */
    public function getSongLimit(): int
    {
        return $this->song_limit;
    }

    /**
     * @param int $song_limit
     * @return Playlist
     */
    public function setSongLimit(int $song_limit): Playlist
    {
        $this->song_limit = $song_limit;
        return $this;
    }
}
