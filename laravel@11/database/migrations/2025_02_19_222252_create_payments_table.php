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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->foreignId('company_id')->nullable()->constrained('company')->nullOnDelete();
        });

        Schema::create('payment_status', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->foreignId('company_id')->nullable()->constrained('company')->nullOnDelete();
        });

        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->foreignId('company_id')->nullable()->constrained('company')->nullOnDelete();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->decimal('amount', 10, 2)->check('amount > 0');
            $table->foreignId('method_id')->constrained('payment_methods')->restrictOnDelete();
            $table->foreignId('status_id')->constrained('payment_status')->restrictOnDelete();
            $table->string('transaction_id')->unique();
            $table->foreignId('transaction_type_id')->constrained('transaction_types')->restrictOnDelete(); // Update this line to use the new table
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('payment_status');
        Schema::dropIfExists('transaction_types');
        Schema::dropIfExists('payments');
    }
};
