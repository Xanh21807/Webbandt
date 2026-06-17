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
        $cart = Cart::with('items.product.images')->with('cartCombos.combo.products')->
            firstOrCreate(['user_id' => $request->user()->id]);

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
            'product_id' => 'nullable|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'combo_id' => 'nullable|exists:combos,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        // If combo_id provided, add all combo products and record cart_combo
        if ($request->filled('combo_id')) {
            $combo = \App\Models\Combo::with('products')->find($request->combo_id);
            if (!$combo) {
                return response()->json(['success' => false, 'message' => 'Combo không tồn tại'], 404);
            }

            $comboQty = max(1, (int) $request->input('quantity', 1));

            // check stock for each product
            foreach ($combo->products as $p) {
                $need = ($p->pivot->quantity ?? 1) * $comboQty;
                if ($p->quantity < $need) {
                    return response()->json(['success' => false, 'message' => "Sản phẩm {$p->name} không đủ số lượng"], 400);
                }
            }

            // create or increment cart items
            foreach ($combo->products as $p) {
                $need = ($p->pivot->quantity ?? 1) * $comboQty;
                $cartItem = CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $p->id)
                    ->first();

                if ($cartItem) {
                    $cartItem->quantity += $need;
                    $cartItem->save();
                } else {
                    CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $p->id,
                        'quantity' => $need,
                    ]);
                }
            }

            // record cart_combo
            \App\Models\CartCombo::create([
                'cart_id' => $cart->id,
                'combo_id' => $combo->id,
                'quantity' => $comboQty,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm combo vào giỏ hàng'
            ]);
        }

        // Single product add
        $product = Product::find($request->product_id);
        $quantity = max(1, (int) $request->input('quantity', 1));

        if ($product->quantity < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm không đủ'
            ], 400);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $quantity,
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
