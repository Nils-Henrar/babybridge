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
        Schema::create('section_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->references('id_section')->on('sections')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('type_id')->references('id_type')->on('types')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamp('from');
            $table->timestamp('to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_type');
    }
};
