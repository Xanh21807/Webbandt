<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Get order counts by status
     */
    public function counts()
    {
        $counts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'shipping' => Order::where('status', 'shipping')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return response()->json($counts);
    }

    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'payment']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Support both 'search' and 'keyword' params
        $searchTerm = $request->get('search') ?? $request->get('keyword');
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

        // Date filters
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Payment method filter
        if ($request->has('payment_method') && $request->payment_method) {
            $query->whereHas('payment', function($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product.images', 'payment'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order
            ]
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,paid,shipping,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái đơn hàng thành công',
            'data' => [
                'order' => $order
            ]
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        }

        if ($order->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng đã bị hủy'
            ], 400);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã hủy đơn hàng'
        ]);
    }

    /**
     * Get recent orders for dashboard
     */
    public function recentOrders()
    {
        $orders = Order::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->receiver_name ?? ($order->user->name ?? 'N/A'),
                    'total' => $order->total_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        }

        // Delete order items first
        $order->items()->delete();
        
        // Delete payment if exists
        if ($order->payment) {
            $order->payment->delete();
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa đơn hàng'
        ]);
    }
}
