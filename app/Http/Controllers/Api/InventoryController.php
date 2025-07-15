<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Shop_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function updateStock(Request $request) {

        $user = Auth::user();

        $shop_id = Shop_user::where('user_id', $user->id)->first()->shop_id;

        $product = Product::where('shop_id', $shop_id)->where('name', $request->name)->first();

        $inventory = Inventory::where('shop_id', $shop_id)->where('product_id', $product->id)->first();

        $inventory->update([
            'stock_qty' => $inventory->stock_qty + $request->stock,
            'notes' => $request->notes
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Stock updated successfully'
        ]);
    }
}
