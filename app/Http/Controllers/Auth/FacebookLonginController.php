<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class FacebookLonginController extends Controller
{
    public function login(){
        return Socialite::driver('facebook')->redirect();
    }

    public function loginWithFacebook(){
        $userFacebook = Socialite::driver('facebook')->user();

        $user = User::where('email', $userFacebook->getEmail())->first();

        if(!$user){
            $user = User::create([
                'name' => $userFacebook->getName(),
                'email' => $userFacebook->getEmail(),
                'password' => '',
                'facebook_id' => $userFacebook->getId(),
                'avatar'  => $userFacebook->getAvatar(),
                'nick'  => $userFacebook->getNickname()
            ]);
        }

        auth()->login($user);
        return redirect()->route('home');
    }
}
