<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_url',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }

        // Nếu đã là URL đầy đủ, trả về luôn
        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        // Nếu path đã bắt đầu bằng 'storage/', dùng asset() để không bị double prefix
        if (Str::startsWith($value, 'storage/')) {
            return asset($value);
        }

        // Path mới từ Storage::store() - dùng Storage::url() để thêm /storage/ prefix
        return Storage::disk('public')->url($value);
    }
}

