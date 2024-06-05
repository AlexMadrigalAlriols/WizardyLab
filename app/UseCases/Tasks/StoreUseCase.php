<?php

namespace App\UseCases\Tasks;

use App\Helpers\NotificationHelper;
use App\Helpers\TaskHelper;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected User $user,
        protected string $title,
        protected string $description,
        protected string $priority,
        protected Status $status,
        protected ?int $limit_hours = null,
        protected ?Carbon $due_date = null,
        protected ?Carbon $start_date = null,
        protected array $labels = [],
        protected array $users = [],
        protected array $departments = [],
        protected ?Project $project = null,
        protected ?Task $parent_task = null
    ) {
    }

    public function action(): Task
    {
        $code = TaskHelper::generateCode($this->project);

        $task = Task::create([
            'code' => $code, // 'T-1234567890
            'assignee_id' => $this->user->id,
            'status_id'=> $this->status->id,
            'title'=> $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'limit_hours' => $this->limit_hours,
            'duedate' => $this->due_date,
            'start_date' => $this->start_date,
            'project_id' => $this->project?->id,
            'task_id' => $this->parent_task?->id
        ]);

        if(count($this->departments)) {
            $users = User::whereIn('department_id', $this->departments)->get();

            if($users->count()) {
                $this->users = array_unique(array_merge($this->users, $users->pluck('id')->toArray()));
            }
        }
        $task->users()->attach($this->users);
        $task->labels()->attach($this->labels);

        if($this->project && !$this->project->avaiableStatuses()->where('status_id', $this->status->id)->exists()) {
            $lastOrder = $this->project->avaiableStatuses()->max('order');
            $this->project->avaiableStatuses()->create([
                'status_id' => $this->status->id,
                'order' => $lastOrder + 1,
            ]);
        }

        foreach ($this->users as $user) {
            if($this->user->id != $user) {
                NotificationHelper::notificate(
                    User::find($user),
                    Notification::TYPES['task'],
                    'New Task Assigned To You',
                    $task->id
                );
            }
        }

        return $task;
    }
}
