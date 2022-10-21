<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use mysql_xdevapi\Exception;

class AuthController extends Controller
{

    public function index() {
        $user = User::find(Auth::user()->id);
        return response()->json(['user' => $user]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'firstName' => 'required',
            'lastName' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $data = $request->all();
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['username'] = $user->username;

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User register successfully'
        ];

        return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['username'] = $user->username;


            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User login successfully',
            ];
            return response()->json($response, 200);
        }
        else {
            $response = [
                'success' => false,
                'message' => 'Unauthorised'
            ];
            return response()->json($response);
        }

    }

    function logout()
    {
        Session::flush();

        Auth::logout();

        return response()->json(['success' => 200]);
    }


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function changeName(Request $request) {
        $request->validate([
            'oldFirstName' => 'required',
            'newFirstName' => 'required',
            'oldLastName' => 'required',
            'newLastName' => 'required',
        ]);
        $data = $request->all();
        if (Auth::user()->first_name === $data['oldFirstName'] && Auth::user()->last_name === $data['oldLastName']) {

            User::where('id', '=', Auth::user()->id)->update([
                'first_name' => $data['newFirstName'],
                'last_name' => $data['newLastName'],
            ]);
            return response()->json(['status' => 200]);
        }
        else {
            return response()->json(['status' => 500]);
        }
    }

    public function changeEmail(Request $request) {
        $request->validate([
            'oldEmail' => 'required',
            'newEmail' => 'required',
        ]);

        $data = $request->all();

        if ($data['oldEmail'] === Auth::user()->email) {

            User::where('id', '=', Auth::user()->id)->update([
                'email' => $data['newEmail'],
            ]);
            return response()->json(['status' => 200]);
        }
        else {
            return response()->json(['status' => 500]);
        }
    }

    public function changePhone(Request $request) {
        $request->validate([
            'oldPhone' => 'required',
            'newPhone' => 'required',
        ]);

        $data = $request->all();

        if ($data['oldPhone'] === Auth::user()->phone) {

            User::where('id', '=', Auth::user()->id)->update([
                'phone' => $data['newPhone'],
            ]);
            return response()->json(['status' => 200]);
        }
        else {
            return response()->json(['status' => 500]);
        }
    }

    public function changePassword(Request $request) {
        $request->validate([
            'email' => 'required',
            'newPassword' => 'required',
        ]);

        $data = $request->all();

        if ($data['email'] === Auth::user()->email) {

            User::where('id', '=', Auth::user()->id)->update([
                'password' => Hash::make($data['newPassword']),
            ]);
            return response()->json(['status' => 200]);
        }
        else {
            return response()->json(['status' => 500]);
        }
    }

    private function storeImage(Request $request)
    {
        if ($request->image != 'undefined') {
            $newImageName = uniqid() . '-userphoto' . '.' . $request->image->extension();
            return $request->image->move('images/', $newImageName);
        }
        else {
            return null;
        }
    }

    public function addPhoto(Request $request)
    {
        $request->validate([
            'image' => 'nullable|max:2048|'
        ]);
        $data = $request->all();
        User::where('id', '=', Auth::user()->id)->update([
            'image' => $this->storeImage($request) ?? null,
        ]);
        return response()->json(['success' => 'Photo uploaded successfully']);
    }

    public function userDataExport($user) {
        $array = User::with('posts')->
        whereKey($user)->
        get(['users.id', 'users.username', 'users.email', 'users.first_name', 'users.last_name', 'users.phone',
            'users.created_at', 'users.updated_at', 'users.balance']);
        return (new UserExport($array))->download('user.xlsx');
    }
}
