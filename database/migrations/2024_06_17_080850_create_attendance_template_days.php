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
        Schema::create('attendance_template_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_template_id')->constrained()->cascadeOnDelete();
            $table->string('weekday'); // Monday, Friday, etc
            $table->time('start_time');
            $table->time('end_time');
            $table->time('start_break')->nullable();
            $table->time('end_break')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_template_days');
    }
};
