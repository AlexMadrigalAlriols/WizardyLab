<?php

namespace App\Observers;

use App\Models\DocumentFolder;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class DocumentFolderObserver
{
    public function deleting(DocumentFolder $folder)
    {
        foreach ($folder->documents as $document) {
            if(Storage::disk('public')->exists($document->path)) {
                Storage::disk('public')->delete($document->path);
            }
        }

        $folder->documents()->delete();
    }
}
