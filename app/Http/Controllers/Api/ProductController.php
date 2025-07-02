<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale_item;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
}
