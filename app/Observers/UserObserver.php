<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function deleting(User $user)
    {
        $user->attendances()->delete();
        $user->UserInventories()->delete();
    }
}
