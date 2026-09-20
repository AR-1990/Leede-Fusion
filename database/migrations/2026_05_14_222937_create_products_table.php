<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('category_id')->constrained()->onDelete('cascade');
            $blueprint->string('name');
            $blueprint->text('description')->nullable();
            $blueprint->string('price');
            $blueprint->string('old_price')->nullable();
            $blueprint->string('image')->nullable();
            $blueprint->string('tag')->nullable();
            $blueprint->boolean('is_featured')->default(false);
            $blueprint->integer('stock')->default(0);
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
