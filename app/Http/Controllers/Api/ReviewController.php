<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại',
            ], 404);
        }

        $reviews = Review::with('user')
            ->where('product_id', $productId)
            ->orderByDesc('created_at')
            ->get();

        $summary = $this->buildSummary($reviews);

        return response()->json([
            'success' => true,
            'data' => $reviews,
            'summary' => $summary,
        ]);
    }

    public function store(Request $request, $productId)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:1', 'max:1000'],
        ]);

        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại',
            ], 404);
        }

        if (!$this->hasPurchasedProduct($request->user()->id, $productId)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chỉ có thể đánh giá sản phẩm đã mua',
            ], 403);
        }

        $review = Review::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $productId,
            ],
            [
                'rating' => $data['rating'],
                'comment' => trim($data['comment']),
            ]
        );

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá đã được lưu thành công',
            'data' => [
                'review' => $review,
            ],
        ]);
    }

    public function eligibility(Request $request, $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại',
            ], 404);
        }

        $hasPurchased = $request->user()
            ? $this->hasPurchasedProduct($request->user()->id, $productId)
            : false;

        $hasReviewed = $request->user()
            ? Review::where('user_id', $request->user()->id)->where('product_id', $productId)->exists()
            : false;

        return response()->json([
            'success' => true,
            'data' => [
                'can_review' => $hasPurchased,
                'has_reviewed' => $hasReviewed,
            ],
        ]);
    }

    private function hasPurchasedProduct(int $userId, int $productId): bool
    {
        return OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereIn('status', ['paid', 'shipping', 'completed']);
            })
            ->exists();
    }

    private function buildSummary($reviews): array
    {
        $distribution = [0, 0, 0, 0, 0];
        $total = 0;

        foreach ($reviews as $review) {
            $rating = (int) $review->rating;
            $total += $rating;
            if ($rating >= 1 && $rating <= 5) {
                $distribution[$rating - 1]++;
            }
        }

        $count = $reviews->count();

        return [
            'count' => $count,
            'average' => $count > 0 ? round($total / $count, 1) : 0,
            'distribution' => $distribution,
        ];
    }
}