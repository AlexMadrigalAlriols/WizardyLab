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
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portal_id')->constrained();
            $table->string('number')->unique();
            $table->foreignId('client_id')->constrained();
            $table->date('issue_date');
            $table->text('data')->nullable();
            $table->float('amount')->default(0.00);
            $table->float('tax')->default(0.00);
            $table->float('total')->default(0.00);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_notes');
    }
};
