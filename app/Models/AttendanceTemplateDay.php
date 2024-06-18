<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceTemplateDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_template_id',
        'weekday',
        'start_time',
        'end_time',
        'start_break',
        'end_break',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'start_break' => 'datetime:H:i:s',
        'end_break' => 'datetime:H:i:s'
    ];

    public function attendanceTemplate()
    {
        return $this->belongsTo(AttendanceTemplate::class);
    }
}


