<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->getAdminProducts($request->all(), $request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show($id)
    {
        try {
            $product = $this->productService->getAdminProduct((int)$id);
            return response()->json([
                'success' => true,
                'data' => [
                    'product' => $product
                ]
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'brand'       => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'ram'         => 'nullable|string',
            'storage'     => 'nullable|string',
            'battery'     => 'nullable|string',
            'description' => 'nullable|string',
            'quantity'    => 'required|integer|min:0',
            'status'      => 'required|in:active,inactive',
            'images'      => 'nullable|array',
            'images.*'    => 'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $product = $this->productService->createProduct(
                $request->except('images'),
                $request->file('images')
            );

            return response()->json([
                'success' => true,
                'message' => 'Thêm sản phẩm thành công',
                'data'    => [
                    'product' => $product
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm sản phẩm',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'name'        => 'sometimes|string|max:255',
            'brand'       => 'sometimes|string|max:255',
            'price'       => 'sometimes|numeric|min:0',
            'ram'         => 'nullable|string',
            'storage'     => 'nullable|string',
            'battery'     => 'nullable|string',
            'description' => 'nullable|string',
            'quantity'    => 'sometimes|integer|min:0',
            'status'      => 'sometimes|in:active,inactive',
            'images'      => 'nullable|array',
            'images.*'    => 'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $product = $this->productService->updateProduct(
                (int)$id,
                $request->except('images'),
                $request->file('images')
            );

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật sản phẩm thành công',
                'data'    => [
                    'product' => $product
                ]
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật sản phẩm',
                'error'   => $e->getMessage()
            ], $code);
        }
    }

    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct((int)$id);
            return response()->json([
                'success' => true,
                'message' => 'Xóa sản phẩm thành công'
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa sản phẩm',
                'error'   => $e->getMessage()
            ], $code);
        }
    }

    public function topProducts()
    {
        $topProducts = $this->productService->getTopProducts();

        return response()->json([
            'success' => true,
            'data'    => $topProducts
        ]);
    }
}
