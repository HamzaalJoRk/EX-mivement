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
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id(); // رقم تسلسلي
            $table->unsignedBigInteger('finance_box_id'); // الصندوق الذي يتبع له
            $table->decimal('amount', 12, 2); // المبلغ
            $table->string('description'); // وصف الحركة
            $table->string('operation_for'); // الحركة التي تم الدفع لأجلها (مثلاً: رقم الطلب أو اسم العملية)
            $table->timestamps();

            $table->foreign('finance_box_id')
                ->references('id')
                ->on('finance_boxes')
                ->onDelete('cascade');
            $table->unsignedBigInteger('entry_statement_id'); // التصفية الذي يتبع لها

            $table->string('cashier_number');         // رقم الصندوق
            $table->string('cashier_name');           // اسم الصندوق أو المسؤول

            $table->string('receipt_number');         // رقم الإيصال
            $table->string('statement_number');       // رقم التصفية
            $table->string('driver_name');            // اسم السائق
            $table->string('car_number');             // رقم السيارة

            $table->decimal('fees', 10, 2);           // رسم العبور 
            $table->decimal('additionalFee', 10, 2);  // الرسوم الإضافية 
            $table->decimal('violations_total', 10, 2); // مجموع الغرامات
            $table->decimal('total_amount', 10, 2);   // الإجمالي

            $table->foreign('entry_statement_id')
                ->references('id')
                ->on('entry_statements')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
