<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\PasswordResetOtp;
use Carbon\Carbon;

class UserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function emailExists(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function changePassword(User $user, string $hashedPassword): bool
    {
        return $user->update([
            'password' => $hashedPassword
        ]);
    }

    public function createOtp(array $data): PasswordResetOtp
    {
        return PasswordResetOtp::create($data);
    }

    public function findValidOtp(string $email, string $otp): ?PasswordResetOtp
    {
        return PasswordResetOtp::where('email', $email)
            ->where('otp', $otp)
            ->where('used', false)
            ->where('expired_at', '>', now())
            ->first();
    }

    public function markOtpAsUsed(PasswordResetOtp $otpRecord): bool
    {
        return $otpRecord->update(['used' => true]);
    }

    public function getPaginatedUsers(int $perPage = 15)
    {
        return User::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByIdWithRelations(int $id, array $relations = ['orders', 'favorites']): ?User
    {
        return User::with($relations)->find($id);
    }

    public function getUserStats(): array
    {
        return [
            'total' => User::count(),
            'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
            'active' => User::where('status', 'active')->count()
        ];
    }

    public function buildAdminUsersQuery()
    {
        return User::query();
    }
}
