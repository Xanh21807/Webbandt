<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::with('items.product.images')
            ->firstOrCreate(['user_id' => $request->user()->id]);

        // Format items để frontend dễ sử dụng
        $items = $cart->items->filter(function ($item) {
            return $item->product !== null; // Bỏ qua item có product đã bị xóa
        })->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'name' => $item->product->name ?? 'Sản phẩm không tồn tại',
                'price' => $item->product->price ?? 0,
                'image' => $item->product->images->first()?->image_url ?? null,
                'product' => $item->product,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $items,
            'total' => $cart->total ?? 0,
            'cart' => $cart
        ]);
    }

    public function addItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::find($request->product_id);

        if ($product->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm không đủ'
            ], 400);
        }

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng'
        ]);
    }

    public function updateItem(Request $request, $itemId)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $cartItem = CartItem::whereHas('cart', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->find($itemId);

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có trong giỏ hàng'
            ], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật giỏ hàng'
        ]);
    }

    public function removeItem(Request $request, $itemId)
    {
        $cartItem = CartItem::whereHas('cart', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->find($itemId);

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có trong giỏ hàng'
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
        ]);
    }

    public function clear(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();

        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng'
        ]);
    }
}
