<?php


namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Services\SpotifyApiService;
use Illuminate\Routing\Controller;
use function gettype;
use function typeOf;
use function var_dump;

class UserController extends Controller
{
    public function getUserProfile()
    {
        $spotify = new SpotifyApiService();
        $user = User::fromSession();

        if ($user) {
            $profile = $spotify->getProfile($user);

            $user->setName($profile['display_name'])
                ->setProfilePic($profile['images'][0]['url'] ?? '/no-profile-pic')
                ->save();
        }

        return \response()->json($user);
    }
}
