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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price')->nullable();
            $table->string('shop_place')->nullable();
            $table->integer('stock');
            $table->timestamps();
        });

        Schema::create('item_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('path');
            $table->integer('size');
            $table->timestamps();
        });

        Schema::dropIfExists('inventory_files');
        Schema::dropIfExists('inventories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_files');
    }
};
