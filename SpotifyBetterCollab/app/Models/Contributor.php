<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Contributor extends Model
{
    protected $table = 'contributor';
    protected $primaryKey = 'id';
    protected $fillable = ['playlist_id', 'user_id'];
    protected $with = ['user', 'songs'];

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function songs(){
        return $this->hasMany('App\Models\Song', 'contributor_id', 'id');
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
     * @return string
     */
    public function getPlaylistId(): string
    {
        return $this->playlist_id;
    }

    /**
     * @param string $playlist_id
     * @return Contributor
     */
    public function setPlaylistId(string $playlist_id): Contributor
    {
        $this->playlist_id = $playlist_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     * @return Contributor
     */
    public function setUserId(string $user_id): Contributor
    {
        $this->user_id = $user_id;
        return $this;
    }
}
