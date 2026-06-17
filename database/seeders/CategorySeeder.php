<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'iPhone', 'description' => 'Apple iPhone products'],
            ['name' => 'Samsung', 'description' => 'Samsung Galaxy products'],
            ['name' => 'Xiaomi', 'description' => 'Xiaomi smartphones'],
            ['name' => 'Oppo', 'description' => 'Oppo smartphones'],
            ['name' => 'Vivo', 'description' => 'Vivo smartphones'],
            ['name' => 'Tai nghe', 'description' => 'Tai nghe có dây và không dây'],
        ];

        foreach ($categories as $c) {
            Category::firstOrCreate(['name' => $c['name']], $c);
        }
    }
}
