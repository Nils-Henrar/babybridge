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
        //

        Schema::create('activity_child', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('child_id')->references('id')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('activity_id')->references('id')->on('activities')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->dateTime('performed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

        Schema::table('activity_child', function (Blueprint $table) {
            $table->dropForeign(['child_id']);
            $table->dropForeign(['activity_id']);
        });


        Schema::dropIfExists('activity_child');
    }
};
