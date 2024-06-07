<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    public function deleting(Project $project)
    {
        $project->tasks()->delete();
        $project->users()->detach();
        $project->boardRules()->detach();
        $project->avaiableStatuses()->delete();
    }
}
