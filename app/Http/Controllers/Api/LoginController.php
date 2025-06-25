<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function userLogin(Request $request) {

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => false
            ],404);         
        }

        if(Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'name' => $user->name,
                    'api_token' => $user->api_token
                ],
                'status' => true
            ],200);
        }

        return response()->json([
            'message' => 'Invalid credentials',
            'status' => false
        ], 401);

    }


    function UserLogout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout successful',
            'status' => true
        ], 200);
    }
}