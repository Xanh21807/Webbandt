<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        try {
            $orders = $this->orderService->getOrders($request->user()->id);
            return response()->json([
                'success' => true,
                'data' => [
                    'orders' => $orders
                ]
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $order = $this->orderService->getOrderDetails($request->user()->id, (int)$id);
            return response()->json([
                'success' => true,
                'data' => [
                    'order' => $order
                ]
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string|max:255',
            'shipping_address.phone' => 'required|string|max:20',
            'shipping_address.full_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->orderService->createOrder($request->all(), $request->user());

            if ($result['payment_type'] === 'cod') {
                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công',
                    'data' => [
                        'id' => $result['order']->id,
                        'order' => $result['order']
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'payment_type' => 'payos',
                'checkoutUrl' => $result['checkoutUrl'],
                'order_id' => $result['order_id']
            ]);

        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], $code);
        }
    }

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $order = $this->orderService->checkoutFromCart($request->all(), $request->user());
            return response()->json([
                'success' => true,
                'message' => 'Thanh toán thành công',
                'data' => [
                    'order' => $order
                ]
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => 'Thanh toán thất bại. Vui lòng thử lại sau.',
                'error' => $e->getMessage()
            ], $code);
        }
    }

    public function cancelOrder(Request $request, $id)
    {
        try {
            $this->orderService->cancelOrder($request->user()->id, (int)$id);
            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đơn hàng thành công'
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], $code);
        }
    }
}
