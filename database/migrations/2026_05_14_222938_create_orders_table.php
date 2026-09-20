<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade');
            $blueprint->decimal('total_amount', 12, 2);
            $blueprint->string('status')->default('pending');
            $blueprint->text('shipping_address');
            $blueprint->string('payment_method')->default('cod');
            $blueprint->timestamps();
        });

        Schema::create('order_items', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('order_id')->constrained()->onDelete('cascade');
            $blueprint->foreignId('product_id')->constrained()->onDelete('cascade');
            $blueprint->integer('quantity');
            $blueprint->decimal('price', 12, 2);
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
