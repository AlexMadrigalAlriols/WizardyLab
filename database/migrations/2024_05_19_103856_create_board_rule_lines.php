<?php

use App\Models\BoardRuleLine;
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
        Schema::create('board_rule_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_rule_id')->constrained()->cascadeOnDelete();
            $table->enum('operator', BoardRuleLine::TYPES)->default('ACTION');
            $table->integer('order')->default(0);
            $table->text('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_rule_lines');
    }
};
