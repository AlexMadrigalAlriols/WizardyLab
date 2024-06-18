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
        Schema::create('attendance_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('data')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('attendance_template_id')->nullable()->after('gender')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_templates');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('attendance_template_id');
        });
    }
};
