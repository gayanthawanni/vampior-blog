<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        $authUser = User::firstOrCreate(
            ['email' => $user->getEmail()],
            [
                'name' => $user->getName(),
                'password' => bcrypt(Str::random(16)),
            ]
        );

        Auth::login($authUser);

        return redirect()->route('dashboard');
    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();

        $authUser = User::firstOrCreate(
            ['email' => $user->getEmail()],
            [
                'name' => $user->getName() ?? $user->getNickname(),
                'password' => bcrypt(Str::random(16)),
            ]
        );

        Auth::login($authUser);

        return redirect()->route('dashboard');
    }
}
