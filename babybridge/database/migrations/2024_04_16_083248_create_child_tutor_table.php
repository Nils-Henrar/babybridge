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
        Schema::create('child_tutor', function (Blueprint $table) {
            $table->id('id_child_tutor');
            $table->foreignId('child_id')->references('id_child')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('tutor_id')->references('id_tutor')->on('tutors')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_tutor');
    }
};
