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
        Schema::connection('mysqlCRM')->create('leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('portal_id');
            $table->foreign('portal_id')->references('id')->on('wizardylab.portals');
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('industry')->nullable();
            $table->string('status')->default('new');
            $table->string('origin')->default('manual');
            $table->text('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysqlCRM')->dropIfExists('leads');
    }
};
