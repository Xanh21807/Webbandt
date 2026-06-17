<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'customer' to users.role and add 'confirmed' to orders.status
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('user','admin','customer') NOT NULL DEFAULT 'user'");
        DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('pending','paid','shipping','completed','cancelled','confirmed') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Revert to original enums (risk of data loss if values exist)
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('user','admin') NOT NULL DEFAULT 'user'");
        DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('pending','paid','shipping','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
