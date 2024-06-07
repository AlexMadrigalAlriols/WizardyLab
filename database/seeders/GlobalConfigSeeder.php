<?php

namespace Database\Seeders;

use App\Models\GlobalConfiguration;
use Illuminate\Database\Seeder;

class GlobalConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            ['type' => 'number', 'key' => 'price_per_hour', 'value' => '15'],
            ['type' => 'number', 'key' => 'tax_value', 'value' => '21'],
            ['type' => 'select-status-task', 'key' => 'completed_task_status', 'value' => '7'],
            ['type' => 'select-status-task', 'key' => 'facturated_task_status', 'value' => '9'],
            ['type' => 'select-status-project', 'key' => 'completed_project_status', 'value' => '3'],
            ['type' => 'select-status-invoice', 'key' => 'default_invoice_status', 'value' => '12'],
        ];

        GlobalConfiguration::insert($configs);
    }
}
