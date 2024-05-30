<?php

use App\Models\User;
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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('iso_code');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('code', 4)->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('profile_img')->nullable();
            $table->enum('gender', User::GENDERS)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('joining_date')->default(now());
            $table->date('birthday_date')->default(now());
            $table->foreignId('reporting_user_id')->nullable()->constrained('users');
            $table->foreignId('department_id')->nullable()->constrained();
            $table->foreignId('role_id')->nullable()->constrained();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('departments');
    }
};
