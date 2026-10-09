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
        Schema::create('sync_conflicts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->constrained('sales')->cascadeOnDelete();   // matches Andile's naming
            $table->morphs('stockable');                                             // the Product or Ingredient that ran short
            $table->decimal('shortfall', 10, 3);
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->enum('resolution', ['recount', 'voided', 'accepted_loss'])->nullable();
            $table->string('resolution_note', 255)->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_conflicts');
    }
};
