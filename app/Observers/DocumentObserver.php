<?php

namespace App\Observers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentObserver
{
    public function deleting(Document $document)
    {
        if(Storage::disk('public')->exists($document->path)) {
            Storage::disk('public')->delete($document->path);
        }
    }
}
