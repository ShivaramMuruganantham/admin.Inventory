<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function ShopRegister(Request $request) {
        
        $validated = $request->validate([
            'shopName' => 'required|string|max:255',
            'ownerName' => 'required|string|regex:/^[A-Za-z\s]+$/|max:50',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'shopPhone' => 'required|numeric|digits:10',
            'shopEmail' => 'required|email|unique:shops,email',
            'gst_number' => 'nullable|string',
        ]);

        $shop = Shop::create([
            'name' => $validated['shopName'],
            'owner_name' => $validated['ownerName'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'phone' => $validated['shopPhone'],
            'email' => $validated['shopEmail'],
            'gst_number' => $validated['gst_number'] ?? null,
        ]);

        return response()->json([
            'message' => 'Shop registered successfully',
            'shop' => $shop,
            'status' => true
        ], 201);
    }

}
