<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Sale_item;
use App\Models\Shop_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleItemsController extends Controller
{
    function salesEntery(Request $request) {
        $user = Auth::user();
        $shopId = Shop_user::where('user_id', $user->id)->first()->shop_id;
        $sale = Sale::create([
            'shop_id' => $shopId,
            'user_id' => $user->id,
            'total_amount' => $request->total
        ]);

        foreach($request->items as $item) {
            Sale_item::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['quantity'] * $item['price']
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Sale added successfully',
            'sale' => $sale
        ]);
    }
}
