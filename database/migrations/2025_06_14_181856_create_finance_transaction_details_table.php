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
        Schema::create('finance_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finance_transaction_id'); // العلاقة مع جدول الحركات المالية
            $table->decimal('fee', 12, 2)->default(0);
            $table->decimal('penalty', 12, 2)->default(0);
            $table->decimal('violations_total', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('finance_transaction_id')
                ->references('id')
                ->on('finance_transactions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transaction_details');
    }
};
