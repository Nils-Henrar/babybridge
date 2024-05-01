<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id('id');
            $table->string('type', 30);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
