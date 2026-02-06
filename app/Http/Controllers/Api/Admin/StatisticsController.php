<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function dashboard(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        // Total revenue
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'shipping', 'completed'])
            ->sum('total_amount');

        // Total orders
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        // Total users
        $totalUsers = User::where('role', 'user')->count();

        // New users
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])
            ->where('role', 'user')
            ->count();

        // Best selling products
        $bestSellingProducts = Product::select(
                'products.id',
                'products.name',
                'products.brand',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->whereIn('orders.status', ['paid', 'shipping', 'completed'])
            ->groupBy('products.id', 'products.name', 'products.brand', 'products.price')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        // Revenue by day
        $revenueByDay = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'shipping', 'completed'])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Order status statistics
        $orderStats = Order::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_revenue' => $totalRevenue,
                    'total_orders' => $totalOrders,
                    'total_users' => $totalUsers,
                    'new_users' => $newUsers,
                ],
                'best_selling_products' => $bestSellingProducts,
                'revenue_by_day' => $revenueByDay,
                'order_statistics' => $orderStats,
            ]
        ]);
    }

    public function revenueReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        $groupBy = $request->get('group_by', 'day'); // day, week, month

        $query = Order::select(
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'shipping', 'completed']);

        switch ($groupBy) {
            case 'week':
                $query->select(DB::raw('YEARWEEK(created_at) as period'))
                    ->groupBy('period');
                break;
            case 'month':
                $query->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as period'))
                    ->groupBy('period');
                break;
            default: // day
                $query->select(DB::raw('DATE(created_at) as period'))
                    ->groupBy('period');
        }

        $data = $query->orderBy('period', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function productReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $products = Product::select(
                'products.id',
                'products.name',
                'products.brand',
                'products.price',
                'products.quantity',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_revenue')
            )
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function($join) use ($startDate, $endDate) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->whereBetween('orders.created_at', [$startDate, $endDate])
                     ->whereIn('orders.status', ['paid', 'shipping', 'completed']);
            })
            ->groupBy('products.id', 'products.name', 'products.brand', 'products.price', 'products.quantity')
            ->orderBy('total_sold', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function categoryReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $categories = DB::table('categories')
            ->select(
                'categories.id',
                'categories.name',
                'categories.description',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_revenue')
            )
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function($join) use ($startDate, $endDate) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->whereBetween('orders.created_at', [$startDate, $endDate])
                     ->whereIn('orders.status', ['paid', 'shipping', 'completed']);
            })
            ->groupBy('categories.id', 'categories.name', 'categories.description')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Dashboard stats for admin dashboard page
     */
    public function dashboardStats()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::whereIn('status', ['paid', 'confirmed', 'processing', 'shipping', 'completed'])->sum('total_amount');
        $totalUsers = User::where('role', 'user')->count();
        $totalProducts = Product::count();

        return response()->json([
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'total_users' => $totalUsers,
            'total_products' => $totalProducts
        ]);
    }

    /**
     * Revenue by month for chart
     */
    public function revenueByMonth(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        $monthlyRevenue = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereYear('created_at', $year)
            ->whereIn('status', ['paid', 'confirmed', 'processing', 'shipping', 'completed'])
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');

        $labels = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
        $values = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $values[] = $monthlyRevenue->get($i)?->revenue ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }

    /**
     * Orders status for pie chart
     */
    public function ordersStatus()
    {
        $statusCounts = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return response()->json([
            'pending' => $statusCounts['pending'] ?? 0,
            'confirmed' => $statusCounts['confirmed'] ?? 0,
            'processing' => $statusCounts['processing'] ?? 0,
            'shipping' => $statusCounts['shipping'] ?? 0,
            'completed' => $statusCounts['completed'] ?? 0,
            'cancelled' => $statusCounts['cancelled'] ?? 0
        ]);
    }
}
