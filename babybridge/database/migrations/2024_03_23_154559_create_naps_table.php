<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('naps', function (Blueprint $table) {
            $table->id('id_nap');
            $table->foreignId('child_id')->references('id_child')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('quality', ['good', 'average', 'bad']);
            $table->string('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naps');
    }
};
