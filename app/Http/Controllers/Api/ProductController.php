<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'reviews'])
            ->active();

        // Search - hỗ trợ cả 'search' và 'keyword'
        $searchTerm = $request->input('search') ?? $request->input('keyword');
        if ($searchTerm) {
            $query->search($searchTerm);
        }

        // Filter - lấy tất cả các filter từ request
        $filters = [];
        
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
        if ($request->filled('price_range')) {
            $filters['price_range'] = $request->input('price_range');
        }
        
        $query->filter($filters);

        // Sort
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
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

    public function show($id)
    {
        $product = Product::with(['category', 'images', 'reviews.user'])
            ->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông tin sản phẩm. Vui lòng thử lại sau.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product
            ]
        ]);
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
