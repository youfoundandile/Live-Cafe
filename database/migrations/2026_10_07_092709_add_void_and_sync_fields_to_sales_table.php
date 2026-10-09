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
        Schema::table('sales', function (Blueprint $table) {
            //
            $table->uuid('client_uuid')->nullable()->unique()->after('id');
            $table->foreignId('voided_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->dateTime('voided_at')->nullable()->after('voided_by');
            $table->string('void_reason', 255)->nullable()->after('voided_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            //
            $table->dropConstrainedForeignId('voided_by');
            $table->dropUnique(['client_uuid']);
            $table->dropColumn(['client_uuid', 'voided_at', 'void_reason']);
        });
    }
};
