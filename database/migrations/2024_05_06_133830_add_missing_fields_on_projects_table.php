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
        Schema::table('projects', function (Blueprint $table) {
            $table->date('start_date')->after('deadline')->nullable();
            $table->foreignId('status_id')->after('start_date')->constrained()->cascadeOnDelete();
            $table->float('limit_hours')->after('status_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropConstrainedForeignId('status_id');
            $table->dropColumn('limit_hours');
        });
    }
};
