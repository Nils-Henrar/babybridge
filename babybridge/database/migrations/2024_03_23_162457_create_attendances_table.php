<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('id_attendance');
            $table->foreignId('child_id')->references('id_child')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->date('attendance_date');
            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->string('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
