<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;

class DashboardController extends Controller
{
    public function index()
    {
        $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $user = auth()->user();
        $now_hour = now()->format('H:i');
        $weekday = now()->format('l');
        $events = [];

        $counters = [
            'tasks' => [
                'total' => $user->tasks()->count(),
                'pending' => $user->tasks()->count(),
                'overdue' => $user->tasks()->where('duedate', '<', now())->count()
            ],
            'projects' => [
                'total' => $user->projects()->count(),
                'active' => $user->projects()->count(),
                'overdue' => $user->projects()->where('deadline', '<', now())->count()
            ]
        ];

        $tasks = $user->tasks()->orderBy('duedate')->limit(5)->get();

        foreach ($weekdays as $wday) {
            $events[$wday] = Leave::where('user_id', $user->id)
                ->where('date', '<=', now()->startOfWeek()->addDays(array_search($wday, $weekdays)))
                ->where('date', '>=', now()->startOfWeek()->addDays(array_search($wday, $weekdays)))
                ->get();

            $userTasks = $user->tasks()->where('duedate', '<=', now()->startOfWeek()->addDays(array_search($wday, $weekdays)))
            ->where('duedate', '>=', now()->startOfWeek()->addDays(array_search($wday, $weekdays)))
            ->get();
            $events[$wday] = $events[$wday]->merge($userTasks);
        }

        return view('dashboard.index', compact('user', 'now_hour', 'weekday', 'counters', 'tasks', 'weekdays', 'events'));
    }
}
