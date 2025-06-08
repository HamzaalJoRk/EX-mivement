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
            $table->foreignId('exit_border_crossing_id')->nullable()->constrained('border_crossings')->onDelete('cascade');
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
