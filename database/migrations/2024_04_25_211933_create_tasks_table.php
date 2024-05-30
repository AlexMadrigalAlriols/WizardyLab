<?php

use App\Models\Task;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('duedate')->nullable();
            $table->float('limit_hours')->nullable();
            $table->enum('priority', Task::PRIORITIES)->default('low');
            $table->foreignId('assignee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('status_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
