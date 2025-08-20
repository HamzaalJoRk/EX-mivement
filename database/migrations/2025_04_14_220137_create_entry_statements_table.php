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
        Schema::create('entry_statements', function (Blueprint $table) {
            $table->id();
            $table->string('car_type');
            $table->string('car_nationality');
            $table->string('car_brand');
            $table->string('driver_name');
            $table->string('car_number');
            $table->integer('stay_duration');
            $table->decimal('stay_fee', 8, 2);
            $table->string('serial_number');
            $table->boolean('is_checked_out')->default(0);
            $table->boolean('completeFinanceExit')->default(0);
            $table->date('checked_out_date')->nullable();
            $table->decimal('exit_fee', 8, 2)->nullable();
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
