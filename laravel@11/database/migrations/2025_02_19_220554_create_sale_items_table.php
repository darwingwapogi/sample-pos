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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->unsignedInteger('quantity')->check('quantity > 0');
            $table->decimal('cost_price', 10, 2)->check('cost_price > 0');
            $table->decimal('markup', 10, 2)->default(0)->check('markup >= 0');
            $table->decimal('markup_amount', 10, 2)->default(0)->check('markup_amount >= 0');
            $table->decimal('percentage_discount', 5, 2)->default(0)->check('percentage_discount >= 0');
            $table->decimal('discount_amount', 10, 2)->default(0)->check('discount_amount >= 0');
            $table->decimal('whole_sale_price', 10, 2)->default(0)->check('whole_sale_price >= 0');
            $table->decimal('selling_price', 10, 2)->check('selling_price > 0');
            $table->decimal('discounted_price', 10, 2)->default(0)->check('discounted_price >= 0');
            $table->decimal('final_selling_price', 10, 2)->check('final_selling_price > 0');
            $table->boolean('is_vat_enabled')->default(false);
            $table->decimal('vat_amount', 10, 2)->default(0)->check('vat_amount >= 0');
            $table->decimal('vat_percentage', 5, 2)->default(0)->check('vat_percentage >= 0');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
