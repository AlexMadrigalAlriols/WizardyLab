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
        $tables = [
            'users',
            'departments',
            'labels',
            'leave_types',
            'companies',
            'clients',
            'projects',
            'tasks',
            'statuses',
            'items',
            'invoices',
            'global_configurations'
        ];


        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('portal_id')->after('id')->constrained();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'departments',
            'labels',
            'leave_types',
            'companies',
            'clients',
            'projects',
            'tasks',
            'statuses',
            'items',
            'invoices',
            'global_configurations'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropConstrainedForeignId('portal_id');
            });
        }
    }
};
