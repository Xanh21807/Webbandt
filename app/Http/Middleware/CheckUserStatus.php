<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Kiểm tra trạng thái tài khoản sau khi xác thực Sanctum.
     * Nếu tài khoản bị khóa (blocked), từ chối truy cập ngay lập tức.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status === 'blocked') {
            // Thu hồi token hiện tại để tránh tái sử dụng
            $user->currentAccessToken()->delete();

            return response()->json([
                'success' => false,
                'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
                'code' => 'ACCOUNT_BLOCKED',
            ], 403);
        }

        return $next($request);
    }
}
