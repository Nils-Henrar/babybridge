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
        Schema::create('tutor_child', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('locality_id')->nullable()->references('id_locality')->on('localities')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('child_id')->references('id_child')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->string('pm_last_four', 4)->nullable();
            $table->timestamp('trial_ends_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_child');
    }
};
