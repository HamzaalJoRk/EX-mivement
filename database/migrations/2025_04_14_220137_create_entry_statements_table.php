<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('financial_receipts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entry_statement_id');
            $table->foreign('entry_statement_id')->references('id')->on('entry_statements')->onDelete('cascade');

            $table->string('cashier_number');         // رقم الهيكل
            $table->string('cashier_name');           // اسم الصندوق أو المسؤول

            $table->string('receipt_number');         // رقم الإيصال
            $table->string('statement_number');       // رقم التصفية
            $table->string('driver_name');            // اسم السائق
            $table->string('car_number');             // رقم السيارة

            $table->decimal('fees', 10, 2);           // رسم العبور 
            $table->decimal('additionalFee', 10, 2);  // الرسوم الإضافية 
            $table->decimal('violations_total', 10, 2); // مجموع الغرامات
            $table->decimal('total_amount', 10, 2);   // الإجمالي

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_statements');
    }
};
