<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->getProducts($request->all(), $request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function filterOptions()
    {
        $options = $this->productService->getFilterOptions();

        return response()->json([
            'success' => true,
            'data' => $options
        ]);
    }

    public function show(Request $request, $id)
    {
        try {
            $product = $this->productService->getProductDetails((int)$id, $request->user());

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

    public function accessories(Request $request, $id)
    {
        try {
            $accessories = $this->productService->getAccessories((int)$id);
            return response()->json([
                'success' => true,
                'data' => $accessories
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function combos(Request $request, $id)
    {
        try {
            $combos = $this->productService->getCombos((int)$id);
            return response()->json([
                'success' => true,
                'data' => $combos
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'data' => []
            ], $code);
        }
    }

    public function addToFavorites(Request $request, $productId)
    {
        try {
            $this->productService->addToFavorites($request->user(), (int)$productId);
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích'
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function removeFromFavorites(Request $request, $productId)
    {
        try {
            $this->productService->removeFromFavorites($request->user(), (int)$productId);
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa khỏi danh sách yêu thích'
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function favorites(Request $request)
    {
        $favorites = $this->productService->getFavorites($request->user());

        return response()->json([
            'success' => true,
            'data' => [
                'favorites' => $favorites
            ]
        ]);
    }
}
