<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diaper_changes', function (Blueprint $table) {
            $table->id('id_diaper');
            $table->foreignId('child_id')->references('id_child')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->enum('poop_consistency', ['normal', 'soft', 'watery'])->nullable();
            $table->dateTime('happened_at');
            $table->string('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diaper_changes');
    }
};
