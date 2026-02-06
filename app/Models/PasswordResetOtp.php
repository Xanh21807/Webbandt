<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp',
        'expired_at',
        'used',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'used' => 'boolean',
    ];
}
