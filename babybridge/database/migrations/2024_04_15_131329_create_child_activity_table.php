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

        Schema::create('child_activity', function (Blueprint $table) {
            $table->id('id_child_activity');
            $table->foreignId('child_id')->references('id_child')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('activity_id')->references('id_activity')->on('activities')
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

        Schema::table('child_activity', function (Blueprint $table) {
            $table->dropForeign(['child_id']);
            $table->dropForeign(['activity_id']);
        });


        Schema::dropIfExists('child_activity');
    }
};
