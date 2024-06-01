<?php

namespace App\Helpers;

use App\Models\Leave;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class NotificationHelper {
    public static function notificate(
        User $user,
        string $type,
        string $title,
        string $content,
    ): ?Notification
    {
        if(!self::validateContents($type, $content)) {
            return null;
        }

        $notification = new Notification();
        $notification->title = $title;
        $notification->content = $content;
        $notification->type = $type;
        $notification->user_id = $user->id;
        $notification->save();

        return $notification;
    }


    private static function validateContents(string $type, string $content): bool
    {
        if(!in_array($type, Notification::TYPES)) {
            return false;
        }

        if(!is_int($content) && !is_string($content)) {
            return false;
        }

        if(is_int($content)) {
            switch($type) {
                case 'task':
                    if(!Task::find($content)) {
                        return false;
                    }
                    break;
                case 'comment':
                    if(!Task::find($content)) {
                        return false;
                    }
                    break;
                case 'project':
                    if(!Project::find($content)) {
                        return false;
                    }
                    break;
                case 'leave':
                    if(!Leave::find($content)) {
                        return false;
                    }
                    break;
            }
        }

        return true;
    }
}
