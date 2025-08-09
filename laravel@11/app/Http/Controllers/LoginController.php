<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request) {
        $result = [];

        $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        if (Auth::attempt($request->only(['username', 'password']))){
            $token = JWTAuth::fromUser(Auth::user());
            setcookie('jwt-user', $token, time() + 3600, '/');

            $result = [
                'success' => true,
                'token' => $token
            ];

            return response()->json($result, 200);
        }
        
        throw ValidationException::withMessages([
            'username' => ['Please enter a correct username.'],
            'password' => ['Please enter a correct password.']
        ]);
    }

    public function logout(Request $request){
        $token = JWTAuth::getToken($request->headers);
    
        if ($token) {
            JWTAuth::invalidate($token);
        }

        if (isset($_COOKIE['jwt-user'])) {
            setcookie($_COOKIE['jwt-user'], "", time() - 3600, '/');
        }

        \Cookie::queue(\Cookie::forget('jwt-user'));
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
