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
        Schema::create('poqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('average_demand', 15, 2);
            $table->decimal('demand_per_year',15, 2);
            $table->decimal('ordering_cost', 15, 2);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('holding_cost', 15, 2);
            $table->decimal('eoq', 15, 2);
            $table->decimal('poq', 15, 2);
            $table->decimal('quantity',15,2);
            $table->foreignId('calculated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poqs');
    }
};
