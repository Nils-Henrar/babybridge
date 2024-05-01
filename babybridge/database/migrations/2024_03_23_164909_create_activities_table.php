<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id('id');
            $table->string('description', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
