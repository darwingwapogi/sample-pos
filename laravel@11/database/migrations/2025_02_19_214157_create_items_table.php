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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unique(['name', 'company_id'], 'category_name_company_unique');
            $table->foreignId('company_id')->nullable()->constrained('company')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('item_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unique(['name', 'company_id'], 'item_type_name_company_unique');
            $table->foreignId('company_id')->nullable()->constrained('company')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol', 10);
            $table->unique(['symbol', 'company_id'], 'unit_symbol_company_unique');
            $table->foreignId('company_id')->nullable()->constrained('company')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->text('description')->nullable();
            $table->decimal('stock_quantity', 10, 2)->default(0)->check('stock_quantity >= 0');
            $table->string('sku', 50);
            $table->string('barcode', 50)->nullable();
            $table->string('size', 100)->nullable();
            $table->string('color', 100)->nullable();
            $table->decimal('reorder_level', 10, 2)->default(0);
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
            $table->date('expiry_date')->nullable();
            $table->unique(
                ['name', 'brand', 'model', 'sku', 'supplier_id', 'unit_id', 'company_id', 'item_type_id', 'category_id'],
                'items_unique_constraint'
            );
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->foreignId('unit_id')->references('id')->on('units');
            $table->foreignId('company_id')->constrained('company')->onDelete('cascade');
            $table->foreignId('item_type_id')->nullable()->constrained('item_types')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('item_status')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('item_types');
        Schema::dropIfExists('units');
        Schema::dropIfExists('items');
    }
};
