<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_value',
        'expired_at',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'expired_at' => 'datetime',
    ];

    public function isValid()
    {
        return $this->expired_at > now();
    }
}
