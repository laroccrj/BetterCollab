<?php


namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Services\SpotifyApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use function response;

class SongController extends Controller
{
    public function getSearch(Request $request)
    {
        $query = $request->get("q");
        $user = User::fromSession();
        $api = new SpotifyApiService();
        $result = $api->trackSearch($user, $query);
        return response()->json($result);
    }
}
