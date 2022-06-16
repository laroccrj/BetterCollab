<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use function session;

class User extends Model
{
    const SESSION_USER_ID = 'user_id';
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $fillable = ['spotify_id', 'access_token', 'expires', 'refresh_token', 'name', 'profile_pic', 'color'];
    protected $hidden = ['access_token', 'refresh_token', 'expires'];

    public function playlists()
    {
        return $this->hasManyThrough(
            'App\Models\Playlist',
            'App\Models\Contributor',
            'user_id',
            'id',
            'id',
            'playlist_id'
        );
    }

    public function saveToSession()
    {
        session()->put(self::SESSION_USER_ID, $this->getId());
    }

    public function removeFromSession()
    {
        session()->remove(self::SESSION_USER_ID);
    }

    /**
     * @return User | null
     */
    public static function fromSession()
    {
        $id = session()->get(self::SESSION_USER_ID);
        return User::find($id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return User
     */
    public function setSpotifyId(string $spotify_id): User
    {
        $this->spotify_id = $spotify_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    /**
     * @param string $access_token
     * @return User
     */
    public function setAccessToken(string $access_token): User
    {
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpires(): int
    {
        return $this->expires;
    }

    /**
     * @param int $expires
     * @return User
     */
    public function setExpires(int $expires): User
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refresh_token;
    }

    /**
     * @param string $refresh_token
     * @return User
     */
    public function setRefreshToken(string $refresh_token): User
    {
        $this->refresh_token = $refresh_token;
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
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfilePic(): string
    {
        return $this->profile_pic;
    }

    /**
     * @param string $profile_pic
     * @return User
     */
    public function setProfilePic(string $profile_pic): User
    {
        $this->profile_pic = $profile_pic;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return User
     */
    public function setColor(string $color): User
    {
        $this->color = $color;
        return $this;
    }

    public function setRandomColor(): User
    {
        $color = sprintf("#%06x",rand(0,16777215));
        return $this->setColor($color);
    }
}
