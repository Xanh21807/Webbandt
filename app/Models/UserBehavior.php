<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBehavior extends Model
{
    protected $table = 'user_behaviors';

    protected $fillable = [
        'user_id',
        'product_id',
        'type',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
