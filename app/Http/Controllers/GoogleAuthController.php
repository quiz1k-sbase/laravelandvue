<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $google_user->getId())->first();
            if (!$user) {
                $new_user = User::create([
                    'first_name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'image' => $google_user->getAvatar(),
                    'google_id' => $google_user->getId()
                ]);

                Auth::login($new_user);

                return dd('if !user it works');
            }
            else {
                Auth::login($user);

                dd('else if works');
            }

        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
