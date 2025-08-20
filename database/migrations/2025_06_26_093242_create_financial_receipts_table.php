<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finance_transaction_id');
            $table->foreign('finance_transaction_id')->references('id')->on('finance_transactions')->onDelete('cascade');
            $table->string('cashier_number');
            $table->string('cashier_name');
            $table->string('receipt_number');
            $table->string('statement_number');
            $table->string('driver_name');
            $table->string('car_number');
            $table->decimal('fees', 10, 2);
            $table->decimal('additionalFee', 10, 2);
            $table->decimal('violations_total', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_receipts');
    }
};
