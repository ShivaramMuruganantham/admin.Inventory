<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\Shop_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function Index() {
       
        $user = Auth::user();

        $userShop = Shop_user::where('user_id', $user->id)->first();
        
        $shop = Shop::where('id', $userShop->shop_id)->first();
        
        $sale = Sale::where('shop_id', $userShop->shop_id)->get();
        
        $Product = Product::where('shop_id', $userShop->shop_id)->get();

        $totalSale = $sale->sum('total_amount');

        $totalProduct = $Product->count();
        
        $totalSold = $sale->count();

        $totalStock = Inventory::where('shop_id', $userShop->shop_id)->sum('stock_qty');

        return response()->json([
            "data" => [
                "user" => [
                    "name" => $user->name,
                ],
                "shop" => [
                    "name" => $shop->name,
                    "email" => $shop->email,
                    "phone" => $shop->phone,
                ],
                "total_sale_amount" => $totalSale,
                "total_product" => $totalProduct,
                "total_sold" => $totalSold,
                "total_stock" => $totalStock
            ],
            "status" => true,
        ]);
    }
}
