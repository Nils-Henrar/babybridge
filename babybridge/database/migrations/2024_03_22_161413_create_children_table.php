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
        Schema::create('children', function (Blueprint $table) {
            $table->id('id');
            $table->string('lastname', 60);
            $table->string('firstname', 60);
            $table->enum('gender', ['M', 'F']);
            $table->date('birthdate');
            $table->text('special_infos')->nullable();
            $table->string('photo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('children');
    }
};
