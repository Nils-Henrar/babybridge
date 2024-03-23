<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id('id_activity');
            $table->foreignId('child_id')->references('id_child')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->string('description', 255);
            $table->dateTime('performed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
