<?php

namespace App\Http\Controllers\Api\Admin;

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

    public function counts()
    {
        $counts = $this->orderService->getOrderCounts();
        return response()->json($counts);
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->getAdminOrders($request->all(), $request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function show($id)
    {
        try {
            $order = $this->orderService->getAdminOrder((int)$id);
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

        try {
            $order = $this->orderService->updateOrderStatus((int)$id, $request->status);
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái đơn hàng thành công',
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

    public function confirmPayment(Request $request, $id)
    {
        try {
            $order = $this->orderService->confirmPayment((int)$id);
            return response()->json([
                'success' => true,
                'message' => 'Đã xác nhận đơn hàng đã thanh toán',
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

        try {
            $order = $this->orderService->adminCancel((int)$id, $request->reason);
            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đơn hàng'
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function recentOrders()
    {
        $orders = $this->orderService->getRecentOrders();
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function destroy($id)
    {
        try {
            $this->orderService->deleteOrder((int)$id);
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa đơn hàng'
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $code);
        }
    }
}
