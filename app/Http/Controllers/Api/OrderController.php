<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items.product.images', 'payment'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tải được đơn hàng của bạn. Hãy thử lại!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'orders' => $orders
            ]
        ]);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with(['items.product.images', 'payment'])
            ->where('user_id', $request->user()->id)
            ->find($id);

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

    /**
     * Tạo đơn hàng mới từ checkout page
     */
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
            DB::beginTransaction();

            $items = $request->items;
            $shippingAddress = $request->shipping_address;
            
            // Check stock for all items
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception("Sản phẩm không tồn tại");
                }
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Sản phẩm {$product->name} không đủ số lượng (còn {$product->quantity})");
                }
            }

            // Calculate total
            $totalAmount = 0;
            foreach ($items as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'user_id' => $request->user()->id,
                'receiver_name' => $shippingAddress['name'],
                'receiver_phone' => $shippingAddress['phone'],
                'receiver_address' => $shippingAddress['full_address'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'note' => $request->note ?? null,
            ]);

            // Create order items and update stock
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                // Update product quantity
                $product = Product::find($item['product_id']);
                $product->quantity -= $item['quantity'];
                $product->save();
            }

            // Create payment
            $paymentMethod = $request->payment_method;
            $paymentStatus = $paymentMethod === 'cod' ? 'pending' : 'pending';
            
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'amount' => $totalAmount,
                'status' => $paymentStatus,
                'transaction_code' => 'TXN' . time() . rand(1000, 9999),
            ]);

            // Clear cart if user is logged in
            $cart = Cart::where('user_id', $request->user()->id)->first();
            if ($cart) {
                $cart->items()->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công',
                'data' => [
                    'id' => $order->id,
                    'order' => $order->load(['items.product', 'payment'])
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_address' => 'required|string',
            'payment_method' => 'required|in:cod,banking,wallet',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $cart = Cart::with('items.product')
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng trống'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Check stock
            foreach ($cart->items as $item) {
                if ($item->product->quantity < $item->quantity) {
                    throw new \Exception("Sản phẩm {$item->product->name} không đủ số lượng");
                }
            }

            // Calculate total
            $totalAmount = 0;
            foreach ($cart->items as $item) {
                $totalAmount += $item->product->price * $item->quantity;
            }

            // Create order
            $order = Order::create([
                'user_id' => $request->user()->id,
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_address' => $request->receiver_address,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Create order items and update stock
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);

                // Update product quantity
                $product = Product::find($item->product_id);
                $product->quantity -= $item->quantity;
                $product->save();
            }

            // Create payment
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $totalAmount,
                'status' => 'completed',
                'transaction_code' => 'TXN' . time() . rand(1000, 9999),
            ]);

            // Clear cart
            $cart->items()->delete();

            // Update order status to paid
            $order->status = 'paid';
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thanh toán thành công',
                'data' => [
                    'order' => $order->load(['items.product', 'payment'])
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Thanh toán thất bại. Vui lòng thử lại sau.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('user_id', $request->user()->id)->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        }

        if ($order->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng đã bị hủy trước đó'
            ], 400);
        }

        if (in_array($order->status, ['shipping', 'completed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy đơn hàng ở trạng thái này'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Restore product quantity
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                $product->quantity += $item->quantity;
                $product->save();
            }

            $order->status = 'cancelled';
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đơn hàng thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy đơn hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
