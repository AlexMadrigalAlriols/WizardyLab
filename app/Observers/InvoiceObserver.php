<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class InvoiceObserver
{
    public function deleting(Invoice $invoice)
    {
        if(Storage::disk('public')->exists($invoice->file_path)) {
            Storage::disk('public')->delete($invoice->file_path);
        }
    }
}
