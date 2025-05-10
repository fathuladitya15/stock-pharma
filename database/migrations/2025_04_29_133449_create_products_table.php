<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('unit');
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(10);
            $table->integer('lead_time')->default(7);
            $table->integer('average_demand')->default(100);
            $table->integer('poq_quantity')->nullable();
            $table->decimal('ordering_cost', 12, 2)->nullable(); // Contoh: 100000
            $table->decimal('holding_cost_percent', 5, 4)->default(0.10); // 10% = 0.10
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
