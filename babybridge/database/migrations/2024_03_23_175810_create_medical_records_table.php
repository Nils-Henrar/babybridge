<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id('id_mrecord');
            $table->foreignId('child_id')->references('id_child')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->text('description');
            $table->timestamp('created_at')->useCurrent();
            //updatedId est null car il sera null quand l'enregistrement est créé
            $table->integer('updatedId')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
