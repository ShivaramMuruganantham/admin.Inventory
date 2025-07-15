<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Revenue;
use App\Models\Sale;
use App\Models\Sale_item;
use App\Models\Shop_user;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    function availableYears() {
       
        $user = Auth::user();
        
        $years = Sale::select(DB::raw('YEAR(created_at) as year'))
                    ->where('user_id', $user->id)
                    ->groupBy('year')
                    ->orderBy('year', 'desc')
                    ->pluck('year');
        
        return response()->json([
            'years' => $years,
            'status' => true
        ]);
    }

    function saleGraph($year) {
        
        $user = Auth::user();

        $salesGraph = Sale::select(
                            DB::raw('MONTH(created_at) as month'), 
                            DB::raw('SUM(total_amount) as total')
                        )
                        ->whereYear('created_at', $year)
                        ->where('user_id', $user->id)
                        ->groupBy('month')
                        ->orderBy('month', 'asc')
                        ->get()
                        ->keyBy('month');
                        
        $result = collect(range(1, 12))->map(function ($month) use ($salesGraph) {
            $total = $salesGraph->has($month) ? $salesGraph->get($month)->total : 0;
            return [
                'month' => $month,
                'month_name' => Carbon::create()->month($month)->format('M'),
                'total' => $total
            ];
        });

        return response()->json([
            'status' => true,
            'graph' => $result
        ]);
    }

    function salesDetails($year) {

        $user = Auth::user();

        $shopId = Shop_user::where('user_id', $user->id)->first()->shop_id;
        
        $soldProduct = Sale_item::join('sales', 'sale_items.sale_id', '=', 'sales.id')
                                ->join('products', 'sale_items.product_id', '=', 'products.id')
                                ->where('sales.shop_id', $shopId)
                                ->whereYear('sales.created_at', $year)
                                ->select(
                                    'products.id as product_id',
                                    'products.name as product_name',
                                    'products.quantity as quantity',
                                    DB::raw('SUM(sale_items.quantity) as total_sale'),
                                    DB::raw('SUM(sale_items.quantity * sale_items.price) as total_price'),
                                    DB::raw('MAX(sale_items.created_at) as sold_date')
                                )
                                ->groupBy('products.id', 'products.name', 'products.quantity')
                                ->get();
        return response()->json([
            'status' => true,
            'sales' => $soldProduct
        ]);
    }

    function revenue($year) {
        $user = Auth::user();
        $shopId = Shop_user::where('user_id', $user->id)->first()->shop_id;
        $revenue = Revenue::where('shop_id', $shopId)->where('year', $year)->get();
        return response()->json([
            'status' => true,
            'revenue' => $revenue
        ]);
    }
}
