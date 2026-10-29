<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        // Choices offered in the "Design Your Article" studio, managed from admin.
        Schema::create('design_options', function (Blueprint $table) {
            $table->id();
            $table->string('group', 40);
            $table->string('label');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['group', 'is_active', 'sort_order']);
        });

        Schema::create('custom_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 16)->unique();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone', 32);
            $table->string('customer_email')->nullable();
            $table->string('city', 100)->nullable();
            $table->json('selections');
            $table->string('size_mode', 20)->default('standard');
            $table->string('standard_size', 8)->nullable();
            $table->json('measurements')->nullable();
            $table->json('reference_images')->nullable();
            $table->text('notes')->nullable();
            $table->date('needed_by')->nullable();
            $table->unsignedInteger('estimated_price')->default(0);
            $table->unsignedInteger('quoted_price')->nullable();
            $table->string('stage', 30)->default('received');
            $table->json('stage_history')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_orders');
        Schema::dropIfExists('design_options');
        Schema::dropIfExists('settings');
    }
};
