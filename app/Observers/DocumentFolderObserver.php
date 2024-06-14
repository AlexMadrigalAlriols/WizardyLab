<?php

namespace App\Observers;

use App\Models\DocumentFolder;
use App\Models\Task;

class DocumentFolderObserver
{
    public function deleting(DocumentFolder $folder)
    {
        $folder->documents()->delete();
    }
}
