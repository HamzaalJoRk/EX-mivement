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
