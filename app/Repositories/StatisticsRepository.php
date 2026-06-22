<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatisticsRepository
{
    public function getTotalRevenue(string $startDate, string $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'shipping', 'completed'])
            ->sum('total_amount');
    }

    public function getTotalOrders(string $startDate, string $endDate): int
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    public function getTotalUsers(): int
    {
        return User::where('role', 'user')->count();
    }

    public function getNewUsers(string $startDate, string $endDate): int
    {
        return User::whereBetween('created_at', [$startDate, $endDate])
            ->where('role', 'user')
            ->count();
    }

    public function getBestSellingProducts(string $startDate, string $endDate, int $limit = 10)
    {
        return Product::select(
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
            ->limit($limit)
            ->get();
    }

    public function getRevenueByDay(string $startDate, string $endDate)
    {
        return Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'shipping', 'completed'])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }

    public function getOrderStatusStats(string $startDate, string $endDate)
    {
        return Order::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();
    }

    public function getRevenueQuery(string $startDate, string $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['paid', 'shipping', 'completed']);
    }

    public function getProductReport(string $startDate, string $endDate)
    {
        return Product::select(
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
    }

    public function getCategoryReport(string $startDate, string $endDate)
    {
        return DB::table('categories')
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
    }

    public function getDashboardOverallStats(): array
    {
        return [
            'total_orders' => Order::count(),
            'total_revenue' => Order::whereIn('status', ['paid', 'confirmed', 'processing', 'shipping', 'completed'])->sum('total_amount'),
            'total_users' => User::where('role', 'user')->count(),
            'total_products' => Product::count(),
        ];
    }

    public function getMonthlyRevenueByYear(string $year)
    {
        return Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereYear('created_at', $year)
            ->whereIn('status', ['paid', 'confirmed', 'processing', 'shipping', 'completed'])
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');
    }

    public function getOrderStatusCounts(): array
    {
        return Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
    }
}
