<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CartRepository;
use Illuminate\Support\Facades\DB;
use PayOS\PayOS;
use Exception;

class OrderService
{
    protected $orderRepository;
    protected $productRepository;
    protected $cartRepository;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        CartRepository $cartRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
    }

    private function normalizePaymentMethod(?string $method): string
    {
        $method = strtolower(trim((string) $method));

        return match ($method) {
            'cod' => 'cod',
            'banking', 'bank_transfer', 'bank transfer', 'chuyen khoan' => 'banking',
            'wallet', 'momo', 'vnpay' => 'wallet',
            default => $method,
        };
    }

    public function getOrders(int $userId)
    {
        $orders = $this->orderRepository->getUserOrders($userId);

        if ($orders->isEmpty()) {
            throw new Exception('Không tải được đơn hàng của bạn. Hãy thử lại!', 404);
        }

        return $orders;
    }

    public function getOrderDetails(int $userId, int $id)
    {
        $order = $this->orderRepository->findUserOrder($userId, $id);

        if (!$order) {
            throw new Exception('Đơn hàng không tồn tại', 404);
        }

        return $order;
    }

    public function createOrder(array $data, $user)
    {
        $paymentMethod = $this->normalizePaymentMethod($data['payment_method'] ?? null);

        if (!in_array($paymentMethod, ['cod', 'banking', 'wallet'], true)) {
            throw new Exception('Phương thức thanh toán không hợp lệ', 400);
        }

        return DB::transaction(function () use ($data, $user, $paymentMethod) {
            // Check stock
            foreach ($data['items'] as $item) {
                $product = $this->productRepository->find($item['product_id']);
                if (!$product) {
                    throw new Exception("Sản phẩm không tồn tại", 404);
                }
                if ($product->quantity < $item['quantity']) {
                    throw new Exception("Sản phẩm {$product->name} không đủ số lượng (còn {$product->quantity})", 400);
                }
            }

            // Calculate total
            $totalAmount = 0;
            foreach ($data['items'] as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            // Create order
            $order = $this->orderRepository->create([
                'user_id' => $user->id,
                'receiver_name' => $data['shipping_address']['name'],
                'receiver_phone' => $data['shipping_address']['phone'],
                'receiver_address' => $data['shipping_address']['full_address'],
                'payment_method' => $paymentMethod,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'note' => $data['note'] ?? null,
            ]);

            // Create order items and update stock
            foreach ($data['items'] as $item) {
                $this->orderRepository->createItem([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                // Update product stock
                $product = $this->productRepository->find($item['product_id']);
                $this->productRepository->update($product, [
                    'quantity' => $product->quantity - $item['quantity']
                ]);
            }

            // Create payment record
            $this->orderRepository->createPayment([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'amount' => $totalAmount,
                'status' => 'pending',
                'transaction_code' => 'TXN' . time() . rand(1000, 9999),
            ]);

            // If COD, clear cart and return order
            if ($paymentMethod === 'cod') {
                $cart = $this->cartRepository->findCartByUserId($user->id);
                if ($cart) {
                    $this->cartRepository->clearCartItems($cart);
                }

                return [
                    'payment_type' => 'cod',
                    'order' => $order->load(['items.product', 'payment'])
                ];
            }

            // Online Payment PayOS Link Creation
            $payOS = new PayOS(
                config('services.payos.client_id'),
                config('services.payos.api_key'),
                config('services.payos.checksum_key')
            );

            $orderCode = intval(date('ymdHis') . rand(10, 99));

            $paymentData = [
                "orderCode" => $orderCode,
                "amount" => (int)$totalAmount,
                "description" => "DH-" . $order->id,
                "returnUrl" => url('/payment/success'),
                "cancelUrl" => url('/payment/cancel')
            ];

            $response = $payOS->createPaymentLink($paymentData);

            return [
                'payment_type' => 'payos',
                'checkoutUrl' => $response['checkoutUrl'],
                'order_id' => $order->id
            ];
        });
    }

    public function checkoutFromCart(array $data, $user)
    {
        $paymentMethod = $this->normalizePaymentMethod($data['payment_method'] ?? null);

        if (!in_array($paymentMethod, ['cod', 'banking', 'wallet'], true)) {
            throw new Exception('Phương thức thanh toán không hợp lệ', 400);
        }

        $cart = $this->cartRepository->getCartWithItems($user->id);

        if (!$cart || $cart->items->isEmpty()) {
            throw new Exception('Giỏ hàng trống', 400);
        }

        return DB::transaction(function () use ($data, $user, $paymentMethod, $cart) {
            // Check stock
            foreach ($cart->items as $item) {
                if ($item->product->quantity < $item->quantity) {
                    throw new Exception("Sản phẩm {$item->product->name} không đủ số lượng", 400);
                }
            }

            // Calculate total
            $totalAmount = 0;
            foreach ($cart->items as $item) {
                $totalAmount += $item->product->price * $item->quantity;
            }

            // Create order
            $order = $this->orderRepository->create([
                'user_id' => $user->id,
                'receiver_name' => $data['receiver_name'],
                'receiver_phone' => $data['receiver_phone'],
                'receiver_address' => $data['receiver_address'],
                'payment_method' => $paymentMethod,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Create order items and update stock
            foreach ($cart->items as $item) {
                $this->orderRepository->createItem([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);

                // Update product stock
                $product = $this->productRepository->find($item->product_id);
                $this->productRepository->update($product, [
                    'quantity' => $product->quantity - $item->quantity
                ]);
            }

            // Create payment
            $this->orderRepository->createPayment([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'amount' => $totalAmount,
                'status' => 'pending',
                'transaction_code' => 'TXN' . time() . rand(1000, 9999),
            ]);

            // Clear cart
            $this->cartRepository->clearCartItems($cart);

            return $order->load(['items.product', 'payment']);
        });
    }

    public function cancelOrder(int $userId, int $id)
    {
        $order = $this->orderRepository->findUserOrder($userId, $id);

        if (!$order) {
            throw new Exception('Đơn hàng không tồn tại', 404);
        }

        if ($order->status === 'cancelled') {
            throw new Exception('Đơn hàng đã bị hủy trước đó', 400);
        }

        if (in_array($order->status, ['shipping', 'completed'])) {
            throw new Exception('Không thể hủy đơn hàng ở trạng thái này', 400);
        }

        return DB::transaction(function () use ($order) {
            // Restore product quantity
            foreach ($order->items as $item) {
                $product = $this->productRepository->find($item->product_id);
                if ($product) {
                    $this->productRepository->update($product, [
                        'quantity' => $product->quantity + $item->quantity
                    ]);
                }
            }

            $this->orderRepository->updateStatus($order, 'cancelled');

            return true;
        });
    }

    // Admin side APIs
    public function getOrderCounts(): array
    {
        return $this->orderRepository->getCounts();
    }

    public function getAdminOrders(array $params, int $perPage = 15)
    {
        $query = $this->orderRepository->buildAdminOrdersQuery();

        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        $searchTerm = $params['search'] ?? $params['keyword'] ?? null;
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('receiver_name', 'like', "%{$searchTerm}%")
                  ->orWhere('receiver_phone', 'like', "%{$searchTerm}%")
                  ->orWhere('order_number', 'like', "%{$searchTerm}%")
                  ->orWhere('id', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function ($q2) use ($searchTerm) {
                      $q2->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                  });
            });
        }

        if (!empty($params['date_from'])) {
            $query->whereDate('created_at', '>=', $params['date_from']);
        }
        if (!empty($params['date_to'])) {
            $query->whereDate('created_at', '<=', $params['date_to']);
        }

        if (!empty($params['payment_method'])) {
            $query->whereHas('payment', function($q) use ($params) {
                $q->where('payment_method', $params['payment_method']);
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getAdminOrder(int $id)
    {
        $order = $this->orderRepository->findWithRelations($id);
        if (!$order) {
            throw new Exception('Đơn hàng không tồn tại', 404);
        }
        return $order;
    }

    public function updateOrderStatus(int $id, string $status)
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new Exception('Đơn hàng không tồn tại', 404);
        }

        $this->orderRepository->updateStatus($order, $status);

        return $order;
    }

    public function confirmPayment(int $id)
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new Exception('Đơn hàng không tồn tại', 404);
        }

        if (!$order->payment) {
            throw new Exception('Đơn hàng này chưa có thông tin thanh toán', 404);
        }

        $this->orderRepository->confirmPayment($order);

        return $order->load(['user', 'items.product', 'payment']);
    }

    public function adminCancel(int $id, string $reason)
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new Exception('Đơn hàng không tồn tại', 404);
        }

        if ($order->status === 'cancelled') {
            throw new Exception('Đơn hàng đã bị hủy', 400);
        }

        $this->orderRepository->updateStatus($order, 'cancelled');

        return $order;
    }

    public function getRecentOrders(int $limit = 5): array
    {
        return $this->orderRepository->getRecentOrders($limit)
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->receiver_name ?? ($order->user->name ?? 'N/A'),
                    'total' => $order->total_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at
                ];
            })->toArray();
    }

    public function deleteOrder(int $id)
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new Exception('Đơn hàng không tồn tại', 404);
        }

        return $this->orderRepository->delete($order);
    }
}
