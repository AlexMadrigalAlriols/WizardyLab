<?php

namespace App\Observers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentObserver
{
    public function deleted(Document $document)
    {
        if(Storage::disk('public')->exists('storage/' . $document->path)) {
            Storage::disk('public')->delete('storage/' . $document->path);
        }
    }
}
