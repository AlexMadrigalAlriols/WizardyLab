<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ConfigurationHelper;
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
                'total' => $user->tasks()->where('status_id', ConfigurationHelper::get('completed_task_status'))->count(),
                'pending' => $user->tasks()->where('status_id', '!=', ConfigurationHelper::get('completed_task_status'))->count(),
                'overdue' => $user->tasks()->where('status_id', '!=', ConfigurationHelper::get('completed_task_status'))->where('duedate', '<', now()->startOfDay())->count()
            ],
            'projects' => [
                'total' => $user->projects()->count(),
                'active' => $user->projects()->where('status_id', '!=', ConfigurationHelper::get('completed_project_status'))->count(),
                'overdue' => $user->projects()->where('status_id', '!=', ConfigurationHelper::get('completed_project_status'))->where('deadline', '<', now()->startOfDay())->count()
            ]
        ];

        $tasks = $user->tasks()->orderBy('duedate', 'desc')->orderBy('updated_at', 'desc')->limit(5)->get();

        foreach ($weekdays as $wday) {
            $events[$wday] = Leave::where('user_id', $user->id)
                ->where('date', '<=', now()->startOfWeek()->addDays(array_search($wday, $weekdays)))
                ->where('date', '>=', now()->startOfWeek()->addDays(array_search($wday, $weekdays)))
                ->where('approved', 1)
                ->get();

            $userTasks = $user->tasks()->where('duedate', '<=', now()->startOfWeek()->addDays(array_search($wday, $weekdays)))
            ->where('duedate', '>=', now()->startOfWeek()->addDays(array_search($wday, $weekdays)))
            ->where('status_id', '!=', ConfigurationHelper::get('completed_task_status'))
            ->get();
            $events[$wday] = $events[$wday]->merge($userTasks);
        }

        return view('dashboard.index', compact('user', 'now_hour', 'weekday', 'counters', 'tasks', 'weekdays', 'events'));
    }

    public function readNotifications()
    {
        auth()->user()->notifications()->unread()->update([
            'is_read' => true
        ]);

        toast('Notifications read', 'success');
        return redirect()->back();
    }
}
