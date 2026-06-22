<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Exception;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        if ($this->userRepository->emailExists($data['email'])) {
            throw new Exception('Tài khoản đã tồn tại. Vui lòng đăng nhập hoặc sử dụng email khác.', 409);
        }

        return $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'status' => 'active',
        ]);
    }

    public function login(array $credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new Exception('Email hoặc mật khẩu không đúng. Vui lòng thử lại.', 401);
        }

        if ($user->status === 'blocked') {
            throw new Exception('Tài khoản của bạn đã bị khóa.', 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ];
    }

    public function adminLogin(array $credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new Exception('Thông tin đăng nhập không chính xác', 401);
        }

        if ($user->role !== 'admin') {
            throw new Exception('Bạn không có quyền truy cập', 403);
        }

        if ($user->status === 'blocked') {
            throw new Exception('Tài khoản đã bị khóa', 403);
        }

        $token = $user->createToken('admin_token')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ];
    }

    public function logout($user)
    {
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }
        return true;
    }

    public function forgotPassword(string $email)
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            throw new Exception('Email không tồn tại trong hệ thống.', 404);
        }

        $otp = rand(100000, 999999);

        $this->userRepository->createOtp([
            'email' => $email,
            'otp' => $otp,
            'expired_at' => now()->addMinutes(10),
            'used' => false,
        ]);

        try {
            Mail::to($email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Send OTP Email Error: ' . $e->getMessage());
        }

        return true;
    }

    public function verifyOtp(string $email, string $otp)
    {
        $otpRecord = $this->userRepository->findValidOtp($email, $otp);

        if (!$otpRecord) {
            throw new Exception('Liên kết hoặc mã xác nhận không còn hiệu lực. Vui lòng yêu cầu lại.', 400);
        }

        return [
            'email' => $email,
            'otp' => $otp
        ];
    }

    public function resetPassword(string $email, string $otp, string $password)
    {
        $otpRecord = $this->userRepository->findValidOtp($email, $otp);

        if (!$otpRecord) {
            throw new Exception('Liên kết hoặc mã xác nhận không còn hiệu lực. Vui lòng yêu cầu lại.', 400);
        }

        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            throw new Exception('Email không tồn tại.', 404);
        }

        $this->userRepository->changePassword($user, Hash::make($password));
        $this->userRepository->markOtpAsUsed($otpRecord);

        return true;
    }

    public function updateProfile($user, array $data)
    {
        $updateData = [];
        $allowedFields = ['name', 'phone', 'address', 'birthday', 'gender'];
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            $this->userRepository->update($user, $updateData);
            $user->refresh();
        }

        return $user;
    }

    public function changePassword($user, string $currentPassword, string $newPassword)
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new Exception('Mật khẩu hiện tại không đúng', 422);
        }

        return $this->userRepository->changePassword($user, Hash::make($newPassword));
    }

    // Admin user management business logic
    public function getUserStats(): array
    {
        return $this->userRepository->getUserStats();
    }

    public function getUsers(array $params, int $perPage = 15)
    {
        $query = $this->userRepository->buildAdminUsersQuery();

        $searchTerm = $params['search'] ?? $params['keyword'] ?? null;
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        if (isset($params['role'])) {
            $query->where('role', $params['role']);
        }

        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getUserDetails(int $id)
    {
        $user = $this->userRepository->findByIdWithRelations($id);
        if (!$user) {
            throw new Exception('Người dùng không tồn tại', 404);
        }
        return $user;
    }

    public function updateUserStatus(int $id, string $status)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new Exception('Người dùng không tồn tại', 404);
        }

        if ($user->role === 'admin') {
            throw new Exception('Không thể thay đổi trạng thái admin', 403);
        }

        $this->userRepository->update($user, ['status' => $status]);

        if ($status === 'blocked') {
            $user->tokens()->delete();
        }

        return $user;
    }

    public function updateUser(int $id, array $data)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new Exception('Người dùng không tồn tại', 404);
        }

        $updateData = array_intersect_key($data, array_flip(['name', 'phone', 'address', 'role', 'status']));
        $this->userRepository->update($user, $updateData);

        return $user;
    }

    public function createUser(array $data)
    {
        if ($this->userRepository->emailExists($data['email'])) {
            throw new Exception('Email đã được sử dụng.', 422);
        }

        return $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => $data['role'],
            'status' => $data['status'] ?? 'active',
        ]);
    }

    public function deleteUser(int $id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new Exception('Người dùng không tồn tại', 404);
        }

        if ($user->role === 'admin') {
            throw new Exception('Không thể xóa tài khoản admin', 403);
        }

        return $user->delete();
    }
}
