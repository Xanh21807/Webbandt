<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserBehavior;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    // GET /api/recommendations
    public function index(Request $request)
    {
        $user = $request->user();

        // Require login
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get user's recent behaviors (last 90 days)
        $behaviors = UserBehavior::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(90))
            ->get();

        if ($behaviors->isEmpty()) {
            // Fallback: top-selling products (join order_items)
            $top = Product::select('products.*', DB::raw('COALESCE(SUM(order_items.quantity),0) as sales_count'))
                ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
                ->groupBy('products.id')
                ->orderByDesc('sales_count')
                ->limit(12)
                ->get();

            return response()->json(['data' => $top]);
        }

        // Count product views by category
        $categoryCounts = [];

        $productIds = $behaviors->pluck('product_id')->filter()->unique()->toArray();

        $products = Product::whereIn('id', $productIds)->get();

        foreach ($products as $p) {
            $cat = $p->category_id ?: 0;
            $categoryCounts[$cat] = ($categoryCounts[$cat] ?? 0) + 1;
        }

        arsort($categoryCounts);
        $topCategories = array_keys(array_slice($categoryCounts, 0, 3, true));

        // Recommend top products from those categories excluding already-viewed
        $recommended = Product::select('products.*', DB::raw('COALESCE(SUM(order_items.quantity),0) as sales_count'))
            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
            ->whereIn('category_id', $topCategories)
            ->whereNotIn('products.id', $productIds)
            ->groupBy('products.id')
            ->orderByDesc('sales_count')
            ->limit(12)
            ->get();

        // If not enough recommendations, fill with top-selling
        if ($recommended->count() < 6) {
            $fill = Product::select('products.*', DB::raw('COALESCE(SUM(order_items.quantity),0) as sales_count'))
                ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
                ->whereNotIn('products.id', $productIds)
                ->groupBy('products.id')
                ->orderByDesc('sales_count')
                ->limit(12 - $recommended->count())
                ->get();

            $recommended = $recommended->merge($fill);
        }

        return response()->json(['data' => $recommended]);
    }
}
