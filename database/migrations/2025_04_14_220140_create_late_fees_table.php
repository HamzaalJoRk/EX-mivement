<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('late_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entry_statement_id');
            $table->string('type');
            $table->decimal('fee', 8, 2);
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('late_fees');
    }
};
