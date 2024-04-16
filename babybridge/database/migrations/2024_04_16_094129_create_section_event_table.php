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
        Schema::create('section_event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->references('id_section')->on('sections')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('event_id')->references('id_event')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_event');
    }
};
