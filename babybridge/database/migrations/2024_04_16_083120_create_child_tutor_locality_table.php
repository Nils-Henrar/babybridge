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
        Schema::create('tutor_locality', function (Blueprint $table) {
            $table->id('id_tutor_locality');
            $table->foreignId('tutor_id')->references('id_tutor')->on('tutors')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('locality_id')->references('id_locality')->on('localities')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_locality');
    }
};
