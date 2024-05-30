<?php

namespace App\UseCases\Projects;

use App\Models\Project;
use App\Models\Status;
use App\UseCases\Core\UseCase;

class UpdateStatusUseCase extends UseCase
{
    public function __construct(
        protected Project $project,
        protected Status $status
    ) {
    }

    public function action(): Project
    {
        $this->project->update([
            'status_id'=> $this->status->id,
        ]);

        return $this->project;
    }
}
