<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProductImageAssignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dir = public_path('storage/product-images');
        if (!File::isDirectory($dir)) {
            $this->command->error("Directory not found: {$dir}");
            return;
        }

        $files = File::files($dir);
        
        $numberedImages = [];
        foreach ($files as $file) {
            $filename = $file->getFilename();
            $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
            
            if (ctype_digit($nameWithoutExt)) {
                $numberedImages[] = [
                    'number' => intval($nameWithoutExt),
                    'filename' => $filename,
                    'path' => 'storage/product-images/' . $filename
                ];
            }
        }

        if (empty($numberedImages)) {
            $this->command->error("No numbered images found in {$dir}");
            return;
        }

        // Sort images numerically by their number
        usort($numberedImages, function ($a, $b) {
            return $a['number'] <=> $b['number'];
        });

        $this->command->info("Found " . count($numberedImages) . " numbered images.");

        // Fetch products starting from ID 1, sorted by ID
        $products = Product::where('id', '>=', 1)->orderBy('id')->get();
        $this->command->info("Found " . $products->count() . " products to update.");

        DB::transaction(function () use ($products, $numberedImages) {
            $imageCount = count($numberedImages);
            
            foreach ($products as $index => $product) {
                // Delete existing images for this product to prevent duplicate/stale images
                ProductImage::where('product_id', $product->id)->delete();

                // Assign 3 images sequentially
                for ($i = 0; $i < 3; $i++) {
                    $imgIndex = ($index * 3) + $i;
                    
                    // Wrap around if product count exceeds image count / 3
                    $actualImgIndex = $imgIndex % $imageCount;
                    $image = $numberedImages[$actualImgIndex];

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $image['path'],
                    ]);
                }
            }
        });

        $this->command->info("Successfully assigned 3 images per product sequentially starting from ID 1!");
    }
}
