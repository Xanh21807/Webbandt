<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function stats()
    {
        $total = User::count();
        $newThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $active = User::where('status', 'active')->count();
        
        return response()->json([
            'success' => true,
            'total' => $total,
            'new_this_month' => $newThisMonth,
            'active' => $active
        ]);
    }

    public function index(Request $request)
    {
        $query = User::query();

        // Support both 'search' and 'keyword' parameters
        $searchTerm = $request->get('search') ?? $request->get('keyword');
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $user = User::with(['orders', 'favorites'])->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user
            ]
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,blocked',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng không tồn tại'
            ], 404);
        }

        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể thay đổi trạng thái admin'
            ], 403);
        }

        $user->status = $request->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái người dùng thành công',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => 'sometimes|in:user,admin',
            'status' => 'sometimes|in:active,blocked',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only(['name', 'phone', 'address', 'role', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật người dùng thành công',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => 'required|in:user,admin',
            'status' => 'sometimes|in:active,blocked',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'status' => $request->get('status', 'active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm người dùng thành công',
            'data' => [
                'user' => $user
            ]
        ], 201);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng không tồn tại'
            ], 404);
        }

        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa tài khoản admin'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa người dùng'
        ]);
    }
}
