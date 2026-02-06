<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        // Search by keyword
        if ($request->has('keyword') && $request->keyword) {
            $query->search($request->keyword);
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by stock level
        if ($request->has('stock') && $request->stock) {
            switch ($request->stock) {
                case 'in_stock':
                    $query->where('quantity', '>', 10);
                    break;
                case 'low_stock':
                    $query->where('quantity', '>', 0)->where('quantity', '<=', 10);
                    break;
                case 'out_of_stock':
                    $query->where('quantity', '<=', 0);
                    break;
            }
        }

        $products = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'images'])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'battery' => 'nullable|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $product = Product::create($request->except('images'));

            // Handle images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thêm sản phẩm thành công',
                'data' => [
                    'product' => $product->load('images')
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'brand' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'battery' => 'nullable|string',
            'description' => 'nullable|string',
            'quantity' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:active,inactive',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $product->update($request->except('images'));

            // Handle images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật sản phẩm thành công',
                'data' => [
                    'product' => $product->load('images')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        try {
            // Delete product images from storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_url);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa sản phẩm thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get top selling products for dashboard
     */
    public function topProducts()
    {
        $topProducts = Product::select(
                'products.id',
                'products.name',
                'products.price',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_revenue')
            )
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->whereIn('orders.status', ['paid', 'confirmed', 'processing', 'shipping', 'completed']);
            })
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $topProducts
        ]);
    }
}
