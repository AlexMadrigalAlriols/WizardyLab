<?php

namespace App\UseCases\AttendanceTemplates;

use App\Models\AttendanceTemplate;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected string $name,
        protected $data = [],
        protected array $start_time = [],
        protected array $end_time = [],
        protected array $start_break = [],
        protected array $end_break = []
    ) {
    }

    public function action(): AttendanceTemplate
    {
        $attendanceTemplate = AttendanceTemplate::create([
            'name' => $this->name,
            'data' => $this->data
        ]);

        foreach (AttendanceTemplate::WEEKDAYS as $weekday) {
            $attendanceTemplate->days()->create([
                'weekday' => $weekday,
                'start_time' => $this->start_time[$weekday] ?? '09:00:00',
                'end_time' => $this->end_time[$weekday] ?? '18:00:00',
                'start_break' => $this->start_break[$weekday] ?? '11:00:00',
                'end_break' => $this->end_break[$weekday] ?? '11:30:00'
            ]);
        }

        return $attendanceTemplate;
    }
}
