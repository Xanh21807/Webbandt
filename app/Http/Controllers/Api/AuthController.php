<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if email already exists
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản đã tồn tại. Vui lòng đăng nhập hoặc sử dụng email khác.'
            ], 409);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công',
            'data' => [
                'user' => $user
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng. Vui lòng thử lại.'
            ], 401);
        }

        if ($user->status === 'blocked') {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản của bạn đã bị khóa.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email không hợp lệ. Vui lòng kiểm tra lại.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Save OTP to database
        PasswordResetOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expired_at' => now()->addMinutes(10),
            'used' => false,
        ]);

        // Send OTP via email
        try {
            Mail::to($request->email)->send(new \App\Mail\OtpMail($otp));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Send OTP Email Error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Mã OTP đã được gửi đến email của bạn',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expired_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Liên kết hoặc mã xác nhận không còn hiệu lực. Vui lòng yêu cầu lại.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mã OTP hợp lệ',
            'data' => [
                'email' => $request->email,
                'otp' => $request->otp
            ]
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->password !== $request->password_confirmation) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu xác nhận không khớp.'
            ], 400);
        }

        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expired_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Liên kết hoặc mã xác nhận không còn hiệu lực. Vui lòng yêu cầu lại.'
            ], 400);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Mark OTP as used
        $otpRecord->used = true;
        $otpRecord->save();

        return response()->json([
            'success' => true,
            'message' => 'Mật khẩu đã được cập nhật thành công'
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user()
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'birthday' => 'sometimes|date|before:today',
            'gender' => 'sometimes|in:male,female,other',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        // Chỉ cập nhật các trường có trong request
        $updateData = [];
        if ($request->has('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->has('phone')) {
            $updateData['phone'] = $request->phone;
        }
        if ($request->has('address')) {
            $updateData['address'] = $request->address;
        }
        if ($request->has('birthday')) {
            $updateData['birthday'] = $request->birthday;
        }
        if ($request->has('gender')) {
            $updateData['gender'] = $request->gender;
        }

        if (!empty($updateData)) {
            $user->update($updateData);
            $user->refresh();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông tin thành công',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu hiện tại không đúng'
            ], 422);
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đổi mật khẩu thành công'
        ]);
    }
}
