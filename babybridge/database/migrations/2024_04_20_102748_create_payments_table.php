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
            $table->foreignId('tutor_child_id')->references('id')->on('tutor_child')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('event_id')->references('id_event')->on('events')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->string('stripe_id')->index();
            $table->string('status');
            $table->string('currency');
            $table->string('amount');
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
