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
        Schema::create('tickets_type', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->string('name', 150);
            $table->string('description', 500)->nullable();
            $table->integer('quantity_available');
            $table->float('price');
            $table->date('sale_start_date');
            $table->time('sale_start_time');
            $table->date('sale_end_date');
            $table->time('sale_end_time');
            $table->integer('purchase_limit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets_type');
    }
};
