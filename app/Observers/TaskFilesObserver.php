<?php

namespace App\Observers;

use App\Models\TaskFile;
use Illuminate\Support\Facades\Storage;

class TaskFilesObserver
{
    public function deleting(TaskFile $taskFile)
    {
        Storage::disk('public')->delete($taskFile->path);
    }
}
