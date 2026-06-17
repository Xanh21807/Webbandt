<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Combo;
use App\Models\Product;

class ComboSeeder extends Seeder
{
    public function run(): void
    {
        // Example combo for iPhone 15 Pro Max
        $primary = Product::where('name', 'iPhone 15 Pro Max')->first();
        $airpods = Product::where('name', 'AirPods Pro 2 USB-C')->first();
        $case = Product::where('name', 'Ốp lưng iPhone 15 Pro Max MagSafe trong suốt')->first();

        if ($primary && $airpods && $case) {
            $combo = Combo::create(['name' => 'iPhone 15 Pro Max + AirPods + Ốp lưng', 'description' => 'Combo tiết kiệm khi mua cùng', 'discount_percent' => 12]);
            $combo->products()->attach([$primary->id => ['quantity' => 1], $airpods->id => ['quantity' => 1], $case->id => ['quantity' => 1]]);
        }

        // Simple Apple accessory bundle
        $magSafe = Product::where('name', 'Sạc không dây MagSafe Apple 15W')->first();
        $battery = Product::where('name', 'Pin sạc MagSafe Apple Battery Pack')->first();
        if ($magSafe && $battery) {
            $c = Combo::create(['name' => 'MagSafe + Battery Pack', 'discount_percent' => 8]);
            $c->products()->attach([$magSafe->id => ['quantity' => 1], $battery->id => ['quantity' => 1]]);
        }

        echo "ComboSeeder completed\n";
    }
}
