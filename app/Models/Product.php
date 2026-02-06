<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'price',
        'ram',
        'storage',
        'battery',
        'description',
        'quantity',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('brand', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['brand'])) {
            if (is_array($filters['brand'])) {
                $query->whereIn('brand', $filters['brand']);
            } else {
                $query->where('brand', $filters['brand']);
            }
        }
        if (isset($filters['ram'])) {
            if (is_array($filters['ram'])) {
                $query->whereIn('ram', $filters['ram']);
            } else {
                $query->where('ram', $filters['ram']);
            }
        }
        if (isset($filters['storage'])) {
            if (is_array($filters['storage'])) {
                $query->whereIn('storage', $filters['storage']);
            } else {
                $query->where('storage', $filters['storage']);
            }
        }
        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        // Lọc theo mức giá (price_range)
        if (isset($filters['price_range'])) {
            switch ($filters['price_range']) {
                case 'under_5':
                    $query->where('price', '<', 5000000);
                    break;
                case '5_10':
                    $query->where('price', '>=', 5000000)->where('price', '<', 10000000);
                    break;
                case '10_20':
                    $query->where('price', '>=', 10000000)->where('price', '<', 20000000);
                    break;
                case '20_30':
                    $query->where('price', '>=', 20000000)->where('price', '<', 30000000);
                    break;
                case 'over_30':
                    $query->where('price', '>=', 30000000);
                    break;
            }
        }
        return $query;
    }
}
