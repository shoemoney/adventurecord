<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use App\User;
use Auth;

class AuthController extends Controller
{
    public function getSocialRedirect($provider)
    {
        try
        {
            return Socialite::with($provider)->redirect();
        }
        catch(\InvalidArgumentException $e)
        {
            return redirect('/login');
        }
    }

    public function getSocialCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        $user = User::where('provider_id', '=', $socialUser->id)->first();

        if($user == null)
        {
            $newUser = new User();

            $newUser->provider_id = $socialUser->getId();
            $newUser->name = $socialUser->getName();
            $newUser->discriminator = $socialUser->user['discriminator'];
            $newUser->email = $socialUser->getEmail() == '' ? '' : $socialUser->getEmail();
            $newUser->avatar = $socialUser->getAvatar();

            $newUser->save();

            $user = $newUser;
        }
        else
        {
            $socialName = $socialUser->getName();
            $socialAvatar = $socialUser->getAvatar();
            
            if($user->name !== $socialName || $user->avatar !== $socialAvatar)
            {
                $user->name = $socialName;
                $user->avatar = $socialAvatar;
                $user->save();
            }
        }

        Auth::login($user);

        return redirect('/');
    }
}
