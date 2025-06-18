<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entry_statement_additional_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entry_statement_id'); // الحركة المرتبطة
            $table->string('title'); // عنوان الرسوم الإضافية
            $table->decimal('fee', 8, 2); // قيمة الرسوم
            $table->boolean('isCompleteFinance')->default(false); // حالة الدفع
            $table->timestamps();

            // العلاقة مع جدول الحركات
            $table->foreign('entry_statement_id')
                ->references('id')
                ->on('entry_statements')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_statement_additional_fees');
    }
};
