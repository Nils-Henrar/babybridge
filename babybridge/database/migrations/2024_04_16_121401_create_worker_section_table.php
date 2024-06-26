<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('section_worker', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('worker_id')->references('id')->on('childcare_workers')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('section_id')->references('id')->on('sections')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamp('from')->useCurrent();
            $table->dateTime('to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_worker');
    }
};
