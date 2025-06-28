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
        Schema::create('entry_cards', function (Blueprint $table) {
            $table->id();

            // علاقة مع entry_statements
            $table->unsignedBigInteger('entry_statement_id');
            $table->foreign('entry_statement_id')->references('id')->on('entry_statements')->onDelete('cascade');

            $table->string('owner_name');           // اسم المالك
            $table->string('car_number');           // رقم السيارة
            $table->string('car_type');             // نوع السيارة (سياحية، شاحنة...)
            $table->string('stay_duration');        // مدة البقاء (مثلاً: شهر)
            $table->date('entry_date');             // تاريخ الدخول
            $table->date('exit_date');              // تاريخ الانتهاء
            $table->string('qr_code')->nullable();  // رابط أو قيمة QR

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_cards');
    }
};
