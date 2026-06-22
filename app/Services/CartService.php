<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use App\Models\CartItem;
use App\Models\CartCombo;
use Exception;

class CartService
{
    protected $cartRepository;
    protected $productRepository;

    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function getCart(int $userId): array
    {
        $cart = $this->cartRepository->getCartWithItems($userId);

        $items = $cart->items->filter(function ($item) {
            return $item->product !== null;
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

        return [
            'items' => $items,
            'total' => $cart->total ?? 0,
            'cart' => $cart
        ];
    }

    public function addItem(int $userId, array $data)
    {
        $cart = $this->cartRepository->getCartWithItems($userId);

        if (!empty($data['combo_id'])) {
            $combo = $this->cartRepository->findComboWithProducts($data['combo_id']);
            if (!$combo) {
                throw new Exception('Combo không tồn tại', 404);
            }

            $comboQty = max(1, (int) ($data['quantity'] ?? 1));

            // Check stock
            foreach ($combo->products as $p) {
                $need = ($p->pivot->quantity ?? 1) * $comboQty;
                if ($p->quantity < $need) {
                    throw new Exception("Sản phẩm {$p->name} không đủ số lượng", 400);
                }
            }

            // Create/update cart items
            foreach ($combo->products as $p) {
                $need = ($p->pivot->quantity ?? 1) * $comboQty;
                $cartItem = $this->cartRepository->findCartItem($cart->id, $p->id);

                if ($cartItem) {
                    $cartItem->quantity += $need;
                    $cartItem->save();
                } else {
                    $this->cartRepository->createCartItem([
                        'cart_id' => $cart->id,
                        'product_id' => $p->id,
                        'quantity' => $need,
                    ]);
                }
            }

            // Record cart combo
            $this->cartRepository->createCartCombo([
                'cart_id' => $cart->id,
                'combo_id' => $combo->id,
                'quantity' => $comboQty,
            ]);

            return ['message' => 'Đã thêm combo vào giỏ hàng'];
        }

        // Single product add
        if (empty($data['product_id'])) {
            throw new Exception('Dữ liệu không hợp lệ', 422);
        }

        $product = $this->productRepository->find($data['product_id']);
        if (!$product) {
            throw new Exception('Sản phẩm không tồn tại', 404);
        }

        $quantity = max(1, (int) ($data['quantity'] ?? 1));

        if ($product->quantity < $quantity) {
            throw new Exception('Số lượng sản phẩm không đủ', 400);
        }

        $cartItem = $this->cartRepository->findCartItem($cart->id, $product->id);

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $this->cartRepository->createCartItem([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return ['message' => 'Đã thêm sản phẩm vào giỏ hàng'];
    }

    public function updateItem(int $userId, int $itemId, int $quantity)
    {
        $cartItem = $this->cartRepository->findUserCartItem($userId, $itemId);

        if (!$cartItem) {
            throw new Exception('Sản phẩm không có trong giỏ hàng', 404);
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return ['message' => 'Đã cập nhật giỏ hàng'];
    }

    public function removeItem(int $userId, int $itemId)
    {
        $cartItem = $this->cartRepository->findUserCartItem($userId, $itemId);

        if (!$cartItem) {
            throw new Exception('Sản phẩm không có trong giỏ hàng', 404);
        }

        $this->cartRepository->deleteCartItem($cartItem);

        return ['message' => 'Đã xóa sản phẩm khỏi giỏ hàng'];
    }

    public function clearCart(int $userId)
    {
        $cart = $this->cartRepository->findCartByUserId($userId);

        if ($cart) {
            $this->cartRepository->clearCartItems($cart);
        }

        return ['message' => 'Đã xóa toàn bộ giỏ hàng'];
    }
}
