<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id('id_photo');
            $table->foreignId('child_id')->references('id_child')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->string('description')->nullable();
            $table->timestamp('taken_at')->useCurrent();
            $table->string('path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
