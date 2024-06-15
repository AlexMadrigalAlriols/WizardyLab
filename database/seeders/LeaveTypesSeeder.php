<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            ['name' => 'Festivos regionales/locales', 'data' => json_encode(['background' => '#0CCF67', 'color' => '#ffffff'])],
            ['name' => 'Compensación de Horas', 'data' => json_encode(['background' => '#FF6D1F', 'color' => '#ffffff'])],
            ['name' => 'Festivo Nacional', 'data' => json_encode(['background' => '#47759B', 'color' => '#ffffff'])],
            ['name' => 'Vacaciones', 'data' => json_encode(['background' => '#6DE2A3', 'color' => '#ffffff'])]
        ];

        foreach ($leaveTypes as $key => $status) {
            $leaveTypes[$key]['portal_id'] = 1;
        }

        LeaveType::insert($leaveTypes);
    }
}
