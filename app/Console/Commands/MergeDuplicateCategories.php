<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MergeDuplicateCategories extends Command
{
    protected $signature = 'categories:merge-duplicates {--backup : Create JSON backups before modifying}';
    protected $description = 'Find duplicate categories by name (case-insensitive), reassign products to the kept category (min id) and remove duplicates.';

    public function handle()
    {
        $this->info('Starting duplicate categories merge (keep lowest id)...');

        $timestamp = date('Ymd_His');
        $backupDir = "backups/categories/{$timestamp}";

        // Query groups of duplicate names (case-insensitive)
        $duplicates = DB::table('categories')
            ->selectRaw('LOWER(name) as key_name, GROUP_CONCAT(id) as ids, COUNT(*) as cnt')
            ->groupBy('key_name')
            ->having('cnt', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('No duplicate category names found.');
            return 0;
        }

        if ($this->option('backup')) {
            Storage::disk('local')->put("{$backupDir}/categories.json", DB::table('categories')->get()->toJson(JSON_PRETTY_PRINT));
            Storage::disk('local')->put("{$backupDir}/products.json", DB::table('products')->get()->toJson(JSON_PRETTY_PRINT));
            $this->info("Backups written to storage/{$backupDir}");
        }

        $summary = [];

        foreach ($duplicates as $group) {
            $ids = explode(',', $group->ids);
            sort($ids, SORT_NUMERIC);

            $keepId = (int)array_shift($ids); // smallest id
            $removeIds = array_map('intval', $ids);

            // Reassign products
            if (!empty($removeIds)) {
                DB::table('products')
                    ->whereIn('category_id', $removeIds)
                    ->update(['category_id' => $keepId]);

                // Delete duplicate categories
                DB::table('categories')
                    ->whereIn('id', $removeIds)
                    ->delete();

                $summary[] = [
                    'kept' => $keepId,
                    'removed' => $removeIds,
                ];

                $this->info("Group '{$group->key_name}': kept={$keepId} removed=[" . implode(',', $removeIds) . "]");
            }
        }

        // Save summary
        Storage::disk('local')->put("{$backupDir}/merge_summary.json", json_encode($summary, JSON_PRETTY_PRINT));
        $this->info('Merge completed. Summary saved.');

        return 0;
    }
}
