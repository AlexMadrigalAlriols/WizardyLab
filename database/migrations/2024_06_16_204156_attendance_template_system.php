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
        Schema::create('attendance_template', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('duration');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('attendance_template_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_template');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('attendance_template_id');
        });
    }
};
