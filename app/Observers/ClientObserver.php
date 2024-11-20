<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\Item;

class ClientObserver
{
    public function deleting(Client $client)
    {
        $client->invoices()->forceDelete();
    }
}
