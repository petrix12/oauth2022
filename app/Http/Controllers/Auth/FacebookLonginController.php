<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class FacebookLonginController extends Controller
{
    public function login(){
        return Socialite::driver('facebook')->redirect();
    }

    /* public function callback(){
        return Socialite::driver('facebook')->redirect();
    } */

    public function loginWithFacebook(){
        dd(Socialite::driver('facebook')->user());
    }
}
