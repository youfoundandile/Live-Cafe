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
        Schema::table('ingredients', function (Blueprint $table) {
            //
            $table->string('unit', 10)->default('kg')->after('quantity');
            $table->decimal('reorder_level', 10, 3)->default(0)->after('unit');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            //
            $table->dropColumn(['unit', 'reorder_level']);

        });
    }
};
