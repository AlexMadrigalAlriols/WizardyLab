<?php

use App\Models\Portal;
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
        Schema::table('portals', function (Blueprint $table) {
            $table->date('subscription_ends_at')->nullable()->after('storage_size');
            $table->enum('subscription_type', Portal::SUBSCRIPTION_TYPES)->default('Basic')->after('subscription_ends_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portals', function (Blueprint $table) {
            $table->dropColumn('subscription_ends_at');
            $table->dropColumn('subscription_type');
        });
    }
};
