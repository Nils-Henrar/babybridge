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
        Schema::create('event_section', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('section_id')->references('id')->on('sections')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_section');
    }
};
