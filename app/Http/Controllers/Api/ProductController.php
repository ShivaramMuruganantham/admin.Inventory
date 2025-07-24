<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Sale_item;
use App\Models\Shop_user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function availableYears()
    {
        $years = Sale_item::select(DB::raw('YEAR(created_at) as year'))
                    ->groupBy('year')
                    ->orderBy('year', 'desc')
                    ->pluck('year');
         
        return response()->json([
            'status' => true,
            'years' => $years
        ]);
    }

    public function graphData($year) {
        $salesGraph = Sale_item::select(
                            DB::raw('MONTH(created_at) as month'), 
                            DB::raw('SUM(total_amount) as total')
                        )
                        ->whereYear('created_at', $year)
                        ->groupBy('month')
                        ->orderBy('month', 'asc')
                        ->get()
                        ->keyBy('month');
                        
        $result = collect(range(1, 12))->map(function ($month) use ($salesGraph) {
            $total = $salesGraph->has($month) ? $salesGraph->get($month)->total : 0;
            return [
                'month' => $month,
                'month_name' => Carbon::create()->month($month)->format('F'),
                'total' => $total
            ];
        });
    }

    public function productDetails() {
        $user = Auth::user();

        $shop_id = Shop_user::where('user_id', $user->id)->first()->shop_id;

        $products = Product::where('shop_id', $shop_id)->with('inventory')->get();

        return response()->json([
            'status' => true,
            'products' => $products
        ]);
    }

    public function productCategory() {
        $categories = Category::all();
        return response()->json([
            'status' => true,
            'categories' => $categories
        ]);
    }

    public function productGraph($year) {
        
        $user = Auth::user();

        $rawData = Sale::join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                            ->join('products', 'sale_items.product_id', '=', 'products.id')
                            ->join('categories', 'products.category_id', '=', 'categories.id')
                            ->select(
                                DB::raw('MONTH(sales.created_at) as month'),
                                'categories.category_name as category_name',
                                DB::raw('SUM(sale_items.quantity) as total')
                            )
                            ->whereYear('sales.created_at', $year)
                            ->where('sales.user_id', $user->id)
                            ->groupBy('month', 'category_name')
                            ->orderBy('month', 'asc')
                            ->get();
        
        $monthlyData = [];
        foreach(range(1, 12) as $month) {
            $monthlyData[$month] = ['month' => Carbon::create()->month($month)->format('M')];
        }

        foreach($rawData as $data) {
            $monthlyData[$data->month][$data->category_name] = $data->total;
        }

        $productGraph = array_values($monthlyData);

        return response()->json([
            'status' => true,
            'graph' => $productGraph
        ]);
                                
    }

    public function addProduct(Request $request) {

        $user = Auth::user();

        $shop_id = Shop_user::where('user_id', $user->id)->first()->shop_id;

        $product = Product::create([
            'shop_id' => $shop_id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'gst' => $request->gst,
            'barcode' => $request->barcode ?? null
        ]);

        Inventory::create([
            'shop_id' => $shop_id,
            'user_id' => $user->id,
            'product_id' => $product->id,
            'stock_qty' => $request->stock,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product added successfully',
            'product' => $product
        ]);
    }

    public function searchProducts(Request $request) {

        $name = $request->query('name');

        $user = Auth::user();
        $shop_id = Shop_user::where('user_id', $user->id)->first()->shop_id;

        $products = Product::where('shop_id', $shop_id)
                            ->when($name, function ($q) use ($name) {$q->where('name', 'like', '%' . $name . '%');})
                            ->with('inventory')
                            ->get();

        return response()->json([
            'status' => true,
            'products' => $products
        ]);
    }

    public function productCategoryFilter($id) {
        $products = Product::where('category_id', $id)->with('inventory')->get();
        return response()->json([
            'status' => true,
            'products' => $products
        ]);
    }
}
