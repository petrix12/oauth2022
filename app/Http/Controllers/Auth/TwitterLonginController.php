<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class TwitterLonginController extends Controller
{
    public function login(){
        return Socialite::driver('twitter')->redirect();
    }

    public function loginWithTwitter(){
        $userTwitter = Socialite::driver('twitter')->user();

        $user = User::where('email', $userTwitter->getEmail())->first();

        if(!$user){
            $user = User::create([
                'name' => $userTwitter->getName(),
                'email' => $userTwitter->getEmail(),
                'password' => '',
                'twitter_id' => $userTwitter->getId(),
                'avatar'  => $userTwitter->getAvatar(),
                'nick'  => $userTwitter->getNickname()
            ]);
        }

        auth()->login($user);
        return redirect()->route('home');
    }
}