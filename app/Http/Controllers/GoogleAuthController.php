<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{

    public array $response;

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
                if (isset($google_user->user['family_name'])) {
                    $username = strtolower($google_user->user['given_name'] .
                        $google_user->user['family_name'] .
                        $google_user->user['sub'][7] .
                        $google_user->user['sub'][4] .
                        $google_user->user['sub'][10] .
                        $google_user->user['email'][2]
                    );
                }
                else {
                    $username = strtolower($google_user->user['given_name'] .
                        'user' .
                        $google_user->user['sub'][7] .
                        $google_user->user['sub'][4] .
                        $google_user->user['sub'][10] .
                        $google_user->user['email'][2]
                    );
                }

                $new_user = User::create([
                    'username' => $username,
                    'first_name' => $google_user->user['given_name'],
                    'last_name' =>  $google_user->user['family_name'] ?? null,
                    'email' => $google_user->getEmail(),
                    'image' => $google_user->getAvatar(),
                    'google_id' => $google_user->getId()
                ]);

                Auth::login($new_user);

                $user = Auth::user();
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['username'] = $user->username;

                return view('callback', [
                    'success' => true,
                    'token' => $success['token'],
                    'username' => $success['username'],
                    'message' => 'User login successfully',
                ]);
            }
            else {
                Auth::login($user);
                $user = Auth::user();
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['username'] = $user->username;

                return view('callback', [
                    'success' => true,
                    'token' => $success['token'],
                    'username' => $success['username'],
                    'message' => 'User login successfully',
                ]);
            }

        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
