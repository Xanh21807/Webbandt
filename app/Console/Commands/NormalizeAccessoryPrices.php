<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NormalizeAccessoryPrices extends Command
{
    protected $signature = 'prices:normalize-accessories {--backup : create backup before changes}';
    protected $description = 'Normalize accessory prices to specified ranges (backup option available)';

    public function handle()
    {
        $this->info('Normalize accessory prices - starting');
        $timestamp = date('Ymd_His');
        $backupDir = "backups/accessory-prices/{$timestamp}";

        $rules = [
            'Ốp lưng' => [60000, 150000],
            'Cáp sạc' => [100000, 150000],
            'Miếng dán màn hình' => [90000, 110000],
            'Giá đỡ điện thoại' => [90000, 110000],
        ];

        // Find categories
        $categories = DB::table('categories')->get();
        $map = [];
        foreach ($categories as $c) {
            $name = trim(mb_strtolower($c->name));
            foreach ($rules as $rname => $range) {
                if ($name === mb_strtolower($rname)) {
                    $map[$rname][] = $c->id;
                }
            }
        }

        if (empty($map)) {
            $this->info('No matching accessory categories found.');
            return 0;
        }

        // Backup products if requested
        if ($this->option('backup')) {
            $ids = [];
            foreach ($map as $idsArr) $ids = array_merge($ids, $idsArr);
            $products = DB::table('products')->whereIn('category_id', $ids)->get();
            Storage::disk('local')->put("{$backupDir}/products_before.json", $products->toJson(JSON_PRETTY_PRINT));
            $this->info("Backed up " . $products->count() . " products to storage/{$backupDir}/products_before.json");
        }

        $summary = [];
        foreach ($rules as $rname => [$min, $max]) {
            $catIds = $map[$rname] ?? [];
            if (empty($catIds)) continue;

            $products = DB::table('products')->whereIn('category_id', $catIds)->get();
            $changed = 0;

            foreach ($products as $p) {
                // price stored as decimal string
                $price = (int) round($p->price);
                $newPrice = $price;
                if ($price < $min) $newPrice = $min;
                if ($price > $max) $newPrice = $max;

                // sale_price may be null
                $salePriceRaw = property_exists($p, 'sale_price') ? $p->sale_price : null;
                $salePrice = $salePriceRaw !== null ? (int) round($salePriceRaw) : null;
                $newSale = $salePrice;
                if ($salePrice !== null) {
                    if ($salePrice < $min) $newSale = $min;
                    if ($salePrice > $max) $newSale = $max;
                }

                $update = [];
                if ($newPrice !== $price) $update['price'] = $newPrice;
                if ($salePrice !== null && $newSale !== $salePrice) $update['sale_price'] = $newSale;

                if (!empty($update)) {
                    DB::table('products')->where('id', $p->id)->update($update);
                    $changed++;
                }
            }

            $summary[$rname] = ['category_ids' => $catIds, 'products_checked' => $products->count(), 'products_changed' => $changed, 'range' => [$min, $max]];
            $this->info("Normalized {$changed}/" . $products->count() . " products for '{$rname}' to range {$min}-{$max}");
        }

        Storage::disk('local')->put("{$backupDir}/normalize_summary.json", json_encode($summary, JSON_PRETTY_PRINT));
        $this->info('Normalization complete. Summary saved.');

        return 0;
    }
}
