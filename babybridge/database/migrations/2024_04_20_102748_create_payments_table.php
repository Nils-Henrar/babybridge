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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_tutor_id')->references('id')->on('child_tutor')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('event_id')->references('id')->on('events')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->string('stripe_id')->nullable()->index();
            $table->string('status')->nullable();
            $table->string('currency')->nullable();
            $table->string('amount')->nullable();
            $table->timestamp('paid_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
