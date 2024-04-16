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
        Schema::create('child_section', function (Blueprint $table) {
            $table->id('id_child_section');
            $table->foreignId('child_id')->references('id_child')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('section_id')->references('id_section')->on('sections')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('child_section', function (Blueprint $table) {
            $table->dropForeign(['child_id']);
            $table->dropForeign(['section_id']);
        });

        Schema::dropIfExists('child_section');
    }
};
