<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('child_id')->references('id')->on('children')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->text('description');
            $table->dateTime('reported_at');
            $table->enum('severity', ['low', 'medium', 'high'])->nullable();
            $table->boolean('notify_parents')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
