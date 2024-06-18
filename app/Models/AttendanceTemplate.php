<?php

namespace App\Models;

use App\Helpers\SubdomainHelper;
use App\Models\Scopes\PortalScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceTemplate extends Model
{
    use HasFactory;

    public const WEEKDAYS = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday'
    ];

    protected $fillable = [
        'name',
        'data'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'data' => 'array'
    ];

    protected static function booted()
    {
        $portal = SubdomainHelper::getPortal(request());
        static::addGlobalScope(new PortalScope($portal->id));

        static::creating(function ($model) use ($portal) {
            $model->portal_id = $portal->id;
        });
    }

    public function days()
    {
        return $this->hasMany(AttendanceTemplateDay::class);
    }

    public function getHoursPerDay(string $weekday)
    {
        $day = AttendanceTemplateDay::where('weekday', $weekday)->first();

        $startTime = Carbon::parse($day->start_time);
        $endTime = Carbon::parse($day->end_time);

        $startBreak = Carbon::parse($day->start_break);
        $endBreak = Carbon::parse($day->end_break);

        $totalWorkTime = $endTime->diffInMinutes($startTime);
        $totalBreakTime = $endBreak->diffInMinutes($startBreak);
        $effectiveWorkTime = $totalWorkTime - $totalBreakTime;

        $hours = intdiv($effectiveWorkTime, 60);
        $minutes = $effectiveWorkTime % 60;

        return sprintf('%02dh %02dm', $hours, $minutes);
    }

    public function getStylesAttribute() {
        $styles = [
            'background-color' => $this->data['background'] ?? '#fff',
            'color' => $this->data['color'] ?? '#000',
        ];

        $string = '';
        foreach ($styles as $key => $style) {
            $string .= $key . ': ' . $style . ';';
        }

        return $string;
    }
}


