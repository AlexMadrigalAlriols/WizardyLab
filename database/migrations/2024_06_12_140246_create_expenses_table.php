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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portal_id')->constrained();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('item_id')->nullable()->constrained();
            $table->string('name');
            $table->decimal('amount', 15, 2);
            $table->integer('quantity')->default(1);
            $table->boolean('facturable')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
