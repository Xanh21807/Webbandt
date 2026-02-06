<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('receiver_address');
            $table->enum('payment_method', ['cod', 'banking', 'wallet'])->default('cod');
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['pending', 'paid', 'shipping', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
