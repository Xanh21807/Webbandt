<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Carbon;

class OrderRepository
{
    public function getUserOrders(int $userId)
    {
        return Order::with(['items.product.images', 'payment'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findUserOrder(int $userId, int $id): ?Order
    {
        return Order::with(['items.product.images', 'payment'])
            ->where('user_id', $userId)
            ->find($id);
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function createItem(array $data): OrderItem
    {
        return OrderItem::create($data);
    }

    public function createPayment(array $data): Payment
    {
        return Payment::create($data);
    }

    public function find(int $id): ?Order
    {
        return Order::find($id);
    }

    public function findWithRelations(int $id, array $relations = ['user', 'items.product.images', 'payment']): ?Order
    {
        return Order::with($relations)->find($id);
    }

    public function updateStatus(Order $order, string $status): bool
    {
        $order->status = $status;
        $saved = $order->save();

        if ($status === 'paid' && $order->payment) {
            $order->payment->status = 'completed';
            $order->payment->paid_at = Carbon::now();
            $order->payment->save();
        }

        return $saved;
    }

    public function confirmPayment(Order $order): bool
    {
        $order->status = 'paid';
        $order->save();

        if ($order->payment) {
            $order->payment->status = 'completed';
            $order->payment->paid_at = Carbon::now();
            $order->payment->save();
        }

        return true;
    }

    public function getCounts(): array
    {
        return [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'shipping' => Order::where('status', 'shipping')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
    }

    public function buildAdminOrdersQuery()
    {
        return Order::with(['user', 'items.product', 'payment']);
    }

    public function getRecentOrders(int $limit = 5)
    {
        return Order::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function delete(Order $order): bool
    {
        $order->items()->delete();
        if ($order->payment) {
            $order->payment->delete();
        }
        return $order->delete();
    }
}
