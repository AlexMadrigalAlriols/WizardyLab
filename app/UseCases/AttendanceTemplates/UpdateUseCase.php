<?php

namespace App\UseCases\AttendanceTemplates;

use App\Models\AttendanceTemplate;
use App\UseCases\Core\UseCase;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected AttendanceTemplate $attendanceTemplate,
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
        $this->attendanceTemplate->update([
            'name' => $this->name,
            'data' => array_merge($this->attendanceTemplate->data, $this->data)
        ]);

        foreach ($this->attendanceTemplate->days ?? [] as $day) {
            $day->update([
                'start_time' => $this->start_time[$day->weekday] ?? '09:00:00',
                'end_time' => $this->end_time[$day->weekday] ?? '18:00:00',
                'start_break' => $this->start_break[$day->weekday] ?? '11:00:00',
                'end_break' => $this->end_break[$day->weekday] ?? '11:30:00'
            ]);
        }

        return $this->attendanceTemplate;
    }
}
