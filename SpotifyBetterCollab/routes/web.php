<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/api/user', 'Api\UserController@getUserProfile');
Route::get('/api/playlist', 'Api\PlaylistController@getPlaylists');
Route::post('/api/playlist/new', 'Api\PlaylistController@postNewPlaylist');
Route::post('/api/playlist/{contributor}/song/add', 'Api\PlaylistController@postAddSong');
Route::get('/api/song/search', 'Api\SongController@getSearch');
Route::get('/api/playlist/{playlist}', 'Api\PlaylistController@getPlaylist');
Route::post('/api/playlist/{playlist}/settings', 'Api\PlaylistController@postPlaylistSettings');


Route::get('/collab/callback', 'ContributorController@spotifyCallback');
Route::get('/collab/{collabHash}', 'ContributorController@getAddContributor');
Route::get('/login', 'SpotifyLoginController@login');
Route::get('/logout', 'SpotifyLoginController@logout');
Route::get('/spotify/callback', 'SpotifyLoginController@spotifyCallback');
Route::get('/{any}', array('as' => 'index', 'uses' => 'AppController@index'))->where('any', '.*');


