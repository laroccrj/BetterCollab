<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use function session;
use function var_dump;

class Song extends Model
{
    protected $table = 'song';
    protected $primaryKey = 'id';

    protected $fillable = [
        'playlist_id',
        'contributor_id',
        'spotify_id',
        'spotify_uri',
        'title',
        'artist',
        'album_art',
        'album',
        'priority'
    ];

    /**
     *@deprecated Use getUser instead
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'contributor_id', 'id');
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $track
     * @return Song
     */
    public static function fromSpotifyApi($track): Song
    {
        $artistsString = null;

        foreach ($track['artists'] as $artist) {
            if ($artistsString !== null) $artistsString .= ', ';
            $artistsString .= $artist['name'];
        }

        $song = new Song();
        return $song->setTitle($track['name'])
            ->setSpotifyId($track['id'])
            ->setSpotifyUri($track['uri'])
            ->setAlbum($track['album']['name'])
            ->setAlbumArt($track['album']['images'][2]['url'])
            ->setArtist($artistsString);
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
    public function getPlaylistId(): int
    {
        return $this->playlist_id;
    }

    /**
     * @param int $playlist_id
     * @return Song
     */
    public function setPlaylistId(int $playlist_id): Song
    {
        $this->playlist_id = $playlist_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getContributorId(): int
    {
        return $this->contributor_id;
    }

    /**
     * @param int $contributor_id
     * @return Song
     */
    public function setContributorId(int $contributor_id): Song
    {
        $this->contributor_id = $contributor_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpotifyId(): string
    {
        return $this->spotify_id;
    }

    /**
     * @param string $spotify_id
     * @return Song
     */
    public function setSpotifyId(string $spotify_id): Song
    {
        $this->spotify_id = $spotify_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpotifyUri(): string
    {
        return $this->spotify_uri;
    }

    /**
     * @param string $spotify_uri
     * @return Song
     */
    public function setSpotifyUri(string $spotify_uri): Song
    {
        $this->spotify_uri = $spotify_uri;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Song
     */
    public function setTitle(string $title): Song
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getArtist(): string
    {
        return $this->artist;
    }

    /**
     * @param string $artist
     * @return Song
     */
    public function setArtist(string $artist): Song
    {
        $this->artist = $artist;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlbumArt(): string
    {
        return $this->album_art;
    }

    /**
     * @param string $album_art
     * @return Song
     */
    public function setAlbumArt(string $album_art): Song
    {
        $this->album_art = $album_art;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlbum(): string
    {
        return $this->album;
    }

    /**
     * @param string $album
     * @return Song
     */
    public function setAlbum(string $album): Song
    {
        $this->album = $album;
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return Song
     */
    public function setPriority(int $priority): Song
    {
        $this->priority = $priority;
        return $this;
    }
}
