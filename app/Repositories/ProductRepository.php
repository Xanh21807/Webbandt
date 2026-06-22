<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\ProductImage;
use App\Models\Combo;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function find(int $id): ?Product
    {
        return Product::find($id);
    }

    public function findWithRelations(int $id, array $relations = ['category', 'images', 'reviews.user'])
    {
        return Product::with($relations)
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'rating')
            ->find($id);
    }

    public function buildActiveProductsQuery()
    {
        return Product::with(['category', 'images', 'reviews'])
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'rating')
            ->active();
    }

    public function getDistinctColumnValues(string $column)
    {
        return Product::active()
            ->whereNotNull($column)
            ->distinct()
            ->pluck($column)
            ->sort()
            ->values();
    }

    public function findCategoryByName(string $name): ?Category
    {
        return Category::where('name', 'like', '%' . $name . '%')->first();
    }

    public function getCategoryIdsByNames(array $names): array
    {
        return Category::whereIn('name', $names)->pluck('id')->toArray();
    }

    public function getAccessoriesQuery(array $accessoryCategoryIds, int $productId)
    {
        return Product::with('images')
            ->withCount(['orderItems as sales_count'])
            ->whereIn('category_id', $accessoryCategoryIds)
            ->where('id', '<>', $productId)
            ->orderByDesc('sales_count');
    }

    public function getCombos(int $productId)
    {
        return Combo::with('products')
            ->whereHas('products', function ($q) use ($productId) {
                $q->where('products.id', $productId);
            })
            ->get();
    }

    public function findFavorite(int $userId, int $productId): ?Favorite
    {
        return Favorite::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }

    public function addFavorite(int $userId, int $productId): Favorite
    {
        return Favorite::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    public function getFavorites(int $userId)
    {
        return Favorite::with('product.images')
            ->where('user_id', $userId)
            ->get();
    }

    // Admin-specific methods
    public function buildAdminProductsQuery()
    {
        return Product::with(['category', 'images']);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function createProductImage(array $data): ProductImage
    {
        return ProductImage::create($data);
    }

    public function getTopProducts(int $limit = 5)
    {
        $topProducts = Product::select(
                'products.id',
                'products.name',
                'products.price',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_revenue')
            )
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function ($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->whereIn('orders.status', ['paid', 'confirmed', 'processing', 'shipping', 'completed']);
            })
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();

        $topProducts->each(function ($product) {
            $image = ProductImage::where('product_id', $product->id)->first();
            $product->image_url = $image ? $image->image_url : null;
        });

        return $topProducts;
    }

    // Category CRUD helpers for Admin
    public function getCategories(int $perPage = 15)
    {
        return Category::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getAllCategories()
    {
        return Category::orderBy('name', 'asc')->get();
    }

    public function findCategory(int $id): ?Category
    {
        return Category::find($id);
    }

    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    public function deleteCategory(Category $category): bool
    {
        return $category->delete();
    }
}
