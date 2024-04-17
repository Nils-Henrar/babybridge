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
        Schema::create('worker_sections', function (Blueprint $table) {
            $table->id('id_worker_section');
            $table->foreignId('worker_id')->references('id_worker')->on('childcare_workers')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('section_id')->references('id_section')->on('sections')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->dateTime('from');
            $table->dateTime('to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_sections');
    }
};
