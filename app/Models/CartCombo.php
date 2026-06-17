<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartCombo extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', 'combo_id', 'quantity'
    ];

    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
