<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        // Backfill slugs for existing products.
        $used = [];
        foreach (DB::table('products')->orderBy('id')->get(['id', 'name']) as $row) {
            $base = Str::slug($row->name) ?: 'product';
            $slug = $base;
            $i = 2;
            while (in_array($slug, $used, true)) {
                $slug = "{$base}-{$i}";
                $i++;
            }
            $used[] = $slug;
            DB::table('products')->where('id', $row->id)->update(['slug' => $slug]);
        }

        // Guests can check out without an account.
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->string('city', 100)->nullable()->after('shipping_address');
            $table->text('notes')->nullable()->after('city');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('size', 32)->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('size');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['city', 'notes']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
