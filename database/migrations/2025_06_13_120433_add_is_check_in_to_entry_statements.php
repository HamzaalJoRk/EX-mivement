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
        Schema::table('entry_statements', function (Blueprint $table) {
            $table->boolean('is_checked_in')->default(0);
            $table->boolean('completeFinanceEntry')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entry_statements', function (Blueprint $table) {
            //
        });
    }
};
