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
        Schema::create('childcare_workers', function (Blueprint $table) {
            $table->id('id_worker');
            $table->foreignId('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->string('firstname', 60);
            $table->string('lastname', 60);
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('childcare_workers');
    }
};
