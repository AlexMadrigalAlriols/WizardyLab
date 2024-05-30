<?php

namespace App\UseCases\Tasks;

use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected Task $task,
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
        $this->task->update([
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
        $this->task->users()->detach();
        if(count($this->departments)) {
            $users = User::whereIn('department_id', $this->departments)->get();

            if($users->count()) {
                $this->users = array_unique(array_merge($this->users, $users->pluck('id')->toArray()));
            }
        }

        $this->task->users()->attach($this->users);
        $this->task->labels()->detach();
        $this->task->labels()->attach($this->labels);

        if($this->project && !$this->project->avaiableStatuses()->where('status_id', $this->status->id)->exists()) {
            $lastOrder = $this->task->project->avaiableStatuses()->max('order');
            $this->task->project->avaiableStatuses()->create([
                'status_id' => $this->status->id,
                'order' => $lastOrder + 1,
            ]);
        }

        return $this->task;
    }
}
