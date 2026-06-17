<?php

namespace App\Services\Chat;

use App\Models\Category;
use App\Models\Product;
use App\Services\SearchParser;

class ProductFinder
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function find(string $message, int $limit = 3): array
    {
        $messageLower = mb_strtolower($message);

        if ($this->isAccessoryQuery($messageLower)) {
            $accessoryResults = $this->findAccessoryProducts($message, $limit);

            if (! empty($accessoryResults)) {
                return $accessoryResults;
            }
        }

        $exactResults = $this->findExactProducts($messageLower, $limit);

        if (! empty($exactResults)) {
            return $exactResults;
        }

        $parsed = SearchParser::parse($message);
        $query = Product::query()->with(['images', 'category'])->active();

        $filters = $parsed['filters'] ?? [];
        if (! empty($filters)) {
            $query->filter($filters);
        }

        $query = $this->applyPhoneCategoryScope($query, $filters, $message);

        $keyword = trim((string) ($parsed['keyword'] ?? ''));
        if ($keyword !== '') {
            $query->search($keyword);
        }

        $results = $query
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(function (Product $product): array {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                    'brand' => $product->brand,
                    'url' => '/products/' . $product->id,
                    'image' => optional($product->images->first())->image_url,
                    'ram' => $product->ram,
                    'storage' => $product->storage,
                ];
            })
            ->all();

        if (! empty($results)) {
            return $results;
        }

        return $this->fallbackFindByTokens($message, $limit);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function findExactProducts(string $messageLower, int $limit = 3): array
    {
        $tokens = $this->extractTokens($messageLower);

        if ($tokens === []) {
            return [];
        }

        $phrase = implode(' ', $tokens);
        $specificModelQuery = $this->isSpecificModelQuery($messageLower);

        $query = Product::query()->with(['images', 'category'])->active();

        $query = $this->applyPhoneCategoryScope($query, ['brand' => $this->detectBrandFromMessage($messageLower)], $messageLower);

        $query->where(function ($builder) use ($tokens) {
            foreach ($tokens as $token) {
                $builder->orWhere('name', 'like', '%' . $token . '%')
                    ->orWhere('brand', 'like', '%' . $token . '%')
                    ->orWhere('description', 'like', '%' . $token . '%');
            }
        });

        $results = $this->rankProducts($query->get()->all(), $tokens, $phrase, $specificModelQuery ? 1 : $limit);

        if (! empty($results)) {
            return $results;
        }

        return [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fallbackFindByTokens(string $message, int $limit = 3): array
    {
        if ($this->isAccessoryQuery($message)) {
            $accessoryResults = $this->findAccessoryProducts($message, $limit);

            if (! empty($accessoryResults)) {
                return $accessoryResults;
            }
        }

        $tokens = preg_split('/\s+/', trim(mb_strtolower($message))) ?: [];
        $tokens = array_values(array_filter(array_map(static function (string $token): string {
            return trim(preg_replace('/[^\p{L}\p{N}]+/u', '', $token) ?? '');
        }, $tokens)));

        $tokens = array_values(array_filter($tokens, static function (string $token): bool {
            return mb_strlen($token) >= 3 && ! in_array($token, ['toi', 'muon', 'xem', 'cho', 'minh', 'con', 'va', 'the'], true);
        }));

        if ($tokens === []) {
            return [];
        }

        $query = Product::query()->with(['images', 'category'])->active();
        $query = $this->applyPhoneCategoryScope($query, ['brand' => $this->detectBrandFromMessage($message)], $message);
        $query->where(function ($builder) use ($tokens) {
            foreach ($tokens as $token) {
                $builder->orWhere('name', 'like', '%' . $token . '%')
                    ->orWhere('brand', 'like', '%' . $token . '%')
                    ->orWhere('description', 'like', '%' . $token . '%');
            }
        });

        $specificModelQuery = $this->isSpecificModelQuery($message);

        return $this->rankProducts($query->get()->all(), $tokens, implode(' ', $tokens), $specificModelQuery ? 1 : $limit);
    }

    /**
     * @param array<int, Product> $products
     * @return array<int, array<string, mixed>>
     */
    private function formatProducts(array $products): array
    {
        return array_map(function (Product $product): array {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'brand' => $product->brand,
                'url' => '/products/' . $product->id,
                'image' => optional($product->images->first())->image_url,
                'ram' => $product->ram,
                'storage' => $product->storage,
            ];
        }, $products);
    }

    /**
     * @param array<int, Product> $products
     * @param array<int, string> $tokens
     * @return array<int, array<string, mixed>>
     */
    private function rankProducts(array $products, array $tokens, string $phrase, int $limit = 3): array
    {
        usort($products, function (Product $left, Product $right) use ($tokens, $phrase): int {
            $leftScore = $this->scoreProduct($left, $tokens, $phrase);
            $rightScore = $this->scoreProduct($right, $tokens, $phrase);

            if ($leftScore === $rightScore) {
                return $left->id <=> $right->id;
            }

            return $rightScore <=> $leftScore;
        });

        $uniqueProducts = [];
        $seenNames = [];

        foreach ($products as $product) {
            $nameKey = mb_strtolower((string) $product->name);

            if (isset($seenNames[$nameKey])) {
                continue;
            }

            $seenNames[$nameKey] = true;
            $uniqueProducts[] = $product;

            if (count($uniqueProducts) >= $limit) {
                break;
            }
        }

        return $this->formatProducts($uniqueProducts);
    }

    private function scoreProduct(Product $product, array $tokens, string $phrase): int
    {
        $name = mb_strtolower((string) $product->name);
        $brand = mb_strtolower((string) $product->brand);
        $description = mb_strtolower((string) $product->description);

        $score = 0;

        if ($phrase !== '' && $name === $phrase) {
            $score += 5000;
        }

        if ($phrase !== '' && str_contains($name, $phrase)) {
            $score += 2000;
        }

        foreach ($tokens as $token) {
            if ($token !== '' && str_contains($name, $token)) {
                $score += 120;
            }

            if ($token !== '' && str_contains($brand, $token)) {
                $score += 40;
            }

            if ($token !== '' && str_contains($description, $token)) {
                $score += 20;
            }
        }

        return $score;
    }

    /**
     * @return array<int, string>
     */
    private function extractTokens(string $text): array
    {
        $normalized = preg_replace('/\s+/', ' ', trim(mb_strtolower($text))) ?? '';
        $normalized = preg_replace('/\b(cho|toi|muon|xem|va|voi|duoi|dưới|trieu|triệu|gia|re|mua|ban|san pham|sanpham|cua|hang|hop|nhe|giup)\b/u', ' ', $normalized) ?? $normalized;
        $parts = preg_split('/\s+/', trim(preg_replace('/\s+/', ' ', $normalized) ?? '')) ?: [];

        return array_values(array_filter(array_map(static function (string $token): string {
            return trim(preg_replace('/[^\p{L}\p{N}]+/u', '', $token) ?? '');
        }, $parts), static function (string $token): bool {
            return mb_strlen($token) >= 2;
        }));
    }

    private function isSpecificModelQuery(string $text): bool
    {
        $text = mb_strtolower($text);

        if (preg_match('/\b\d{2,4}\b/u', $text)) {
            return true;
        }

        foreach (['pro max', 'pro', 'max', 'ultra', 'plus', 'mini', 'se', 'fold', 'flip', 'note'] as $needle) {
            if (str_contains($text, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed> $filters
     */
    private function applyPhoneCategoryScope($query, array $filters, string $message)
    {
        $brand = trim((string) ($filters['brand'] ?? ''));

        $brandToCategory = [
            'Apple' => ['iPhone'],
            'Samsung' => ['Samsung'],
            'Xiaomi' => ['Xiaomi'],
            'Oppo' => ['OPPO'],
            'Vivo' => ['Vivo'],
            'Realme' => ['Realme'],
            'Huawei' => ['Huawei'],
            'Oneplus' => ['OnePlus'],
        ];

        if ($brand !== '' && isset($brandToCategory[$brand])) {
            $query->whereHas('category', function ($categoryQuery) use ($brandToCategory, $brand) {
                $categoryQuery->whereIn('name', $brandToCategory[$brand]);
            });
        }

        if ($this->looksLikePhoneQuery($message)) {
            $accessoryCategories = ['Ốp lưng', 'Cáp sạc', 'Tai nghe', 'Sạc dự phòng', 'Miếng dán màn hình', 'Giá đỡ điện thoại'];
            $query->whereHas('category', function ($categoryQuery) use ($accessoryCategories) {
                $categoryQuery->whereNotIn('name', $accessoryCategories);
            });
        }

        return $query;
    }

    /**
     * @param array<string, mixed> $filters
     */
    private function applyAccessoryCategoryScope($query, array $filters)
    {
        $accessoryCategories = ['Ốp lưng', 'Cáp sạc', 'Tai nghe', 'Sạc dự phòng', 'Miếng dán màn hình', 'Giá đỡ điện thoại'];

        $query->whereHas('category', function ($categoryQuery) use ($accessoryCategories) {
            $categoryQuery->whereIn('name', $accessoryCategories);
        });

        $brand = trim((string) ($filters['brand'] ?? ''));

        if ($brand !== '') {
            $query->where(function ($builder) use ($brand) {
                $builder->where('name', 'like', '%' . $brand . '%')
                    ->orWhere('brand', 'like', '%' . $brand . '%')
                    ->orWhere('description', 'like', '%' . $brand . '%');
            });
        }

        return $query;
    }

    private function findAccessoryProducts(string $message, int $limit = 3): array
    {
        $tokens = $this->extractTokens($message);

        $query = Product::query()->with(['images', 'category'])->active();
        $query = $this->applyAccessoryCategoryScope($query, []);

        if ($tokens !== []) {
            $query->where(function ($builder) use ($tokens) {
                foreach ($tokens as $token) {
                    $builder->orWhere('name', 'like', '%' . $token . '%')
                        ->orWhere('brand', 'like', '%' . $token . '%')
                        ->orWhere('description', 'like', '%' . $token . '%');
                }
            });
        }

        return $this->rankProducts(
            $query->get()->all(),
            $tokens,
            implode(' ', $tokens),
            $limit
        );
    }

    private function looksLikePhoneQuery(string $message): bool
    {
        $message = mb_strtolower($message);

        return str_contains($message, 'điện thoại')
            || str_contains($message, 'iphone')
            || str_contains($message, 'samsung')
            || str_contains($message, 'xiaomi')
            || str_contains($message, 'oppo')
            || str_contains($message, 'vivo')
            || str_contains($message, 'realme')
            || str_contains($message, 'huawei')
            || str_contains($message, 'oneplus');
    }

    private function detectBrandFromMessage(string $message): string
    {
        $message = mb_strtolower($message);

        foreach ([
            'Apple' => ['iphone', 'apple'],
            'Samsung' => ['samsung'],
            'Xiaomi' => ['xiaomi'],
            'Oppo' => ['oppo'],
            'Vivo' => ['vivo'],
            'Realme' => ['realme'],
            'Huawei' => ['huawei'],
            'Oneplus' => ['oneplus'],
        ] as $brand => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($message, $needle)) {
                    return $brand;
                }
            }
        }

        return '';
    }

    private function isAccessoryQuery(string $message): bool
    {
        $message = mb_strtolower($message);

        return $this->containsAny($message, [
            'cáp sạc',
            'cap sac',
            'sạc',
            'sac',
            'cable',
            'adapter',
            'tai nghe',
            'ốp lưng',
            'op lung',
            'case',
            'bao da',
            'miếng dán',
            'mieng dan',
            'giá đỡ',
            'gia do',
            'pin sạc',
            'pin sac',
        ]);
    }

    private function containsAny(string $message, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($message, $needle)) {
                return true;
            }
        }

        return false;
    }
}