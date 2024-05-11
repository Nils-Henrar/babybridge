<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('naps', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('child_id')->references('id')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('quality')->nullable();
            $table->string('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naps');
    }
};
