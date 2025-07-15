<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Revenue;
use App\Models\Sale_item;
use App\Models\Shop;
use App\Models\Shop_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'status' => true,
            'message' => 'Shop registered successfully',
            'shop' => $shop
        ], 201);
    }

    function totalRevenues() {
        $user = Auth::user();
        $shopId = Shop_user::where('user_id', $user->id)->first()->shop_id;

        $revenues = Revenue::where('shop_id', $shopId)->get();

        return response()->json([
            'status' => true,
            'revenues' => $revenues
        ]);
    }

    function revenueTarget(Request $request) {
        $user = Auth::user();
        $shopId = Shop_user::where('user_id', $user->id)->first()->shop_id;

        $saleAmount = Sale_item::where('shop_id', $shopId)->whereYear('created_at', date('Y'))->sum('price');

        $revenue = Revenue::create([
            'shop_id' => $shopId,
            'year' => date('Y'),
            'expected_amount' => $request->expected_amount,
            'collected_amount' =>  $saleAmount ?? 0,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Revenue added successfully'
        ], 201);
    }

}
