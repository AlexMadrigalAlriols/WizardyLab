<?php

namespace App\UseCases\Projects;

use App\Models\Client;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected Project $project,
        protected User $user,
        protected ?Client $client = null,
        protected string $name,
        protected ?Carbon $start_date = null,
        protected ?Carbon $due_date = null,
        protected ?int $limit_hours = null,
        protected Status $status,
        protected string $description = '',
        protected array $users = [],
        protected array $departments = []
    ) {
    }

    public function action(): Project
    {
        $this->project->update([
            'name' => $this->name,
            'client_id' => $this->client?->id,
            'start_date' => $this->start_date,
            'deadline' => $this->due_date,
            'limit_hours' => $this->limit_hours,
            'status_id' => $this->status->id,
            'description' => $this->description,
        ]);
        $this->project->users()->detach();
        if(count($this->departments)) {
            $users = User::whereIn('department_id', $this->departments)->get();

            if($users->count()) {
                $this->users = array_unique(array_merge($this->users, $users->pluck('id')->toArray()));
            }
        }

        $this->project->users()->attach($this->users);

        return $this->project;
    }
}
