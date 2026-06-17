<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalAttribute()
    {
        $subtotal = $this->items->sum(function ($item) {
            if ($item->product) {
                return $item->product->price * $item->quantity;
            }
            return 0;
        });

        // apply combo discounts
        $comboDiscount = 0;
        if (method_exists($this, 'cartCombos')) {
            foreach ($this->cartCombos as $cc) {
                $combo = $cc->combo;
                if (!$combo) continue;
                $comboSum = 0;
                foreach ($combo->products as $p) {
                    $qty = $p->pivot->quantity * $cc->quantity;
                    $comboSum += ($p->price ?? 0) * $qty;
                }
                $comboDiscount += $comboSum * (($combo->discount_percent ?? 0) / 100);
            }
        }

        return max(0, $subtotal - $comboDiscount);
    }

    public function cartCombos()
    {
        return $this->hasMany(\App\Models\CartCombo::class);
    }
}
