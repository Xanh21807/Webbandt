<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CartCombo;
use App\Models\Combo;

class CartRepository
{
    public function getCartWithItems(int $userId): Cart
    {
        return Cart::with(['items.product.images', 'cartCombos.combo.products'])
            ->firstOrCreate(['user_id' => $userId]);
    }

    public function findCartByUserId(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)->first();
    }

    public function findComboWithProducts(int $comboId): ?Combo
    {
        return Combo::with('products')->find($comboId);
    }

    public function findCartItem(int $cartId, int $productId): ?CartItem
    {
        return CartItem::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();
    }

    public function createCartItem(array $data): CartItem
    {
        return CartItem::create($data);
    }

    public function createCartCombo(array $data): CartCombo
    {
        return CartCombo::create($data);
    }

    public function findUserCartItem(int $userId, int $itemId): ?CartItem
    {
        return CartItem::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->find($itemId);
    }

    public function deleteCartItem(CartItem $cartItem): bool
    {
        return $cartItem->delete();
    }

    public function clearCartItems(Cart $cart): void
    {
        $cart->items()->delete();
    }
}
