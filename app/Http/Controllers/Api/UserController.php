<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Shop_user;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    function adminRegister(Request $request) {
        
        $validate = $request->validate([
            'shopName' => 'required',
            'shopEmail' => 'required|email',
            'userName' => 'required',
            'userEmail' => 'required|email',
            'phone' => 'required',
            'userRole' => 'required',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $validate['userName'],
            'email' => $validate['userEmail'],
            'password' => Hash::make($validate['password']),
            'phone' => $validate['phone'],
            'api_token' => Str::random(15),
        ]);

        $shop = Shop::where('email', $validate['shopEmail'])->where('name', $validate['shopName'])->first();

        if ($shop) {
            $shopUser = Shop_user::create([
                'shop_id' => $shop->id,
                'user_id' => $user->id,
                'role' => $validate['userRole'],
                'status' => 'active',
            ]);
        }

        return response()->json([
            'message' => 'Admin registered successfully',
            'status' => true
        ]);
    }

    function userRegister(Request $request) {

        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'phone' => 'required'
        ]);

        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password']),
            'phone' => $validate['phone'],
            'api_token' => Str::random(15),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'status' => true
        ], 201);
    }
}
