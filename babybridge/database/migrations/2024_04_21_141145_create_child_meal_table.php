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
        Schema::create('child_meal', function (Blueprint $table) {
            $table->id('id_child_meal');
            $table->foreignId('child_id')->references('id_child')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('meal_id')->references('id_meal')->on('meals')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->dateTime('meal_time');
            $table->enum('quantity', ['full', 'half', 'quarter', 'refused']);
            $table->string('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_meal');
    }
};
