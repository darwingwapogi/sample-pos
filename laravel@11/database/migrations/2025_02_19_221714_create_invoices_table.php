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
        Schema::create('invoice_status', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->unique();
            $table->foreignId('company_id')->nullable()->constrained('company')->nullOnDelete();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->unique()->constrained('sales')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('company')->nullOnDelete();
            $table->date('due_date');
            $table->foreignId('status_id')->constrained('invoice_status')->onDelete('restrict');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_status');
        Schema::dropIfExists('invoices');
    }
};
