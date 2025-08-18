<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function getOrdersChartData(Request $request)
{
    $period = $request->input('period', 'monthly'); // default monthly

    // Gabungkan orders + payments dan orders_vip + payments_vip
    $baseQuery = DB::table(DB::raw("(
        SELECT o.order_date, o.total
        FROM orders o
        INNER JOIN payments p ON o.id = p.order_id
        WHERE o.order_status = 'completed' AND o.order_date IS NOT NULL AND o.total IS NOT NULL AND p.method IS NOT NULL

        UNION ALL

        SELECT ov.order_date, ov.total
        FROM orders_vip ov
        INNER JOIN payments_vip pv ON ov.id = pv.order_id
        WHERE ov.order_status = 'completed' AND ov.order_date IS NOT NULL AND ov.total IS NOT NULL AND pv.method IS NOT NULL
    ) as combined_orders"));

    // Pilih dan grup berdasarkan period
    switch ($period) {
        case 'daily':
            $baseQuery->selectRaw('DATE(order_date) as period_label, SUM(total) as total')
                ->groupBy('period_label')
                ->orderBy('period_label');
            break;

        case 'weekly':
            $baseQuery->selectRaw('YEAR(order_date) as year, WEEK(order_date, 1) as week, SUM(total) as total')
                ->groupBy('year', 'week')
                ->orderBy('year')
                ->orderBy('week');
            break;

        case 'monthly':
            $baseQuery->selectRaw('DATE_FORMAT(order_date, "%Y-%m") as period_label, SUM(total) as total')
                ->groupBy('period_label')
                ->orderBy('period_label');
            break;

        case 'yearly':
            $baseQuery->selectRaw('YEAR(order_date) as period_label, SUM(total) as total')
                ->groupBy('period_label')
                ->orderBy('period_label');
            break;

        default:
            $baseQuery->selectRaw('DATE_FORMAT(order_date, "%Y-%m") as period_label, SUM(total) as total')
                ->groupBy('period_label')
                ->orderBy('period_label');
            break;
    }

    $results = $baseQuery->get();

    // Format hasil ke bentuk chart
    $seriesData = [];

    if ($period === 'weekly') {
        foreach ($results as $row) {
            $label = "Week {$row->week}, {$row->year}";
            $seriesData[] = ['x' => $label, 'y' => (float) $row->total];
        }
    } else {
        foreach ($results as $row) {
            $seriesData[] = ['x' => $row->period_label, 'y' => (float) $row->total];
        }
    }

    return response()->json([
        'series' => [
            [
                'name' => 'Total Orders with Payment',
                'data' => $seriesData,
            ]
        ]
    ]);
}

}
