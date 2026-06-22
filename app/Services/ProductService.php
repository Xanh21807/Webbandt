<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Services\SearchParser;
use App\Models\UserBehavior;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts(array $params, int $perPage = 15)
    {
        $query = $this->productRepository->buildActiveProductsQuery();

        $filters = [];

        // Search with parser
        $searchTerm = $params['search'] ?? $params['keyword'] ?? null;
        if ($searchTerm) {
            $parsed = SearchParser::parse($searchTerm);
            if (!empty($parsed['filters'])) {
                $filters = array_merge($filters, $parsed['filters']);
            }
            if (!empty($parsed['keyword'])) {
                $query->search($parsed['keyword']);
            }
        }

        // Direct filters
        $directFilterKeys = ['brand', 'ram', 'storage', 'min_price', 'max_price', 'category_id', 'price_range'];
        foreach ($directFilterKeys as $key) {
            if (!empty($params[$key])) {
                $filters[$key] = $params[$key];
            }
        }

        // Handle category name filter if no category_id
        if (isset($params['category']) && !isset($filters['category_id'])) {
            $categoryVal = $params['category'];
            if (is_numeric($categoryVal)) {
                $filters['category_id'] = (int) $categoryVal;
            } else {
                $category = $this->productRepository->findCategoryByName($categoryVal);
                if ($category) {
                    $filters['category_id'] = $category->id;
                }
            }
        }

        $query->filter($filters);

        // Sort
        $sortBy = $params['sort_by'] ?? $params['sort'] ?? 'newest';
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

        return $query->paginate($perPage);
    }

    public function getFilterOptions(): array
    {
        return [
            'rams' => $this->productRepository->getDistinctColumnValues('ram'),
            'storages' => $this->productRepository->getDistinctColumnValues('storage'),
            'brands' => $this->productRepository->getDistinctColumnValues('brand'),
            'price_ranges' => [
                ['value' => 'under_5', 'label' => 'Dưới 5 triệu'],
                ['value' => '5_10', 'label' => '5 - 10 triệu'],
                ['value' => '10_20', 'label' => '10 - 20 triệu'],
                ['value' => '20_30', 'label' => '20 - 30 triệu'],
                ['value' => 'over_30', 'label' => 'Trên 30 triệu'],
            ]
        ];
    }

    public function getProductDetails(int $id, $user = null)
    {
        $product = $this->productRepository->findWithRelations($id);

        if (!$product) {
            throw new Exception('Không thể tải thông tin sản phẩm. Vui lòng thử lại sau.', 404);
        }

        if ($user) {
            UserBehavior::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'type' => 'view',
            ]);
        }

        return $product;
    }

    public function getAccessories(int $id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new Exception('Product not found', 404);
        }

        $accessoryNames = ['Ốp lưng','Cáp sạc','Tai nghe','Sạc dự phòng','Miếng dán màn hình','Giá đỡ điện thoại'];
        $accessoryCategoryIds = $this->productRepository->getCategoryIdsByNames($accessoryNames);

        if (empty($accessoryCategoryIds)) {
            return collect();
        }

        $baseQuery = $this->productRepository->getAccessoriesQuery($accessoryCategoryIds, $product->id);

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

        return $result;
    }

    public function getCombos(int $id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new Exception('Product not found', 404);
        }

        return $this->productRepository->getCombos($product->id);
    }

    public function addToFavorites($user, int $productId)
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new Exception('Sản phẩm không tồn tại', 404);
        }

        $favorite = $this->productRepository->findFavorite($user->id, $productId);
        if ($favorite) {
            throw new Exception('Sản phẩm đã có trong danh sách yêu thích', 409);
        }

        return $this->productRepository->addFavorite($user->id, $productId);
    }

    public function removeFromFavorites($user, int $productId)
    {
        $favorite = $this->productRepository->findFavorite($user->id, $productId);
        if (!$favorite) {
            throw new Exception('Sản phẩm không có trong danh sách yêu thích', 404);
        }

        return $favorite->delete();
    }

    public function getFavorites($user)
    {
        return $this->productRepository->getFavorites($user->id);
    }

    // Admin APIs
    public function getAdminProducts(array $params, int $perPage = 15)
    {
        $query = $this->productRepository->buildAdminProductsQuery();

        if (!empty($params['keyword'])) {
            $query->search($params['keyword']);
        }

        if (!empty($params['category_id'])) {
            $query->where('category_id', $params['category_id']);
        }

        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (!empty($params['stock'])) {
            switch ($params['stock']) {
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

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getAdminProduct(int $id)
    {
        $product = $this->productRepository->findWithRelations($id, ['category', 'images']);
        if (!$product) {
            throw new Exception('Sản phẩm không tồn tại', 404);
        }
        return $product;
    }

    public function createProduct(array $data, ?array $images)
    {
        return DB::transaction(function () use ($data, $images) {
            $product = $this->productRepository->create($data);

            if (!empty($images)) {
                foreach ($images as $image) {
                    $path = $image->store('products', 'public');
                    $this->productRepository->createProductImage([
                        'product_id' => $product->id,
                        'image_url'  => $path,
                    ]);
                }
            }

            return $product->load('images');
        });
    }

    public function updateProduct(int $id, array $data, ?array $images)
    {
        return DB::transaction(function () use ($id, $data, $images) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                throw new Exception('Sản phẩm không tồn tại', 404);
            }

            $this->productRepository->update($product, $data);

            if (!empty($images)) {
                foreach ($images as $image) {
                    $path = $image->store('products', 'public');
                    $this->productRepository->createProductImage([
                        'product_id' => $product->id,
                        'image_url'  => $path,
                    ]);
                }
            }

            return $product->load('images');
        });
    }

    public function deleteProduct(int $id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new Exception('Sản phẩm không tồn tại', 404);
        }

        // Delete images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_url);
        }

        return $this->productRepository->delete($product);
    }

    public function getTopProducts(int $limit = 5)
    {
        return $this->productRepository->getTopProducts($limit);
    }

    // Categories logic (Admin)
    public function getCategories(array $params, int $perPage = 15)
    {
        $query = \App\Models\Category::withCount('products');

        if (!empty($params['keyword'])) {
            $query->where('name', 'like', "%{$params['keyword']}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getCategory(int $id)
    {
        $category = \App\Models\Category::withCount('products')->find($id);
        if (!$category) {
            throw new Exception('Danh mục không tồn tại', 404);
        }
        return $category;
    }

    public function createCategory(array $data)
    {
        return $this->productRepository->createCategory($data);
    }

    public function updateCategory(int $id, array $data)
    {
        $category = $this->productRepository->findCategory($id);
        if (!$category) {
            throw new Exception('Danh mục không tồn tại', 404);
        }
        $this->productRepository->updateCategory($category, $data);
        return $category;
    }

    public function deleteCategory(int $id)
    {
        $category = \App\Models\Category::withCount('products')->find($id);
        if (!$category) {
            throw new Exception('Danh mục không tồn tại', 404);
        }

        if ($category->products_count > 0) {
            throw new Exception('Danh mục đang chứa sản phẩm. Không thể xóa.', 400);
        }

        return $this->productRepository->deleteCategory($category);
    }
}
