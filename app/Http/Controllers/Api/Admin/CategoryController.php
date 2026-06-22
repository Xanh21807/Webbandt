<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $categories = $this->productService->getCategories($request->all(), $request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function show($id)
    {
        try {
            $category = $this->productService->getCategory((int)$id);
            return response()->json([
                'success' => true,
                'data' => [
                    'category' => $category
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
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục trùng tên. Vui lòng nhập tên khác.',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = $this->productService->createCategory($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Thêm danh mục thành công',
            'data' => [
                'category' => $category
            ]
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục trùng tên. Vui lòng nhập tên khác.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = $this->productService->updateCategory((int)$id, $request->all());
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật danh mục thành công',
                'data' => [
                    'category' => $category
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

    public function destroy($id)
    {
        try {
            $this->productService->deleteCategory((int)$id);
            return response()->json([
                'success' => true,
                'message' => 'Xóa danh mục thành công'
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $code);
        }
    }
}
