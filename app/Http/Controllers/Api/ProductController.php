<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'reviews'])
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'rating')
            ->active();
        // Filter - lấy tất cả các filter từ request
        $filters = [];

        // Search - hỗ trợ cả 'search' và 'keyword'
        $searchTerm = $request->input('search') ?? $request->input('keyword');
        if ($searchTerm) {
            // parse natural language into filters + keyword
            $parsed = \App\Services\SearchParser::parse($searchTerm);
            if (!empty($parsed['filters'])) {
                $filters = array_merge($filters, $parsed['filters']);
            }
            if (!empty($parsed['keyword'])) {
                $query->search($parsed['keyword']);
            }
        }
        
        if ($request->filled('brand')) {
            $filters['brand'] = $request->input('brand');
        }
        if ($request->filled('ram')) {
            $filters['ram'] = $request->input('ram');
        }
        if ($request->filled('storage')) {
            $filters['storage'] = $request->input('storage');
        }
        if ($request->filled('min_price')) {
            $filters['min_price'] = $request->input('min_price');
        }
        if ($request->filled('max_price')) {
            $filters['max_price'] = $request->input('max_price');
        }
        if ($request->filled('category_id')) {
            $filters['category_id'] = $request->input('category_id');
        }
        if ($request->filled('category') && ! $request->filled('category_id')) {
            $categoryValue = $request->input('category');

            if (is_numeric($categoryValue)) {
                $filters['category_id'] = (int) $categoryValue;
            } else {
                $category = \App\Models\Category::where('name', 'like', '%' . $categoryValue . '%')->first();

                if ($category) {
                    $filters['category_id'] = $category->id;
                }
            }
        }
        if ($request->filled('price_range')) {
            $filters['price_range'] = $request->input('price_range');
        }
        
        $query->filter($filters);

        // Sort: accept both `sort_by` and legacy `sort`
        $sortBy = $request->input('sort_by', $request->input('sort', 'newest'));

        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'bestselling':
                $query->withCount('orderItems')
                    ->orderBy('order_items_count', 'desc')
                    ->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Lấy danh sách các giá trị filter có sẵn
     */
    public function filterOptions()
    {
        $rams = Product::active()
            ->whereNotNull('ram')
            ->distinct()
            ->pluck('ram')
            ->sort()
            ->values();

        $storages = Product::active()
            ->whereNotNull('storage')
            ->distinct()
            ->pluck('storage')
            ->sort()
            ->values();

        $brands = Product::active()
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        $priceRanges = [
            ['value' => 'under_5', 'label' => 'Dưới 5 triệu'],
            ['value' => '5_10', 'label' => '5 - 10 triệu'],
            ['value' => '10_20', 'label' => '10 - 20 triệu'],
            ['value' => '20_30', 'label' => '20 - 30 triệu'],
            ['value' => 'over_30', 'label' => 'Trên 30 triệu'],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'rams' => $rams,
                'storages' => $storages,
                'brands' => $brands,
                'price_ranges' => $priceRanges,
            ]
        ]);
    }

    public function show(Request $request, $id)
    {
        $product = Product::with(['category', 'images', 'reviews.user'])
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'rating')
            ->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông tin sản phẩm. Vui lòng thử lại sau.'
            ], 404);
        }

        // record view behavior for authenticated users
        if ($request->user()) {
            \App\Models\UserBehavior::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'type' => 'view',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product
            ]
        ]);
    }

    /**
     * Return accessory suggestions for a given product
     */
    public function accessories(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            $accessoryNames = ['Ốp lưng','Cáp sạc','Tai nghe','Sạc dự phòng','Miếng dán màn hình','Giá đỡ điện thoại'];
            $accessoryCategoryIds = \App\Models\Category::whereIn('name', $accessoryNames)->pluck('id')->toArray();

            if (empty($accessoryCategoryIds)) {
                return response()->json(['success' => true, 'data' => []]);
            }

            // Use relationship count to avoid GROUP BY raw queries
            $baseQuery = Product::with('images')
                ->withCount(['orderItems as sales_count'])
                ->whereIn('category_id', $accessoryCategoryIds)
                ->where('id', '<>', $product->id)
                ->orderByDesc('sales_count');

            if ($product->brand) {
                $sameBrand = (clone $baseQuery)->where('brand', $product->brand)->limit(6)->get();
                $needed = 6 - $sameBrand->count();
                if ($needed > 0) {
                    $fill = $baseQuery->where('brand', '<>', $product->brand)->limit($needed)->get();
                    $result = $sameBrand->merge($fill);
                } else {
                    $result = $sameBrand;
                }
            } else {
                $result = $baseQuery->limit(6)->get();
            }

            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $ex) {
            Log::error('Error in accessories(): ' . $ex->getMessage(), ['exception' => $ex]);
            return response()->json(['success' => false, 'message' => 'Lỗi server khi lấy phụ kiện'], 500);
        }
    }

    /**
     * Return combos that include this product
     */
    public function combos(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['success' => false, 'data' => []], 404);
            }

            $combos = \App\Models\Combo::with('products')
                ->whereHas('products', function ($q) use ($id) {
                    $q->where('products.id', $id);
                })
                ->get();

            return response()->json(['success' => true, 'data' => $combos]);
        } catch (\Exception $ex) {
            Log::error('Error in combos(): ' . $ex->getMessage(), ['exception' => $ex]);
            return response()->json(['success' => false, 'data' => []], 500);
        }
    }

    public function addToFavorites(Request $request, $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        $favorite = Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm đã có trong danh sách yêu thích'
            ], 409);
        }

        Favorite::create([
            'user_id' => $request->user()->id,
            'product_id' => $productId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào danh sách yêu thích'
        ]);
    }

    public function removeFromFavorites(Request $request, $productId)
    {
        $favorite = Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->first();

        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có trong danh sách yêu thích'
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi danh sách yêu thích'
        ]);
    }

    public function favorites(Request $request)
    {
        $favorites = Favorite::with('product.images')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'favorites' => $favorites
            ]
        ]);
    }
}
