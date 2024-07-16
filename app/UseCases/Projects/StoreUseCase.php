<?php

namespace App\UseCases\Projects;

use App\Models\Client;
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
        protected ?Client $client,
        protected string $name,
        protected string $code,
        protected ?Carbon $start_date = null,
        protected ?Carbon $due_date = null,
        protected ?int $limit_hours = null,
        protected Status $status,
        protected ?string $description = '',
        protected array $users = [],
        protected array $departments = []
    ) {
    }

    public function action(): Project
    {
        $project = Project::create([
            'client_id' => $this->client?->id,
            'name' => $this->name,
            'code' => $this->code,
            'start_date' => $this->start_date,
            'deadline' => $this->due_date,
            'limit_hours' => $this->limit_hours,
            'status_id' => $this->status->id,
            'description' => $this->description,
        ]);

        if(count($this->departments)) {
            $users = User::whereIn('department_id', $this->departments)->get();

            if($users->count()) {
                $this->users = array_unique(array_merge($this->users, $users->pluck('id')->toArray()));
            }
        }

        foreach(Project::where('morphable', Project::class)->get() as $idx => $status) {
            $project->avaiableStatuses()->create([
                'status_id' => $status->id,
                'order' => $idx,
                'collapsed' => false
            ]);
        }

        $project->users()->attach($this->users);

        return $project;
    }
}
